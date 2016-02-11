    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-keyboard-o"></i> ユーザー登録</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
          <?php echo $this->Form->create('CustomerEntry', ['url' => ['controller' => 'register', 'action' => 'add'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
            <div class="col-lg-12 col-md-12 none-title">
              <div class="form-group">
                <?php echo $this->Form->input('CustomerEntry.email', ['class' => "form-control", 'placeholder'=>'メールアドレス', 'autofocus'=>'autofocus', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerEntry.email', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CustomerEntry.password', ['class' => "form-control", 'placeholder'=>'パスワード', 'type' => 'password', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerEntry.password', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CustomerEntry.password_confirm', ['class' => "form-control", 'placeholder'=>'パスワード（確認用）', 'type' => 'password', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerEntry.password_confirm', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <label>お知らせメール</label>
                <?php echo $this->Form->select('CustomerEntry.newsletter', CUSTOMER_NEWSLETTER, ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
              </div>
              <a class="btn btn-info btn-xs btn-block animsition-link" href="#" target="_blank">魂ガレージ利用規約</a>
              <div class="checkbox">
                <label>
                  <input name="remember" type="checkbox" value="Remember Me">
                  魂ガレージ利用規約に同意する </label>
              </div>
              <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">利用規約に同意して会員登録</button>
              <a class="btn btn-primary btn-xs btn-block animsition-link" href="/login">ログインはこちら</a>
            </div>
          <?php echo $this->Form->end(); ?>
          </div>
        </div>
      </div>
    </div>
