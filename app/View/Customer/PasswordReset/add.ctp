  <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-lock"></i> パスワード再発行</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <?php echo $this->Form->create('CustomerPasswordReset', ['url' => ['controller' => 'password_reset', 'action' => 'confirm'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <div class="col-lg-12">
            <div class="form-group">
              <?php echo $this->Form->input('CustomerPasswordReset.email', ['class' => "form-control", 'placeholder'=>'メールアドレス', 'error' => false]); ?>
              <?php echo $this->Form->error('CustomerPasswordReset.email', null, ['wrap' => 'p']) ?>
              <p class="help-block">ハイフン有り無しどちらでもご入力いただけます。</p>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12"> <a class="btn btn-primary btn-lg btn-block animsition-link" href="/customer/password_reset/add">クリア</a> </span>
            <span class="col-lg-6 col-md-6 col-xs-12"> <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">確認</button> </span>
          </div>
        <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
