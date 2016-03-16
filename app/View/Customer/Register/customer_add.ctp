  <?php if (!empty($code))  : ?>
    <div class="row">
      <div class="col-lg-12" align="center">
        <img class="alliance" src="https://minikura.com/contents/image/with/<?php echo $code ?>.gif" />
      </div>
    </div>
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
          <?php echo $this->Form->create('CustomerEntry', ['url' => ['controller' => 'register', 'action' => 'add', '?' => ['code' => $code]], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
            <div class="col-lg-12 col-md-12 none-title">
              <div class="form-group">
                <?php echo $this->Form->input('CustomerEntry.email', ['class' => "form-control", 'placeholder'=>'メールアドレス', 'error' => false]); ?>
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
              <a class="btn btn-info btn-xs btn-block" href="/use_agreement/" target="_blank">minikura 利用規約</a>
              <div class="checkbox">
                <label>
                  <input name="remember" type="checkbox" value="Remember Me" class="agree-before-submit">
                  minikura 利用規約に同意する </label>
              </div>
              <button type="submit" class="btn btn-danger btn-lg btn-block">利用規約に同意して会員登録</button>
              <a class="btn btn-primary btn-xs btn-block" href="/login">ログインはこちら</a>
            </div>
          <?php echo $this->Form->end(); ?>
          </div>
        </div>
      </div>
    </div>
