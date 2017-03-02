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
        // 既にセッションスタートしてる事が条件
        // マイページ側セッションクローズ

        $before_session_id = session_id();
        session_write_close();

        // コンテンツ側のセッション名に変更
        $SESS_ID = '';
        if(isset($_COOKIE['WWWMINIKURACOM'])) {
            $SESS_ID = $_COOKIE['WWWMINIKURACOM'];
        }

        if( !empty($SESS_ID)) {
            // セッション再開
            session_id($SESS_ID);
            session_start();

            // 紹介コード削除処理
            if (!empty($_SESSION['ref_code'])) {
                unset($_SESSION['ref_code']);
                CakeLog::write(DEBUG_LOG, 'ref_code is unset SESS_ID ' . print_r($SESS_ID, true));
            }

            // コンテンツ側セッションクローズ
            session_write_close();
        }

        // マイページ側セッション名に変更
        session_name('MINIKURACOM');

        if (!empty($before_session_id)) {
            session_id($before_session_id);
        }

        // マイページ側セッション再開
        session_start();

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

    /**
     * 全角半角変換　Mac全角ハイフン対応版
     *
     * @access    public
     * @param     全角文字列
     * @return    半角文字列
     */
    /// 全角変換
    public static function _wrapConvertKana($str)
    {
        $phyhen = array(
            '-', '﹣', '－', '−', '⁻', '₋',
            '‐', '‑', '‒', '–', '—', '―', '﹘','ー'
        );

        return mb_convert_kana(str_replace($phyhen, '', $str), 'nhk', "utf-8");
    }
}
