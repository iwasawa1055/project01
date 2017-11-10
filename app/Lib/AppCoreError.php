<?php

App::uses('AppE', 'Lib');

/**
 * コアエラーハンドラー
 *
 * 例外以外のエラー処理
 * CakePHP より権限委譲
 * 最終エラー処理のため、極力ライブラリ等使用せず、自前ロジックで実装すべき
 * （エラ処理ーのエラーで本来のエラーを不透明にしないため）
 */
class AppCoreError extends AppE
{
    public static function handleError($_level, $_message, $_file, $_line, $_traces)
    {
        //* Error Type
        $error_type = 'PHP syntax error';

        //* To String
        $traces = debug_backtrace();
        $stack = '';
        foreach ($traces as $i => $trace) {
            $num = '#' . $i . ' ';
            $file = isset($trace['file']) ? $trace['file'] : '';
            $line = isset($trace['line']) ? '(' . $trace['line'] . '): ': '';
            $class = isset($trace['class']) ? $trace['class'] : '';
            $type = isset($trace['type']) ? $trace['type'] : '';
            $func = isset($trace['function']) ? $trace['function'] : '';
            $stack .= $num . $file . $line . $class . $type . $func . "\n";
        }
        $to_string = $_message . " in " . $_file . ": " . $_line . "\n" .
        "Stack Trace:" . "\n" . 
        $stack;

        //** Message
        $message = $to_string;

        //** Results
        $results = array();
        // Mail flag
        $mail_flag = false;

        switch (true) {
            case $_level === E_ERROR:
                // 処理は停止
                $error_type = 'Critical Error [PHP Fatal error]';
                $mail_flag = true;
                break;
            case $_level === E_WARNING:
                // 処理は中断されない
                $error_type = 'Warning [PHP Warning]';
                break;
            case $_level === E_PARSE:
                // 処理は停止
                $error_type = 'Critical [PHP Parse error]';
                $mail_flag = true;
                break;
            case $_level === E_NOTICE:
                // 処理は中断されない
                $error_type = 'Notice [PHP Notice]';
                break;
            case $_level === E_CORE_ERROR:
                $error_type = 'Fatal [PHP Core fatal error]';
                $mail_flag = true;
                break;
            case $_level === E_CORE_WARNING:
                $error_type = 'Warning [PHP Core warning]';
                $mail_flag = true;
                break;
            case $_level === E_COMPILE_ERROR:
                $error_type = 'Fatal [PHP Compile fatal error]';
                $mail_flag = true;
                break;
            case $_level === E_COMPILE_WARNING:
                $error_type = 'Warning [PHP Compile warning]';
                $mail_flag = true;
                break;
            case $_level === E_USER_ERROR:
                $error_type = 'PHP User error';
                break;
            case $_level === E_USER_WARNING:
                // 処理は中断されない
                $error_type = 'PHP User warning';
                break;
            case $_level === E_USER_NOTICE:
                // 処理は中断されない
                $error_type = 'PHP User notice';
                break;
            case $_level === E_STRICT:
                $error_type = 'PHP Strict';
                break;
            case $_level === E_RECOVERABLE_ERROR:
                $error_type = 'PHP Recoverable Critical Error';
                break;
            case $_level === E_DEPRECATED:
                $error_type = 'PHP Deprecated';
                break;
            case $_level === E_USER_DEPRECATED:
                $error_type = 'PHP User deprecated';
                break;
            case $_level >= E_ALL:
                // 未知のエラー
                $error_type = 'PHP All: unknown error';
                $mail_flag = true;
                break;
            default:
                break;
        }
        self::_display(true, $error_type, $message);
        self::_log(true, $error_type, self::_createLogStr($error_type, $message, true));
        self::_mail($mail_flag, $error_type, self::_createLogStr($error_type, $message, false));
        return true;
    }

    protected static function _createLogStr($_error_type = null, $_message = null, $_show_request = false)
    {
        $subject = $_error_type;
        // Controller,Action名取得
        $trace_list = debug_backtrace();
        $controller = '';
        $action = '';
        foreach ($trace_list as $key => $trace) {
            $class = isset($trace['class']) ? $trace['class'] : '';
            $function = isset($trace['function']) ? $trace['function'] : '' ;
            if ($class === 'Dispatcher' and $function === 'dispatch') {
                $cakeRequest = isset($trace['args'][0]) ? $trace['args'][0] : null ;
                if (is_object($cakeRequest) and get_class($cakeRequest) === 'CakeRequest') {
                    $controller = $cakeRequest->controller;
                    $action = $cakeRequest->action;
                    break;
                }
            }
        }

        // ログデータ生成
        $log = [];
        $log['Log ID'] = uniqid(ERROR_LOG . '-', true);
        $log['Access ID'] = isset($_SERVER['UNIQUE_ID']) ? $_SERVER['UNIQUE_ID'] : '';
        $log['Request URI'] = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $log['Controller'] = $controller;
        $log['Action'] = $action;
        if (class_exists('CakeSession')) {
            //各project sessionのcustomer_id
            $customer = CakeSession::read('CUSTOMER_DATA_CACHE');
            $log['Customer ID'] = $customer->token['customer_id'];
        }
        $log['Request Method'] = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
        if ($_show_request) {
            $log['Request Variable'] = $_REQUEST;
        }
        $log['Debug Message'] = $_message;
        $log['Env Variable'] = $_SERVER;

        return "\n" . $subject . "\n" . var_export($log, true) . "\n";
    }

    /**
     * エラーログ
     *
     * @access  protected
     * @param   bool $_write ログフラグ
     * @param   string $_error_type 
     * @param   string $_message 
     * @return  
     */
    protected static function _log($_write = true, $_error_type = null, $_message = null)
    {
        if (! ini_get('log_errors')) {
            return false;
        }

        // Write Flag
        if ($_write) {
            CakeLog::write(ERROR_LOG, $_message);
        }
    }

    /**
     * 画面表示 暫定
     *
     * @access  protected
     * @param   bool $_write フラグ
     * @param   string $_error_type 
     * @param   string $_message 
     * @return  
     */
    protected static function _display($_write = true, $_error_type = null, $_message = null)
    {
        if (! ini_get('log_errors') || ! Configure::read('debug')) {
            return false;
        }

        if ($_write) {
            echo '<script>alert("' . $_error_type . str_replace("\n", " ", $_message) . '")</script>';
        }
    }

    /**
     * メール送信 
     *
     * @access  protected
     * @param   bool $_mail フラグ
     * @param   string $_error_type 
     * @param   string $_message 
     * @return  
     */
    protected static function _mail($_mail = true, $_error_type = null, $_message = null)
    {
        if (! ini_get('log_errors') || ! Configure::read('app.e.mail.flag')) {
            return false;
        }
        if (! $_mail) {
            return false;
        }

        //送信設定
        $subject = '【 障害 】' . Configure::read('app.e.mail.env_name') . ' ' . Configure::read('app.e.mail.service_name') . ' システムエラー';
        $confs = Configure::read('app.e.mail');
        $senders = $confs['sender'];
        $message = Configure::read('app.e.mail.body');
        $message .= $_message; 

        $envs = array();
        $envs['HOST'] = $senders['HOST'];
        $envs['PORT'] = $senders['PORT'];
        $envs['MAIL FROM'] = $senders['MAIL FROM'];
        $envs['USER'] = $senders['USER'];
        $envs['PASS'] = $senders['PASS'];

        $headers = array();
        $headers['Subject'] = $subject;
        $headers['From'] = $envs['MAIL FROM'];

        $receivers = $confs['receiver'];
        $headers['To'] = '';
        $headers['Cc'] = '';
        $headers['Bcc'] = '';
        if ($_mail) {
            //error_level 暫定 
            $error_level = 'critical';
            if (!empty($receivers[$error_level])) {
                foreach ($receivers[$error_level] as $k => $v) {
                    foreach ($v as $receiver) {
                        if ($k === 'To') {
                            $envs['RCPT TO'] = array($receiver);
                            $headers['To'] =  $receiver;
                        } else if ($k === 'Cc') {
                            $envs['RCPT TO'] = array($receiver);
                            $headers['Cc'] = $receiver;
                        } else if ($k === 'Bcc') {
                            $envs['RCPT TO'] = array($receiver);
                            $headers['Bcc'] = $receiver;
                        }
                        if (! AppMinikuraMail::send($envs, $headers, $message)) {
                            return false;
                        }
                    }
                }
            }
        }
    }


}
