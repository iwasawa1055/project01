<?php

App::uses('MinikuraController', 'Controller');
App::uses('Folder', 'Utility');

class GvidoController extends MinikuraController
{
    protected $checkLogined = false;
    public $layout = 'gvido';
    public $alliance_cd = 'gvido';
    public $modelName = null;
    const MODEL_NAME_CUSTOMER_REGIST_INFO = 'CustomerRegistInfo';
    const MODEL_NAME_EMAIL = 'Email';
    const MODEL_NAME_PAYMENT_GMO_CREDIT_CARD_CHECK = 'PaymentGMOCreditCardCheck';
    const MODEL_NAME_PAYMENT_GMO_CREDIT_CARD = 'PaymentGMOCreditCard';
    const MODEL_NAME_PAYMENT_GMO_KIT_BY_CREDIT_CARD = 'PaymentGMOKitByCreditCard';

    const GVIDO_COUPON_FILE = TMP . 'gvido_coupon/coupon.txt';

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME_CUSTOMER_REGIST_INFO);
    }


    public function customer_delete_session()
    {
        $this->autoRender = false;
        CakeSession::delete(self::MODEL_NAME_CUSTOMER_REGIST_INFO);
        echo "セッションを削除しました。";
    }

    public function customer_add()
    {
        //クーポンコードの取得
        if (isset($_GET['coupon_code'])) {
            $coupon_code = $_GET['coupon_code'];
        } elseif (CakeSession::read('app.data.coupon_code')) {
            $coupon_code = CakeSession::read('app.data.coupon_code');
        } else {
            $coupon_code = '';
        }

        CakeSession::Write('app.data.coupon_code', $coupon_code);

        //クーポンコードの確認
        if ($this->_confirmCouponCode($coupon_code) == false) {
            return $this->redirect(Configure::read('site.static_content_url') . '/gvido?coupon_error=1#coupon');
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // 初期表示
        if ($this->request->is('get')) {
            // セッションから入力値を取得
            $data = CakeSession::read(self::MODEL_NAME_CUSTOMER_REGIST_INFO);
            $this->request->data = [self::MODEL_NAME_CUSTOMER_REGIST_INFO => $data];

            if (empty($this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO])) {
                $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO] = [
                    'alliance_cd' => $this->alliance_cd,
                ];
            }
        // 確認へ遷移する場合
        } elseif ($this->request->is('post')) {
            // 生年月日を結合
            $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['birth'] = implode('-', [
                $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['birth_year'],
                // ゼロ埋め
                sprintf('%02d', $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['birth_month']),
                // ゼロ埋め
                sprintf('%02d', $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['birth_day']),
            ]);

            // 電話番号を半角変換
            $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['tel1'] = self::_wrapConvertKana($this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['tel1']);

            // Validationにdatetime_cdを追加
            $this->CustomerRegistInfo->validate['datetime_cd'] = [
                'notBlank' => [
                    'rule' => 'notBlank',
                    'required' => true,
                    'message' => ['notBlank', 'kit_datetime']
                ],
                'isDatetimeDelivery' => [
                    'rule' => 'isDatetimeDelivery',
                    'message' => ['format', 'kit_datetime']
                ],
            ];

            // モデルに入力値をセット
            $this->CustomerRegistInfo->set($this->request->data);

            // セッションに入力値を保存
            CakeSession::write(self::MODEL_NAME_CUSTOMER_REGIST_INFO, $this->CustomerRegistInfo->toArray());

            // バリデーションエラー確認
            if ($this->CustomerRegistInfo->validates() === false) {
                return $this->render('customer_add');
            }

            // 既存ユーザチェック
            $this->loadModel(self::MODEL_NAME_EMAIL);
            $result = $this->Email->getEmail(array('email' => $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['email']));
            if ($result->status === "0") {
                $this->CustomerRegistInfo->validationErrors['email'][0] = '登録済みのメールアドレスです';
                return $this->render('customer_add');
            }

            // エラーがない場合は確認画面にリダイレクト
            return $this->redirect('/customer/gvido/confirm');
        }
    }

    public function customer_confirm()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
            'Gvido/customer_add',
            'Gvido/customer_confirm',
            ], true) === false ) {
            $this->redirect(['controller' => 'gvido', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // セッションから入力値を取得しviewに渡す
        $this->set(self::MODEL_NAME_CUSTOMER_REGIST_INFO, CakeSession::read(self::MODEL_NAME_CUSTOMER_REGIST_INFO));
    }

    public function customer_card()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
            'Gvido/customer_confirm',
            'Gvido/customer_card',
            ], true) === false ) {
            $this->redirect(['controller' => 'gvido', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        if ($this->request->is('post')) {
            $params = [
                'security_cd' => filter_input(INPUT_POST, 'securitycode'),
                'gmo_token' => filter_input(INPUT_POST, 'gmo_token'),
            ];

            if (empty($params['gmo_token'])) {
                $this->Flash->validation('クレジットカード情報を再度入力してください。', ['key' => 'gmo_token']);
            }

            $this->loadModel(self::MODEL_NAME_PAYMENT_GMO_CREDIT_CARD_CHECK);
            $res = $this->PaymentGMOCreditCardCheck->getCreditCardCheck(['gmo_token' => filter_input(INPUT_POST, 'gmo_token_for_check')]);

            if (!empty($res->error_message)) {
                $this->set('error_message', $res->error_message . 'エラーコード:' . $res->message);
                return $this->render('customer_card');
            }

            // セッションに入力値を保存
            CakeSession::write(self::MODEL_NAME_PAYMENT_GMO_CREDIT_CARD, $params);

            // エラーがない場合は確認画面にリダイレクト
            return $this->redirect('/customer/gvido/complete');
        }
    }

    public function customer_complete()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
            'Gvido/customer_card',
            ], true) === false ) {
            $this->redirect(['controller' => 'gvido', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $data = CakeSession::read(self::MODEL_NAME_CUSTOMER_REGIST_INFO);
        $this->CustomerRegistInfo->set($data);

        // 本登録
        $this->CustomerRegistInfo->regist();

        // ログイン
        $this->loadModel('CustomerLogin');
        $this->CustomerLogin->data['CustomerLogin']['email'] = $this->CustomerRegistInfo->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['email'];
        $this->CustomerLogin->data['CustomerLogin']['password'] = $this->CustomerRegistInfo->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['password'];
        $res = $this->CustomerLogin->login();

        // カスタマー情報を取得しセッションに保存
        $this->Customer->setTokenAndSave($res->results[0]);
        $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
        $this->Customer->getInfo();

        // カード登録
        $this->loadModel(self::MODEL_NAME_PAYMENT_GMO_CREDIT_CARD);
        $this->PaymentGMOCreditCard->set(CakeSession::read(self::MODEL_NAME_PAYMENT_GMO_CREDIT_CARD));
        $this->PaymentGMOCreditCard->apiPost($this->PaymentGMOCreditCard->toArray());

        // 購入
        $this->loadModel(self::MODEL_NAME_PAYMENT_GMO_KIT_BY_CREDIT_CARD);

        // アドレスの処理(API側でパースした際に12文字目がスペースのみで終わらないように変換をかける)
        $address = $this->CustomerRegistInfo->data['CustomerRegistInfo']['pref'] .
          $this->CustomerRegistInfo->data['CustomerRegistInfo']['address1'] .
          $this->CustomerRegistInfo->data['CustomerRegistInfo']['address2'] .
          $this->CustomerRegistInfo->data['CustomerRegistInfo']['address3'];

        if (mb_strlen($address) === 12  && mb_substr($address, 11, 1) === '　') { //合計12文字で最後が全角スペースで終わる場合
            $address = mb_substr($address, 0, 11); //12文字目の全角スペースを除いた先頭から11文字を返す
        }

        $gmo_kit_card['security_cd']   = $this->PaymentGMOCreditCard->data['PaymentGMOCreditCard']['security_cd'];
        $gmo_kit_card['card_seq']      = 0;
        $gmo_kit_card['address_id']    = '';
        $gmo_kit_card['datetime_cd']   = $this->CustomerRegistInfo->data['CustomerRegistInfo']['datetime_cd'];
        $gmo_kit_card['name']          = $this->CustomerRegistInfo->data['CustomerRegistInfo']['lastname'] . '　' . $this->CustomerRegistInfo->data['CustomerRegistInfo']['firstname'];
        $gmo_kit_card['tel1']          = $this->CustomerRegistInfo->data['CustomerRegistInfo']['tel1'];
        $gmo_kit_card['postal']        = $this->CustomerRegistInfo->data['CustomerRegistInfo']['postal'];
        $gmo_kit_card['address']       = $address;
        $gmo_kit_card['kit'] = KIT_CD_LIBRARY_GVIDO . ':3';
        $this->PaymentGMOKitByCreditCard->set($gmo_kit_card);
        $this->PaymentGMOKitByCreditCard->apiPost($this->PaymentGMOKitByCreditCard->toArray());

        CakeSession::delete(self::MODEL_NAME_CUSTOMER_REGIST_INFO);
        CakeSession::delete('app.data.coupon_code');
    }

    public function customer_as_get_address_datetime()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        // 画面描画しない
        $this->autoRender = false;

        $postal = filter_input(INPUT_POST, 'postal');

        $result = $this->_getAddressDatetime($postal);

        $status = !empty($result);

        // コードを表示用文字列に変換
        App::uses('AppHelper', 'View/Helper');
        $appHelper = new AppHelper(new View());

        $results = [];
        $i = 0;
        foreach ($result->results as $datetime) {
            $datetime_cd = $datetime['datetime_cd'];
            $results[$i]["datetime_cd"] = $datetime_cd;
            $results[$i]["text"] = $appHelper->convDatetimeCode($datetime_cd);
            $i++;
        }

        return json_encode(compact('status', 'results'));
    }

    private function _getAddressDatetime($postal)
    {
        // ハイフンチェック
        if (mb_strlen($postal) > 7) {
            // ハイフン部分を削除 macの場合全角ハイフンの文字コードが異なるため単純な全角半角変換ができない
            $postal = mb_substr($postal,0, 3) . mb_substr($postal, 4, 4);
        }
        $postal = mb_convert_kana($postal, 'nhk', "utf-8");

        // 配送日時情報取得
        $this->loadModel('KitDeliveryDatetime');

        $result = $this->KitDeliveryDatetime->getKitDeliveryDatetime(array('postal' => $postal));

        return $result;
    }

    private function _confirmCouponCode($coupon_code)
    {
        /* ファイルポインタをオープン */
        $file = fopen(self::GVIDO_COUPON_FILE, "r");

        /* ファイルを1行ずつ出力 */
        if ($file) {
            while ($line = fgets($file)) {
                if (rtrim($line) == $coupon_code) {
                    return true;
                };
            }
        }
        /* ファイルポインタをクローズ */
        fclose($file);

        return false;
    }
}
