<?php
/**
 * Import by CakePHP
 * Config by CakePHP
 * Logger by CakePHP
 * Mailer by AppMinikuraMail
 */

App::uses('AppMinikuraMail', 'Lib');

/**
 * AppE
 */
class AppE extends Exception
{
    //* Init
    public $error_msg = null;
    public $http_code = null;
    public $vendor_prefix = null;
    public $error_node = null;
    public $error_level = null;
    public $error_type = null;

    public $log_form = null;
    public $display_form = null;
    public $mail_form = null;
    public $ref_code = null;
    public $service_name = null;
    public $log_sent = null;
    public $mail_sent = null;

    //const PASS = 0;
    //const INFO = 100;
    //const NOTICE = 200;
    //const WARNING = 300;
    //const DEFECT = 400;
    //const ERROR = 500;
    //const CRITICAL = 600;
    //const FATAL = 700;

    const DISPLAY = 100;
    const LOG = 200;
    const MAIL = 300;
    const PATCH = 400;
    const ABORT = 500;
    const ALERT = 600;
    const SHUT = 700;

    public $patch_form = 'Partial Error';
    public $config_prefix = 'app.e.';

    //* 4xx
    const BAD_REQUEST = 'Bad Request - ';
    const UNAUTHORIZED = 'Unauthorized - ';
    const PAYMENT_REQUIRED = 'Payment Required - ';
    const FORBIDDEN = 'Forbidden - ';
    const NOT_FOUND = 'Not Found - ';
    const METHOD_NOT_ALLOWED = 'Method Not Allowed - ';
    const NOT_ACCETABLE = 'Not Accetable - ';
    const REQUEST_TIMEOUT = 'Request Timeout - ';
    const CONFLICT = 'Conflict - ';
    const REQUEST_ENTITY_TOO_LARGE = 'Request Entity Too Large - ';
    const TOO_MANY_REQUESTS = 'Too Many Requests - ';

    //* 5xx
    const INTERNAL_SERVER_ERROR = 'Internal Server Error - ';
    const EXTERNAL_SERVER_ERROR = 'External Server Error - ';
    const MEDIAL_SERVER_ERROR = 'Medial Server Error - ';
    const NOT_IMPLEMENTED = 'Not Implemented - ';
    const BAD_GATEWAY = 'Bad Gateway: ';
    const SERVICE_UNAVAILABLE = 'Service Unavailable - ';
    const EXTERNAL_SERVER_UNAVAILABLE = 'External Server Unavailable - ';
    const GATEWAY_TIMEOUT = 'Gateway Timeout - ';
    const EXTERNAL_SERVER_TIMEOUT = 'External Server Timeout - ';
    const HTTP_VERSION_NOT_SUPPORTED = 'HTTP Version Not Supported - ';

    //* Terminal
    const PARAMETER_INVALID = 'Parameter Invalid - ';
    const PARAMETER_UNKNOWN = 'Parameter Unknown - ';
    const REQUEST_INVALID = 'Request Invalid - ';
    const RESPONSE_INVALID = 'Response Invalid - ';
    const RECORD_UNKNOWN = 'Record Unknown - ';
    const RECORD_ALREADY = 'Record Already - ';

    //* Internal
    const ARGUMENT = 'Argument Failed - ';
    const CONFIG = 'Config Failed - ';
    const FILESYSTEM = 'Filesystem Failed - ';
    const FUNC = 'Function Failed - ';
    const OBJECTS = 'Object Invalid - ';
    const PARAMETER = 'Parameter Invalid - ';
    const SESSION = 'Session Failed - ';

    //* Medial
    const ASTERIA = 'Asteria Failed - ';
    const CONNECTION = 'Connection Failed - ';
    const CONTENTS = 'Contents Failed - ' ;
    const DATABASE = 'Database Failed - ';
    const DISC = 'Disc Failed -' ;
    const HTTPD = 'HTTPD Failed - ' ;
    const KVS = 'KVS Failed -' ;
    const LB = 'LB Failed -' ;
    const NETWORK = 'Network Failed -' ;
    const MEMCACHE = 'Memo Cahce Failed - ' ;
    const MEMORY = 'Memory Failed - ' ;
    const SMTP = 'Smtp Failed - ' ;
    const STORAGE = 'Storage Failed - ' ;
    const STREAM = 'Stream Failed - ' ;

    /**
     * 共通エラーハンドラ
     *
     * @access	public
     * @param	string $_msg エラーメッセージ
     * @param	int $_code エラーコード
     * @return	void
     */
    public function __construct($_error_msg = '', $_http_code = null, $_error_prev = null)
    {
        $this->service_name = Configure::read('app.name.0');

        //* Error Message Init
        $this->initErrorMessage($_error_msg);

        //* Http Code Init
        $this->initHttpCode($_http_code);

        //* previous error object 
        $this->error_prev = $_error_prev;

        parent::__construct($this->error_msg, $this->http_code, $this->error_prev);

        //* Error Node Init
        $this->initErrorNodeLevel();

        // Handler
        $this->handle();
    }

    /**
     * エラーメッセージ初期化
     *
     * @access	protected
     * @param	mixed $_error_msg
     * @return	void
     */
    protected function initErrorMessage($_error_msg)
    {
        $this->error_msg = is_string($_error_msg) ? $_error_msg : strval($_error_msg);
    }

    /**
     * HTTP Response Code 初期化
     *
     * @access	protected
     * @param	viod
     * @return	viod
     */
    protected function initHttpCode($_http_code)
    {
        $this->http_code = $_http_code ? $_http_code : 0;
    }

    /**
     * エラーノード初期化
     *
     * @access	protected
     * @param	viod
     * @return	viod
     */
    protected function initErrorNodeLevel()
    {
        if (preg_match('/^([A-Z][a-z\d]+)([A-Z\d_][a-z]+)([A-Z\d_][a-z]+)$/', get_class($this), $matches)) {
            $c = count($matches);
            if ($c === 3) {
                $this->vendor_prefix = '';
                $this->error_node = $matches[1];
                $this->error_level = $matches[2];
            } elseif ($c === 4) {
                $this->vendor_prefix = $matches[1];
                $this->error_node = $matches[2];
                $this->error_level = $matches[3];
            } else {
                throw new Exception('The Error trigger class name (' . get_class($this) . ') is invalid.');
            }
        }
    }

    /**
     * エラーハンドリング
     * $this->handlersに入れる値の順番を考慮する。
     * ABORT以上は処理中断or閉鎖処理となるので、ABORT以下の処理をしたい場合はABORTより前に記述
     *
     * @access	protected
     * @param	void
     * @return	void
     */
    protected function handle()
    {
        if (empty($this->handlers)) {
            throw new Exception('The handlers is empty.', 500);
        }

        if (! is_array($this->handlers)) {
            $this->handlers = array($this->handlers);
        }

        // $hasAbort = false;
        // $hasShut = false;


        switch (true) {
            case ! empty($this->handlers):
                if (in_array(self::DISPLAY, $this->handlers, true)) {
                    // 転送処理の妨げになる一時除外
                    // $this->display();
                }
                if (in_array(self::LOG, $this->handlers, true)) {
                    $this->log();
                }
                if (in_array(self::MAIL, $this->handlers, true)) {
                    $this->mail();
                }
                if (in_array(self::PATCH, $this->handlers, true)) {
                    $this->patch();
                }
                if (in_array(self::ALERT, $this->handlers, true) || in_array(self::ABORT, $this->handlers, true)) {
                    $this->abort();
                }
                break;
            // Others
            default:
                $this->abort();
        }
    }

    /**
     * エラーログ
     *
     * @access	static
     * @param	bool $write_ ログフラグ
     * @return	string ログフォーマット
     */
    public function log($_write = true)
    {
        //* Request Body
        $request_body = '';
        if (isset($_REQUEST) && $_SERVER['REQUEST_METHOD'] !== 'GET') {
            foreach ($_REQUEST as $k => $v) {
                $request_body .= $k . '=' . serialize($v) . '&' . "\n";
            }
        }

        //* Response Header
        $response_headers = headers_list();

        // Format
        $log  = "\n";
        if ($this->error_prev) {
            $log .= $this->error_prev->__toString();
        } else {
            $log .= $this->__toString();
        }
        $log .= '[Access ID]' . "\n" . (! empty($_SERVER['UNIQUE_ID']) ? $_SERVER['UNIQUE_ID'] : uniqid('@', true)) . "\n";
        $log .= '[Env Variable]' . "\n" . (isset($_SERVER) ? var_export($_SERVER, true) : '-') . "\n";
        $log .= '[Request Body]' . "\n" . ($request_body ? $request_body : '-') . "\n";
        $log .= '[Response Header]' . "\n" . ($response_headers ? var_export($response_headers, true) : '-') . "\n";
        $log .= "\n";
        // Write Flag
        if ($_write) {
            CakeLog::write(ERROR_LOG, $log);
        }

        //return $log;
        $this->log_form = $log;
        return $this->log_form;
    }

    /**
     * エラーメール
     *
     * @access	static
     * @param   object
     * @return	string メールフォーマット
     */
    public function mail()
    {
        if (! Configure::read($this->config_prefix . 'mail.flag')) {
            return false;
        }

        if ($this->log_form === null) {
            $this->log_form = $this->log(false);
        }
        $body = Configure::read($this->config_prefix . 'mail.body.default'); 
        if ($this->error_level === 'Warning') {
            $body = Configure::read($this->config_prefix . 'mail.body.warning'); 
        }
        $body .= str_replace("\n", "\r\n", $this->log_form);

        $confs = Configure::read($this->config_prefix . 'mail');
        $senders = $confs['sender'];

        $envs = array();
        $envs['HOST'] = $senders['HOST'];
        $envs['PORT'] = $senders['PORT'];
        $envs['MAIL FROM'] = $senders['MAIL FROM'];
        $envs['MAIL FROM DISP'] = $senders['MAIL FROM DISP'];
        $envs['USER'] = $senders['USER'];
        $envs['PASS'] = $senders['PASS'];

        $headers = array();
        $headers['Subject'] = Configure::read($this->config_prefix . 'mail.subject.default'); ;
        if ($this->error_level === 'Warning') {
            $headers['Subject'] = Configure::read($this->config_prefix . 'mail.subject.warning'); ;
        }
        $headers['From'] = $envs['MAIL FROM'];
        $headers['From'] = sprintf(
            '%s<%s>'
            , $envs['MAIL FROM DISP']
            , $envs['MAIL FROM']
        );

        $receivers = $confs['receiver'];
        $headers['To'] = '';
        $headers['Cc'] = '';
        $headers['Bcc'] = '';
        $error_level = strtolower($this->error_level);

        foreach ($receivers[$error_level] as $k => $a) {
            foreach ($a as $receiver) {
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
                if (! AppMinikuraMail::send($envs, $headers, $body)) {
                    return false;
                }
            }
        }
        $this->mail_form = $body;
        return $this->mail_form;
    }

    /**
     * エラーデバッグ画面出力
     *
     * @access	protected
     * @param	void
     * @return	string 画面出力フォーマット
     */
    protected function display()
    {
        if (! ini_get('display_errors') || ! Configure::read('debug')) {
            return false;
        }

        // Stack traceを除いたエラーメッセージのみ表示
        $str = $this->vendor_prefix . ' ' . $this->error_node . ' ' . $this->error_level . ' Error: ';
        $str .= $this->__toString();
        $str = preg_replace('/Stack trace:.+$/s', '', $str);

        // Display
        echo '<div style="display: inline-block; padding: 5px 15px; border-radius: 5px; background-color: #FF9090; color: #FFFFFF;"><b>';
        echo  h($str);
        echo '</b></div>';
    }

    /**
     * エラー中断処理
     *
     * @access	protected
     * @param	void
     * @return	void
     */
    protected function abort()
    {
        throw $this;
    }
}
