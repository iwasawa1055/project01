  <?php if (!empty($code)) : ?>
    <?php $header_params = @get_headers('https://minikura.com/contents/image/with/' . $code . '.gif'); ?>
    <?php if (strpos($header_params[0], '200') !== false) : ?>
    <div class="row">
      <div class="col-lg-12" align="center">
        <img class="alliance" src="https://minikura.com/contents/image/with/<?php echo $code ?>.gif" />
      </div>
    </div>
    <?php endif; ?>
  <?php endif; ?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-keyboard-o"></i> ユーザー登録</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
          <?php echo $this->Form->create('CustomerEntry', ['url' => ['controller' => 'register', 'action' => 'customer_confirm', '?' => ['code' => $code]], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
            <div class="col-lg-12 col-md-12 none-title">
              <div class="form-group">
                <?php echo $this->Form->input('CustomerEntry.email', ['class' => "form-control", 'placeholder'=>'メールアドレス', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerEntry.email', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CustomerEntry.password', ['class' => "form-control", 'maxlength' => 64, 'placeholder'=>'パスワード', 'type' => 'password', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerEntry.password', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CustomerEntry.password_confirm', ['class' => "form-control", 'maxlength' => 64, 'placeholder'=>'パスワード（確認用）', 'type' => 'password', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerEntry.password_confirm', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CustomerEntry.alliance_cd', ['class' => "form-control", 'maxlength' => 64, 'placeholder'=>'紹介コードをお持ちの方はこちらにご入力ください', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerEntry.alliance_cd', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <label>お知らせメール</label>
                <?php echo $this->Form->select('CustomerEntry.newsletter', CUSTOMER_NEWSLETTER, ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
              </div>
              <a class="btn btn-info btn-xs btn-block" href="<?php echo Configure::read('site.static_content_url'); ?>/use_agreement/" target="_blank">minikura 利用規約</a>
              <div class="checkbox">
                <label>
                  <input name="remember" type="checkbox" value="Remember Me" class="agree-before-submit">
                  minikura 利用規約に同意する </label>
              </div>
              <button type="submit" class="btn btn-danger btn-lg btn-block">利用規約に同意して会員登録する</button>
              <a class="btn btn-primary btn-xs btn-block" href="/login">ログインはこちら</a>
              <?php if (!empty($code)) : ?>
              <a class="btn btn-danger btn-xs btn-block animsition-link" href="/corporate/register/add_info?code=<?php echo $code ?>">法人の方はこちら</a>
              <?php else : ?>
              <a class="btn btn-danger btn-xs btn-block animsition-link" href="/corporate/register/add_info">法人の方はこちら</a>
              <?php endif; ?>
            </div>
          <?php echo $this->Form->end(); ?>
          </div>
        </div>
      </div>
    </div>
