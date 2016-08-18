<section id="form">
  <div class="container">
  <?php  echo $this->Flash->render();?>  
    <div>
      <h2>ログイン/配送先情報入力</h2>
    </div>
    <div class="row">
      <div class="info">
        <div class="photo">
          <img src="<?php echo Configure::read('site.mypage.url');?>/market/images/item.jpg" alt="" />
        </div>
        <div class="caption">
          <h3>極美品 NIKE FLYKNIT RACER us9 jp27cm フライニット 007極美品 NIKE FLYKNIT RACER us9 jp27cm フライニット 007</h3>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form">
        <div class="login">
          <h4>ログインして購入（1/4）</h4>
          <?php echo $this->Form->create('CustomerLogin', ['url' => '/purchase/'. $sales_id, 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerLogin.email', ['class' => "form-control", 'placeholder'=>'メールアドレス', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerLogin.email', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->password('CustomerLogin.password', ['class' => "form-control", 'placeholder'=>'パスワード', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerLogin.password', null, ['wrap' => 'p']) ?>
          </div>
          <div class="row">
            <div class="text-center btn-commit">
              <button type="submit" class="btn">ログイン</button>
            </div>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
        <div class="signin">
          <h4>配送先情報を入力して購入</h4>
          <p>メールアドレスとパスワードを設定（1/5）</p>
          <div class="form-group">
            <input class="form-control" placeholder="メールアドレス" name="email" type="email" autofocus>
          </div>
          <div class="form-group">
            <input class="form-control" placeholder="パスワード" name="password" type="password" value="">
          </div>
          <div class="form-group">
            <input class="form-control" placeholder="パスワード（確認用）" name="password" type="password" value="">
          </div>
          <div class="btn-orrection">
          <a class="btn btn-info btn-xs" href="https://minikura.com/use_agreement/" target="_blank">minikura利用規約</a>
          </div>
          <div class="checkbox">
            <label>
              <input name="remember" type="checkbox" value="Remember Me">
              minikura利用規約に同意する </label>
          </div>
          <div class="row">
            <div class="text-center btn-commit">
              <a href="/purchase/register/address" class="animsition-link btn">配送先住所を入力へ（2/5）</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
