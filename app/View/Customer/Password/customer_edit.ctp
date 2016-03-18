    <?php if (!empty($validErrors)) { $this->validationErrors['CustomerPassword'] = $validErrors; } ?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-lock"></i> パスワード変更</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
            <?php echo $this->Form->create('CustomerPassword', ['url' => ['controller' => 'password', 'action' => 'complete'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="col-lg-12">
                <p class="form-control-static col-lg-12">現在のパスワードを入力してください。</p>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerPassword.password', ['class' => "form-control", 'placeholder'=>'現在のパスワード', 'type' => 'password', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerPassword.password', null, ['wrap' => 'p']) ?>
                </div>
                <p class="form-control-static col-lg-12">新しいパスワードと確認用にパスワードを再入力してください。</p>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerPassword.new_password', ['class' => "form-control", 'maxlength' => 64, 'placeholder'=>'新しいパスワード', 'type' => 'password', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerPassword.new_password', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerPassword.new_password_confirm', ['class' => "form-control", 'maxlength' => 64, 'placeholder'=>'新しいパスワード（再入力）', 'type' => 'password', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerPassword.new_password_confirm', null, ['wrap' => 'p']) ?>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/customer/password/edit">戻る</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">パスワードを設定する</button>
                </span>
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
