<?php

App::uses('AppHttp', 'Lib');
App::uses('AppModel', 'Model');

/**
 * API問い合わせや後処理をロジックを含むモデルクラス。
 * モデルクラスを継承するがDB処理や値保持を利用しない。
 * validateを継承先で利用する想定
 * API問い合わせ関連関数をここで実装る
 *
 * 継承する場合は、コンストラクタでモデル名とAPIエンドポイントを定義する
 * 継承例:
 * parent::__construct('InfoBox', '/info_box');
 *
 * コントローラでの利用例:
 * $this->loadModel(MODEL_NAME);
 * $data = ['limit' => 10];
 * $this->MODEL_NAME->apiGet($data);
 * $data = ['id' => 'hoge', 'name' => 'hoge'];
 * $this->MODEL_NAME->apiPut($data);
 *
 */
class ApiModel extends AppModel
{
    // DB処理を行わない
    public $useTable = false;

    // 設定値のキー
    const CONFIG_API_OEMKEY = 'api.oem_key';
    const CONFIG_API_ACCESSPOINT_BASE = 'api.minikura.access_point.';

    // セッション値のキー
    const SESSION_API_TOKEN = 'api.token';
    const SESSION_API_DIVISION = 'api.division';

    protected $oem_key = null;
    protected $access_point = null;
    protected $email = null;
    protected $password = null;
    protected $end_point = null;

    /**
     * [__construct description].
     *
     * @param [type] $name             [description]
     * @param [type] $end              [description]
     * @param string $access_point_key API種別キー
     */
    public function __construct($name, $end, $access_point_key = 'minikura_v3')
    {
        parent::__construct($name);
        $this->oem_key = Configure::read($this::CONFIG_API_OEMKEY);
        $this->access_point = Configure::read($this::CONFIG_API_ACCESSPOINT_BASE.$access_point_key);
        $this->end_point = $end;
    }

    public function apiGetResults($data = [])
    {
        $apiRes = $this->apiGet($data);
        return $apiRes->results;
    }
    public function apiPostResults($data = [])
    {
        $apiRes = $this->apiPost($data);
        return $apiRes->results;
    }
    public function apiPutResults($data = [])
    {
        $apiRes = $this->apiPut($data);
        return $apiRes->results;
    }
    public function apiPatchResults($data = [])
    {
        $apiRes = $this->apiPatch($data);
        return $apiRes->results;
    }
    public function apiDeleteResults($data = [])
    {
        $apiRes = $this->apiDelete($data);
        return $apiRes->results;
    }

    public function apiGet($data = [])
    {
        return $this->requestWithDataAndToken($data, 'GET');
    }
    public function apiPost($data)
    {
        return $this->requestWithDataAndToken($data, 'POST');
    }
    public function apiPut($data)
    {
        return $this->requestWithDataAndToken($data, 'PUT');
    }
    public function apiPatch($data)
    {
        return $this->requestWithDataAndToken($data, 'PATCH');
    }
    public function apiDelete($data)
    {
        return $this->requestWithDataAndToken($data, 'DELETE');
    }

    public function beforeApiRequest($url, &$params, $method)
    {
        // TODO: ログ出力？
    }
    public function afterApiRequest($params, $method, &$apiRes)
    {
        // 基準となる例外処理
        if (!$apiRes->isSuccess()) {
            new AppMedialCritical(AppE::MEDIAL_SERVER_ERROR.$apiRes->message.', '.$apiRes->results['support'], 500);
        }
    }


    protected function requestWithDataAndToken($params = [], $method = 'GET')
    {
        // トークンを指定する
        $token = CakeSession::read('api.token');
        $params['token'] = $token;

        // API問い合わせを行う　レスポンス型クラスを生成
        $apiRes = $this->request($this->end_point, $params, $method);

        // TODO: 例外処理
        // ネットワーク例外は、AppHttp::requestで実装。
        // 問い合わせ失敗、JSON形式でない場合は例外


        return $apiRes;
    }

    /**
     * API呼び出しJSON型のレスポンスを返す
     *
     * @param [type] $end_point [description]
     * @param [type] $params    [description]
     * @param [type] $method    [description]
     * @param [type] $headers   [description]
     *
     * @return [type] [description]
     */
    protected function request($end_point, $params, $method, $headers = [])
    {
        // TODO: APIの仕様を確認
        // GET, POST以外はすべてPOSTで送る
        if ('GET' !== $method && 'POST' !== $method) {
            $params['request_method'] = strtolower($method);
            $method = 'POST';
        }
        $url = $this->access_point.$end_point;


        // 前処理
        $this->beforeApiRequest($url, $params, $method);


        $responses = AppHttp::request($url, $params, $method, $headers);
        $apiRes = new ApiResponse($responses);

        // 後処理
        $this->afterApiRequest($params, $method, $apiRes);

        return $apiRes;
    }
}

class ApiResponse
{
    public $status = null;
    public $message = null;
    public $results = null;

    public function __construct($json)
    {
        $this->status = $json['status'];
        $this->message = $json['message'];
        $this->results = $json['results'];
    }
    public function isSuccess()
    {
        return $this->status === '1';
    }
}
