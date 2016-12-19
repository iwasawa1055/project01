<?php

class TitleHelper extends AppHelper {

    private $titles = [
        // customer
        'address' => [
            'index' => 'お届け先変更',
        ],
        'credit_card' => [
            'index' => 'クレジットカード変更',
            'customer_add' => 'クレジットカード登録',
        ],
        'email' => [
            'index' => 'メールアドレス変更',
        ],
        'info' => [
            'index' => 'お客さま情報変更',
            'customer_add' => 'お客さま情報登録',
        ],
        'password' => [
            'index' => 'パスワード変更',
        ],
        'password_reset' => [
            'index' => 'パスワード再発行',
        ],
        'register' => [
            'index' => 'ユーザー登録',
        ],

        'announcement' => [
            'index' => 'メッセージ一覧',
            'detail' => 'メッセージ',
        ],
        'news' => [
            'index' => 'ニュース一覧',
            'detail' => 'ニュース',
        ],
        'box' => [
            'index' => 'ボックス一覧',
        ],
        'contact_us' => [
            'index' => 'お問い合わせ',
        ],
        'contract' => [
            'index' => '会員情報',
        ],
        'InboundBox' => [
            'index' => 'ボックス預け入れ',
        ],
        'inquiry' => [
            'index' => 'お問い合わせ',
        ],
        'item' => [
            'index' => 'アイテム一覧',
        ],
        'login' => [
            'index' => 'ログイン',
        ],
        'my_page' => [
            'index' => 'マイページ',
        ],
        'order' => [
            'index' => 'ボックス購入',
        ],
        'outbound' => [
            'index' => '取り出し',
        ],
        'result' => [
            'index' => '検索結果',
        ],
        'point' => [
            'index' => 'ポイント',
        ],
        'travel' => [
            'index' => 'minikura teburaTRAVEL',
        ],
    ];

    public function p() {
        $controller = $this->request->params['controller'];
        $action = $this->request->params['action'];
        $str = 'minikura';
        if (array_key_exists($controller, $this->titles) && array_key_exists('index', $this->titles[$controller])) {
            $str = $this->titles[$controller]['index'];
            if (array_key_exists($action, $this->titles[$controller])) {

                $str = $this->titles[$controller][$action];
            }
        }
        echo h($str) . '｜モノをあずけて、写真でみれる minikura';
    }
}
