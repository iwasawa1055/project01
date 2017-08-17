<?php

App::uses('AppHttp', 'Lib');
App::uses('AppModel', 'Model');

/**
 * API問い合わせモデルクラス。DB処理は行わない。
 * 継承先でモデル名、対応するAPIエンドポイント名と、
 * APIパラメータのバリデーションを定義する。
 *
 * 継承先のコンストラクタ利用例：
 * parent::__construct('InfoBox', '/info_box');
 *
 * コントローラでの利用例：
 * $this->loadModel(MODEL_NAME);
 * $params = ['limit' => 10];
 * $this->MODEL_NAME->apiGet($params);
 * $params = ['id' => 'hoge', 'name' => 'hoge'];
 * $this->MODEL_NAME->apiPut($params);
 *
 * 例外処理
 * 例外発生（AppE生成）時に例外処理を行うため例外制御はハンドラに任せる。
 * ・ネットワーク関連はAppHttpライブラリ内で例外発生する
 * ・APIレスポンスはApiModel::afterApiRequest()で処理
 * 		・が500系の場合、AppTerminalCriticalを生成する
 *   	・APIレスポンスが401、402の場合、ApiModel::afterApiRequest例外発生する
 *    	・APIレスポンスが401、402以外の場合、言語リソースからエラーメッセージを設定する
 *
 */
class ApiModel extends AppModel
{
    // DB処理を行わない
    public $useTable = false;

    // 設定値のキー
    const CONFIG_API_OEMKEY = 'api.minikura.oem_key';
    const CONFIG_API_ACCESSPOINT_BASE = 'api.minikura.access_point.';

    // セッション値のキー
    const SESSION_API_TOKEN = 'api.token';
    const SESSION_API_DIVISION = 'api.division';
    const SESSION_AMAZON_PAY_ACCESS_KEY = 'amazon_pay.access_key';

    protected $oem_key = null;
    protected $access_point = null;
    protected $email = null;
    protected $password = null;
    protected $end_point = null;

    // 結果ゼロ件チェック
    protected $checkZeroResultsKey = null;

    /**
     * コンストラクタ
     *
     * @param [type] $name    モデル名
     * @param [type] $end     エンドポイント
     * @param string $access_point_key APIバージョン種別キー
     */
    public function __construct($name, $end, $access_point_key = 'minikura_v3')
    {
        parent::__construct($name);
        $this->oem_key = Configure::read(self::CONFIG_API_OEMKEY);
        $this->access_point = Configure::read(self::CONFIG_API_ACCESSPOINT_BASE.$access_point_key);
        $this->end_point = $end;
    }

    /**
     * GETメソッドでAPI問い合わせ
     * @param  array $data パラメータ値
     * @return ApiResponse API問い合わせレスポンス
     */
    public function apiGet($data = [])
    {
        $r = $this->requestWithDataAndToken($data, 'GET');
        // 結果ゼロ件チェックして空配列を返す
        if (!empty($this->checkZeroResultsKey)) {
            if (count($r->results) === 1 &&
                    array_key_exists($this->checkZeroResultsKey, $r->results[0]) &&
                    empty($r->results[0][$this->checkZeroResultsKey])) {
                $r->results = [];
            }
        }
        return $r;
    }
    public function apiPost($data)
    {
        $r = $this->requestWithDataAndToken($data, 'POST');
        if ($r->isSuccess()) {
            $this->triggerDataChanged();
        }
        return $r;
    }
    public function apiPut($data)
    {
        $r = $this->requestWithDataAndToken($data, 'PUT');
        if ($r->isSuccess()) {
            $this->triggerDataChanged();
        }
        return $r;
    }
    public function apiPatch($data)
    {
        $r = $this->requestWithDataAndToken($data, 'PATCH');
        if ($r->isSuccess()) {
            $this->triggerDataChanged();
        }
        return $r;
    }
    public function apiDelete($data)
    {
        $r = $this->requestWithDataAndToken($data, 'DELETE');
        if ($r->isSuccess()) {
            $this->triggerDataChanged();
        }
        return $r;
    }

    /* protected */

    /**
     * データ更新（GET以外）の問い合わせが成功した場合
     * @return null
     */
    protected function triggerDataChanged() {
    }

    /**
     * API問い合わせ前処理
     * @param  string $url    問い合わせ先URL
     * @param  array $params パラメータ値（変更可能）
     * @param  string $method HTTPメソッド
     * @return null
     */
    protected function beforeApiRequest($url, &$params, $method)
    {
        $d = date('H:i:s', time());
        if (array_key_exists('request_method', $params)) {
            $method = $params['request_method'];
        }
        $d .= ' bigen -> ' . $method . ': ' . $url;
        //CakeLog::write(DEBUG_LOG, $d, ['bench']);
        //CakeLog::write(DEBUG_LOG, print_r($params, true));
    }

    /**
     * API問い合わせ後処理
     * 継承先で必ず親メソッドを実行
     * @param  array $params パラメータ値
     * @param  string $method HTTPメソッド
     * @param  ApiResponse $apiRes API問い合わせレスポンス（変更可能）
     * @return null
     */
    protected function afterApiRequest($params, $method, &$apiRes)
    {
        $d = date('H:i:s', time());
        $d .= " end ->";
        CakeLog::write(DEBUG_LOG, $d, ['bench']);
        CakeLog::write(DEBUG_LOG, print_r($apiRes, true));

        $code = $apiRes->http_code;
        $message = $apiRes->message;

        // 例外発生
        if (400 <= $code) {
            // エラーコードとメッセージから言語リソースを取得
            $msgKey = $code . ' ' . $message;
            $msg = __d('api', $msgKey);
            if ($msgKey === $msg) {
                // 見つからない場合はエラーコードのみで探す
                $msg = __d('api', $code . ' default');
            }
            $apiRes->error_message = $msg;
            Cakelog::write(DEBUG_LOG, "error_message: ${msgKey} -> ${msg}");
        }

        // token不正は未承認のエラーコードに変える
        if ($code === '400' && $message === 'Parameter Invalid - token') {
            $code = '401';
        }
        // 未認証と未支払は強制ログイン画面へ転送する
        if (in_array($code , ['401', '402'], true)) {
            new AppTerminalCritical($apiRes->error_message, $code);
        }
        // 基準となる例外処理
        if (500 <= $code) {
            new AppMedialCritical($apiRes->error_message, $code);
        }
    }

    /* request */

    /**
     * tokenを使うAPI問い合わせ
     *
     * @param  array $params パラメータ値
     * @param  string $method HTTPメソッド
     * @return ApiResponse    API問い合わせレスポンス
     */
    protected function requestWithDataAndToken($params = [], $method = 'GET')
    {
        // tokenをパラメータに追加
        $token = CakeSession::read(self::SESSION_API_TOKEN);
        $params['token'] = $token;
        // API問い合わせを行う
        return $this->request($this->end_point, $params, $method);
    }

    /**
     * API問い合わせ
     *
     * @param string $end_point エンドポイント
     * @param  array $params パラメータ値
     * @param  string $method HTTPメソッド
     *
     * @return ApiResponse    API問い合わせレスポンス
     */
    protected function request($end_point, $params, $method)
    {
        // GET, POST以外はrequest_methodパラメータで指定
        if ('GET' !== $method && 'POST' !== $method) {
            $params['request_method'] = strtolower($method);
            $method = 'POST';
        }
        $url = $this->access_point.$end_point;

        // 前処理
        $this->beforeApiRequest($url, $params, $method);

        // API問い合わせとレスポンス型生成
        $responses = AppHttp::request($url, $params, $method);
        $apiRes = new ApiResponse($responses);

        // 後処理
        $this->afterApiRequest($params, $method, $apiRes);

        return $apiRes;
    }
}

/**
 * API問い合わせレスポンスのクラス
 */
class ApiResponse
{
    public $status = null;
    public $message = null;
    public $results = null;
    public $http_code = null;
    public $error_message = null;

    /**
     * APIレスポンスのJSONを解析しメンバー変数を設定
     * @param array $resp APIレスポンスのJSON
     */
    public function __construct($resp)
    {
        $json = $resp['body_parsed'];
        $this->status = $json['status'];
        $this->message = $json['message'];
        if (is_array($json['results']) && array_key_exists('contents', $json['results'])) {
            // v5
            $this->results = $json['results']['contents'];
        } else {
            $this->results = $json['results'];
        }
        if (empty($this->results)) {
            $this->results = [];
        }

        $this->http_code = $resp['headers']['http_code'];
    }
    public function isSuccess()
    {
        return $this->status === '1';
    }
}
