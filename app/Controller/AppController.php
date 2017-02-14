<?php
App::uses('Controller', 'Controller');
class AppController extends Controller
{
    // ジャンクション関連
    // ジャンクション終了時の戻りURL用セッションキー
    const JUNCTION_URL_KEY = 'app.data.junction';

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function afterFilter()
    {
        parent::afterFilter();
    }

    public function beforeRender()
    {
        parent::beforeRender();
    }

    /**
     * ジャンクション開始ポイントでコールする
     * 
     * 内部的には、
     * ジャンクション戻り処理に必要なパラメータの保存を行う
     * @return type
     */
    protected function _startJunction()
    {
        // ジャンクション情報が来てれば、セッションに保存
        // ジャンクションフロー完了後のリダイレクト情報に使用する
        $junction_controller = filter_input(INPUT_GET, 'c');
        $junction_action = filter_input(INPUT_GET, 'a');
        $junction_params_str = filter_input(INPUT_GET, 'p');

        if ($junction_controller && $junction_action) {
            $junction_params = array();
            parse_str($junction_params_str, $junction_params);
            $junction = [
                'controller' => $junction_controller,
                'action' => $junction_action,
                'params' => $junction_params,
            ];
            CakeLog::write(DEBUG_LOG, '_startJunction ' . print_r($junction, true));
            CakeSession::write(self::JUNCTION_URL_KEY, $junction);
        }
        else {
            CakeSession::delete(self::JUNCTION_URL_KEY);
        }
        
        return;
    }

    /**
     * ジャンクション終了ポイントでコールする
     * 
     * 内部的にはパラメータがセッションにあれば、リダイレクトかける
     * @return type
     */
    protected function _endJunction()
    {
        // ジャンクション情報設定されてるならリダイレクト
        $junction = CakeSession::read(self::JUNCTION_URL_KEY);
        CakeSession::delete(self::JUNCTION_URL_KEY);
        CakeLog::write(DEBUG_LOG, '_endJunction ' . print_r($junction, true));
        if (!empty($junction)) {
            $this->redirect(['controller' => $junction['controller'],
                            'action' => $junction['action'],
                            '?' => $junction['params']
                    ]);
        }

        return;
    }
}
