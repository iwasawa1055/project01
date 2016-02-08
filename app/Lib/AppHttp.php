<?php

class AppHttp
{
    public static function request($_url, $_requests = array(), $_method = null, $_headers = array())
    {
        //* Request
        $curls = self::_curl($_url, $_requests, $_method, $_headers);
        //debug($curls);

        // json形式として分解
        $curls['body_parsed'] = self::_parse($curls['body'], 'json');
        return $curls;
    }

    protected static function _curl($_url, $_requests, $_method = null, $_headers = array())
    {
        //* Args No Check
        $yahoo_stream = curl_init();
        $query = http_build_query($_requests);
        //debug($_url);
        //debug($query);

        //* Option
        //** Common
        $options = array();
        $options[CURLOPT_USERAGENT] = Configure::check('app.user_agent') ? Configure::read('app.user_agent') : '';
        $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLINFO_HEADER_OUT] = true;
        $options[CURLOPT_HEADER] = true;

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
        } elseif ($_method === 'POST_BIN') {
            $options[CURLOPT_URL] = $_url;
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $query;
            $options[CURLOPT_BINARYTRANSFER] = true;
        } else {
            new AppInternalCritical(AppE::FUNC . 'Http method ['.$_method.'] not supported', 500);
        }

        //** Option Set
        curl_setopt_array($yahoo_stream, $options);

        //* Transfer
        if (! $responses = curl_exec($yahoo_stream)) {
            $error = curl_error($yahoo_stream);
            new AppMedialCritical(AppE::CONNECTION . 'Could not connect external server [' . $error . ']', 500);
        }
        //debug($responses);

        //* Results
        $results['headers'] = curl_getinfo($yahoo_stream);
        $results['request_header'] = curl_getinfo($yahoo_stream, CURLINFO_HEADER_OUT);
        $header_size = curl_getinfo($yahoo_stream, CURLINFO_HEADER_SIZE);
        $results['response_header'] = substr($responses, 0, $header_size);
        $results['body'] = substr($responses, $header_size);
        curl_close($yahoo_stream);

        return $results;
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
