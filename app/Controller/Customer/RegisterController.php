<?php

App::uses('MinikuraController', 'Controller');

class RegisterController extends MinikuraController
{
    const MODEL_NAME = 'CustomerEntry';
    const MODEL_NAME_REGIST = 'CustomerRegistInfo';
    const MODEL_NAME_CORP_REGIST = 'CorporateRegistInfo';

    // ログイン不要なページ
    protected $checkLogined = false;

    /**
     * アクセス拒否.
     */
    protected function isAccessDeny()
    {
        if ($this->Customer->isLogined()) {
            return true;
        }

        return false;
    }

    /**
     *
     */
    public function customer_add()
    {
        // 紹介コード
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        if ($this->request->is('post')) {
            $this->loadModel(self::MODEL_NAME);
            $this->CustomerEntry->set($this->request->data);

            // 紹介コード
            if (!empty($code)) {
                $this->CustomerEntry->data[self::MODEL_NAME]['alliance_cd'] = $code;
            }

            if ($this->CustomerEntry->validates()) {
                // 仮登録
                $res = $this->CustomerEntry->entry();
                if (!empty($res->error_message)) {
                    $this->request->data[self::MODEL_NAME]['password'] = '';
                    $this->request->data[self::MODEL_NAME]['password_confirm'] = '';
                    $this->Flash->set($res->error_message);
                    return $this->render('customer_add');
                }

                // ログイン
                $this->loadModel('CustomerLogin');
                $this->CustomerLogin->data['CustomerLogin']['email'] = $this->request->data[self::MODEL_NAME]['email'];
                $this->CustomerLogin->data['CustomerLogin']['password'] = $this->request->data[self::MODEL_NAME]['password'];

                $res = $this->CustomerLogin->login();
                if (!empty($res->error_message)) {
                    $this->Flash->set($res->error_message);
                    return $this->render('customer_add');
                }

                // カスタマー情報を取得しセッションに保存
                $this->Customer->setTokenAndSave($res->results[0]);
                $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
                $this->Customer->getInfo();

                if (empty($this->CustomerEntry->data[self::MODEL_NAME]['alliance_cd'])) {
                    return $this->redirect('/');
                } else {
                    // 紹介コードがある場合はキット購入へ遷移
                    return $this->redirect(['controller' => 'order', 'action' => 'add', 'customer' => false]);
                }

            } else {
                $this->request->data[self::MODEL_NAME]['password'] = '';
                $this->request->data[self::MODEL_NAME]['password_confirm'] = '';
                return $this->render('customer_add');
            }

        }
    }

    /**
     *
     */
    public function customer_add_info()
    {
        // 紹介コード
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        if ($this->request->is('post')) {
            $this->loadModel(self::MODEL_NAME_REGIST);
            $data = $this->request->data[self::MODEL_NAME_REGIST];

            $birth = [];
            $birth[0] = $data['birth_year'];
            $birth[1] = $data['birth_month'];
            $birth[2] = $data['birth_day'];
            $data['birth'] = implode('-', $birth);

            $this->CustomerRegistInfo->set($data);

            // 紹介コード
            if (!empty($code)) {
                $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd'] = $code;
            }

            if (empty($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd'])) {
                unset($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd']);
            }

            if ($this->CustomerRegistInfo->validates()) {
                // 部屋番号
                $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['address3'] = $data['address3'] . $data['room'];

                // 本登録
                $res = $this->CustomerRegistInfo->regist();
                if (!empty($res->error_message)) {
                    $this->request->data[self::MODEL_NAME_REGIST]['password'] = '';
                    $this->request->data[self::MODEL_NAME_REGIST]['password_confirm'] = '';
                    $this->Flash->set($res->error_message);
                    return $this->render('customer_add_info');
                }
                
                // ログイン
                $this->loadModel('CustomerLogin');
                $this->CustomerLogin->data['CustomerLogin']['email'] = $this->request->data[self::MODEL_NAME_REGIST]['email'];
                $this->CustomerLogin->data['CustomerLogin']['password'] = $this->request->data[self::MODEL_NAME_REGIST]['password'];
                
                $res = $this->CustomerLogin->login();
                if (!empty($res->error_message)) {
                    $this->Flash->set($res->error_message);
                    return $this->render('customer_add_info');
                }
                
                // カスタマー情報を取得しセッションに保存
                $this->Customer->setTokenAndSave($res->results[0]);
                $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
                $this->Customer->getInfo();
                
                if (isset($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd'])) {
                    // 紹介コードがある場合はキット購入へ遷移
                    return $this->redirect(['controller' => 'order', 'action' => 'add', 'customer' => false]);
                } else {
                    return $this->redirect('/');
                }
            } else {
                $this->request->data[self::MODEL_NAME_REGIST]['password'] = '';
                $this->request->data[self::MODEL_NAME_REGIST]['password_confirm'] = '';
                return $this->render('customer_add_info');
            }
        }
    }

    /**
     * 法人カスタマー登録（いきなり本登録）
     */
    public function corporate_add_info()
    {
        // 紹介コード
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = [self::MODEL_NAME_CORP_REGIST => CakeSession::read(self::MODEL_NAME_CORP_REGIST)];
            $this->request->data[self::MODEL_NAME_CORP_REGIST]['password'] = '';
            $this->request->data[self::MODEL_NAME_CORP_REGIST]['password_confirm'] = '';
        }
        CakeSession::delete(self::MODEL_NAME_CORP_REGIST);
    }

    /**
     * 法人カスタマー登録確認（いきなり本登録）
     */
    public function corporate_confirm_info()
    {
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        $this->loadModel(self::MODEL_NAME_CORP_REGIST);
        $this->CorporateRegistInfo->set($this->request->data);

        // 紹介コード
        if (!empty($code)) {
            $this->CorporateRegistInfo->data[self::MODEL_NAME_CORP_REGIST]['alliance_cd'] = $code;
        }

        if ($this->CorporateRegistInfo->validates()) {
            CakeSession::write(self::MODEL_NAME_CORP_REGIST, $this->CorporateRegistInfo->toArray());
        } else {
            return $this->render('corporate_add_info');
        }
    }

    /**
     * 法人カスタマー登録完了（いきなり本登録）
     */
    public function corporate_complete_info()
    {
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        $data = CakeSession::read(self::MODEL_NAME_CORP_REGIST);
        CakeSession::delete(self::MODEL_NAME_CORP_REGIST);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'corporate_add_info', '?' => ['code' => $code]]);
        }

        $this->loadModel(self::MODEL_NAME_CORP_REGIST);
        $this->CorporateRegistInfo->set($data);
        if ($this->CorporateRegistInfo->validates()) {
            // 本登録
            $res = $this->CorporateRegistInfo->regist();
            if (!empty($res->error_message)) {
                $this->CorporateRegistInfo->data[self::MODEL_NAME_CORP_REGIST]['password'] = '';
                $this->CorporateRegistInfo->data[self::MODEL_NAME_CORP_REGIST]['password_confirm'] = '';
                $this->Flash->set($res->error_message);
                return $this->render('corporate_add_info');
            }

            // ログイン
            $this->loadModel('CustomerLogin');
            $this->CustomerLogin->data['CustomerLogin']['email'] = $this->CorporateRegistInfo->data[self::MODEL_NAME_CORP_REGIST]['email'];
            $this->CustomerLogin->data['CustomerLogin']['password'] = $this->CorporateRegistInfo->data[self::MODEL_NAME_CORP_REGIST]['password'];

            $res = $this->CustomerLogin->login();
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->render('corporate_add_info');
            }

            // カスタマー情報を取得しセッションに保存
            $this->Customer->setTokenAndSave($res->results[0]);
            $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
            $this->Customer->getInfo();

            if (empty($this->CorporateRegistInfo->data[self::MODEL_NAME_CORP_REGIST]['alliance_cd'])) {
                return $this->redirect('/');
            } else {
                // 紹介コードがある場合はキット購入へ遷移
                return $this->redirect(['controller' => 'order', 'action' => 'add', 'customer' => false]);
            }

        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'corporate_add_info', '?' => ['code' => $code]]);
        }
    }
}
