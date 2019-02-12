    <div class="col-md-6 col-md-offset-3">
      <h1 class="page-header"><i class="fa fa-sign-in"></i> ログイン</h1>
    </div>
    <div id="login">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="col-lg-12 col-md-12 none-title">
            <?php echo $this->Form->create('CustomerLogin', ['url' => '/login', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="form-group">
                <?php echo $this->Form->input('CustomerLogin.email', ['type' => 'url', 'class' => "form-control", 'placeholder'=>'メールアドレス', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerLogin.email', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->password('CustomerLogin.password', ['class' => "form-control", 'placeholder'=>'パスワード', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerLogin.password', null, ['wrap' => 'p']) ?>
              </div>
              <div class="checkbox">
                <label>
                  <input name="remember" type="checkbox" value="1">
                  ログイン状態を保持する  </label>
              </div>
              <div class="row">
                <span class="col-sm-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-md btn-block">ログイン</button>
                </span>
                <span class="col-sm-6 col-xs-12">
                  <a class="btn btn-primary btn-md btn-block" href="/customer/register/add" target="_blank">はじめて購入するかたはこちら</a>
                </span>
                <span class="col-sm-6 col-xs-12">
                  <a class="btn btn-info btn-xs btn-block" href="/customer/password_reset">パスワードを忘れた方はこちら</a>
                </span>
                <!--span class="col-sm-6 col-xs-12">
                <a class="btn btn-social btn-xs btn-block btn-facebook"><i class="fa fa-facebook"></i>Facebook アカウントでログイン</a>
                </span-->
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="dsn-amazon-login">
              <h3>Amazonアカウントで会員登録された方はこちらからログインできます。</h3>
              <a>
                <div id="AmazonPayButtonLogin">
                </div>
              </a>
              <?php //amazonpay 関連エラー表示 ?>
              <?php echo $this->Flash->render('amazon_pay_access_token'); ?>
            </div>
        </div>
      </div>
    </div>
  </div>
<script src="/js/login.js"></script>
<script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>" ></script>
<?php $this->Html->script('login_dev', ['block' => 'scriptMinikura']); ?>
