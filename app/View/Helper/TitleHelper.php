<?php

class TitleHelper extends AppHelper {

    private $titles = [
        'announcement' => [
            'index' => 'お知らせ',
            'detail' => 'お知らせ詳細'
        ]
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
        echo $str . '｜モノをあずけて、写真でみれる minikura';
    }
}
