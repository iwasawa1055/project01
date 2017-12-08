<?php

class AppHttp
{
    public static function request($_url, $_requests = array(), $_method = null, $_headers = array())
    {
        $retry_num = 0;
        $retry_max_num = Configure::read('api.retry_max_num');

        for ($retry_num; $retry_num <= $retry_max_num; $retry_num++) {
            //* Request
            $curls = self::_curl($_url, $_requests, $_method, $_headers);

            // json形式として分解
            $curls['body_parsed'] = self::_parse($curls['body'], 'json');

            // 429 too many requested以外は抜ける
            if ($curls['headers']['http_code'] !== 429) {
                break;
            }

            // リトライ上限に達した場合
            if ($retry_max_num == $retry_num) {
                // ログを出力 & 発報
                new AppMedialCritical(AppE::TOO_MANY_REQUESTS.'Maximum retry has been reached. (APIのリトライ上限に達しました) Request URI:'.$_SERVER['REQUEST_URI'].', API Endpoint:'.$_url.', Retry Count:'.$retry_num, 500);
            } else {
                // ログを出力
                new AppMedialNotice(AppE::TOO_MANY_REQUESTS.'Retry API request. (APIのリトライを実行します) Request URI:'.$_SERVER['REQUEST_URI'].', API Endpoint:'.$_url.', Retry Count:'.$retry_num);
                // スリープ処理
                usleep(Configure::read('api.retry_sleep_sec') * 1000000);
            }
        }
        return $curls;
    }

    protected static function _curl($_url, $_requests, $_method = null, $_headers = array())
    {
        //* Args No Check
        $ch = curl_init();
        $query = http_build_query($_requests);
        //debug($_url);
        //debug($query);

        //* Option
        //** Common
        $options = array();
        $options[CURLOPT_USERAGENT] = Configure::check('api.user_agent') ? Configure::read('api.user_agent') : '';
        $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLINFO_HEADER_OUT] = true;
        $options[CURLOPT_HEADER] = true;
        $options[CURLOPT_TIMEOUT] = Configure::check('api.timeout') ? Configure::read('api.timeout') : 30;
        $options[CURLOPT_CONNECTTIMEOUT] = Configure::check('api.connect_timeout') ? Configure::read('api.connect_timeout') : 30;


        //** Headers
        if (! empty($_headers)) {
            $options[CURLOPT_HTTPHEADER] = $_headers;
        }

        //** Method
        if (! $_method || $_method === 'GET') {
            $options[CURLOPT_URL] = $_url . '?' . $query;
            $options[CURLOPT_HTTPGET] = true;
        } elseif ($_method === 'POST') {
            $options[CURLOPT_URL] = $_url;
            $options[CURLOPT_POSTFIELDS] = $query;
            $options[CURLOPT_POST] = true;
        } else {
            new AppInternalCritical(AppE::FUNC . 'Http method ['.$_method.'] not supported', 500);
        }

        //** Option Set
        curl_setopt_array($ch, $options);

        //* Transfer
        if (! $responses = curl_exec($ch)) {
            $errno = curl_errno($ch);
            $error = curl_error($ch);
            new AppMedialCritical(AppE::CONNECTION . 'Could not connect external server [' . $errno . ': ' . $error . ']', 500);
        }
        //debug($responses);

        //* Results
        $results['headers'] = curl_getinfo($ch);
        $results['request_header'] = curl_getinfo($ch, CURLINFO_HEADER_OUT);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $results['response_header'] = substr($responses, 0, $header_size);
        $results['body'] = substr($responses, $header_size);
        curl_close($ch);

        //* Api results log
        self::_writeRelayLog($_url, $query, $results);

        return $results;
    }

    protected static function _writeRelayLog($_url, $_query, $_results)
    {
        $log = [];
        $log['Log ID'] = uniqid('app_relay_', true);
        $log['Access ID'] = isset($_SERVER['UNIQUE_ID']) ? $_SERVER['UNIQUE_ID'] : '';
        $log['Own Host'] = isset($_SERVER['SEVER_NAME']) ? $_SERVER['SERVER_NAME'] : '-';
        $log['Own IPv4'] = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '-';
        $log['Client Host'] = isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : '-';
        $log['Client IPv4'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '-';
        $log['Client User Agent'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '-';
        $log['Client Referer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '-';
        $log['Forward UTC'] = time();
        $log['Forward URL'] = !empty($_url) ? $_url : '-';
        $log['Forward Header'] = !empty($_results['request_header']) ? var_export($_results['request_header'], true) : '-';
        $log['Forward Query'] = !empty($_query) ? var_export($_query, true) : '-';
        $log['Reverse Header'] = !empty($_results['response_header']) ? var_export($_results['response_header'], true) : '-';
        $log['Reverse Body'] = !empty($_results['body']) ? var_export($_results['body'], true) : '-';

        CakeLog::write(RELAY_LOG, var_export($log, true) . "\n");
    }

    protected static function _parse($_content, $_accept = null)
    {
        //* Args No Check
        //debug($_content);
        //* php
        if ($_accept === 'php') {
            if (! $results = unserialize($_content)) {
                new AppInternalCritical(AppE::FUNC . 'Could not unserialize', 500);
            }
        //* json
        } elseif ($_accept === 'json') {
            if (! $results = json_decode(trim($_content), true)) {
                new AppInternalCritical(AppE::FUNC . 'Could not decode json', 500);
            }
        //* other
        } else {
            $results = $_content;
            // Task
        }

        return $results;
    }
}
