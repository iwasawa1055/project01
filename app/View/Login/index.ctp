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
                <?php echo $this->Form->input('CustomerLogin.email', ['type' => 'url', 'class' => "form-control", 'placeholder'=>'メールアドレス', 'error' => false, 'autocomplete' => 'off']); ?>
                <?php echo $this->Form->error('CustomerLogin.email', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <input type="password" name="dummy_password" disabled="disabled" style="width:2px;height:2px;position:absolute;opacity:0"/>
                <?php echo $this->Form->password('CustomerLogin.password', ['class' => "form-control", 'placeholder'=>'パスワード', 'error' => false, 'autocomplete' => 'off']); ?>
                <?php echo $this->Form->error('CustomerLogin.password', null, ['wrap' => 'p']) ?>
              </div>
              <div class="row">
                <span class="col-sm-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-md btn-block">ログイン</button>
                </span>
                <span class="col-sm-6 col-xs-12">
                  <a class="btn btn-primary btn-md btn-block" href="/customer/register/add" target="_blank">会員登録</a>
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
              <?php $amazon_pay_access_token = $this->Flash->render('amazon_pay_access_token'); ?>
              <?php echo (!is_null($amazon_pay_access_token))? '<br /><p class="error-message">' . $amazon_pay_access_token . '</p>' : ""; ?>
            </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="dsn-amazon-login">
            <h3>Facebookアカウントで会員登録された方はこちらからログインできます。</h3>
            <a href="javascript:void(0);" class="btn fb btn-facebook dev_facebook_login" style="width:200px; height:35px">Facebookでログイン</a>
            <?php echo $this->Form->create('CustomerLoginFacebook', ['url' => ['controller' => 'login', 'action' => 'login_by_facebook'], "id" => "dev_id_facebook_loginform", 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <?php echo $this->Form->hidden('CustomerLoginFacebook.facebook_user_id', ['value'=>'', 'label' => false, 'error' => false, 'div' => false]); ?>
              <?php echo $this->Form->hidden('CustomerLoginFacebook.access_token', ['value'=>'', 'label' => false, 'error' => false, 'div' => false]); ?>
            <?php echo $this->Form->end(); ?>
            <?php $facebook_access_token = $this->Flash->render('facebook_access_token'); ?>
            <?php echo (!is_null($facebook_access_token))? '<p class="error-message">' . $facebook_access_token . '</p>' : ""; ?>
          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="dsn-amazon-login">
            <h3>Googleアカウントで会員登録された方はこちらからログインできます。</h3>
            <div class="g-signin2" onclick="Login();">Google Sign In</div>
            <?php echo $this->Form->create('CustomerLoginGoogle', ['url' => ['controller' => 'login', 'action' => 'login_by_google'], "id" => "dev_id_google_loginform", 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
            <?php echo $this->Form->hidden('CustomerLoginGoogle.access_token', ['value'=>'', 'label' => false, 'error' => false, 'div' => false]); ?>
            <?php echo $this->Form->hidden('CustomerLoginGoogle.id_token', ['value'=>'', 'label' => false, 'error' => false, 'div' => false]); ?>
            <?php echo $this->Form->end(); ?>
            <?php $google_access_token = $this->Flash->render('google_access_token'); ?>
            <?php echo (!is_null($google_access_token))? '<p class="error-message">' . $google_access_token . '</p>' : ""; ?>
          </div>
        </div>
      </div>
    </div>
<script src="/js/login.js"></script>
<script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>" ></script>
<?php $this->Html->script('login_dev', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('app_dev_facebook', ['block' => 'scriptMinikura']); ?>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : "<?php echo Configure::read('app.facebook.app_id'); ?>",
            cookie     : true,
            xfbml      : true,
            version    : "<?php echo Configure::read('app.facebook.version'); ?>"
        });

        FB.AppEvents.logPageView();

    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/ja_JP/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>