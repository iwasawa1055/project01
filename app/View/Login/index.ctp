    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-sign-in"></i> ログイン</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="col-lg-12 col-md-12 none-title">
            <?php echo $this->Form->create('CustomerLogin', ['url' => '/login', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="form-group">
                <?php echo $this->Form->input('CustomerLogin.email', ['class' => "form-control", 'placeholder'=>'メールアドレス', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerLogin.email', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->password('CustomerLogin.password', ['class' => "form-control", 'placeholder'=>'パスワード', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerLogin.password', null, ['wrap' => 'p']) ?>
              </div>
              <button type="submit" class="btn btn-danger btn-lg btn-block">ログイン</button>
            <?php echo $this->Form->end(); ?>
              <a class="btn btn-info btn-xs btn-block" href="/customer/password_reset">パスワードを忘れた方はこちら</a>
            <?php if (!empty($code) && $code ===  Configure::read('api.sneakers.alliance_cd')) : ?>
              <a class="btn btn-primary btn-xs btn-block" href="/customer/register/add_sneakers?key=<?php echo $key;?>">ユーザー登録はこちら</a>
            <?php else : ?>
              <a class="btn btn-primary btn-xs btn-block" href="/customer/register/add">ユーザー登録はこちら</a>
            <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
