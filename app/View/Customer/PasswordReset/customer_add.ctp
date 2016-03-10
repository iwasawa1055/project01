    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-lock"></i> パスワード再発行</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
            <?php echo $this->Form->create('CustomerPasswordReset', ['url' => ['controller' => 'password_reset', 'action' => 'confirm'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="col-lg-12">
                <p class="form-control-static col-lg-12">ご登録されているメールアドレスを入力してください。</p>
                <div class="form-group col-lg-12">
                  <p class="form-control-static"><?php echo $this->Form->data['CustomerPasswordReset']['email'] ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerPasswordReset.new_password', ['class' => "form-control", 'placeholder'=>'新しいパスワード', 'type' => 'password', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerPasswordReset.new_password', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerPasswordReset.new_password_confirm', ['class' => "form-control", 'placeholder'=>'新しいパスワード（再入力）', 'type' => 'password', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerPasswordReset.new_password_confirm', null, ['wrap' => 'p']) ?>
                </div>
                <span class="col-lg-12 col-md-12 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">確認</button>
                  <a class="btn btn-primary btn-xs btn-block" href="/login">ログインはこちら</a>
                </span>
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
