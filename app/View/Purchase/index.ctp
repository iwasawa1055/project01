<section id="form">
  <div class="container">
  <?php  echo $this->Flash->render();?>  
    <div>
      <h2>ログイン/購入情報入力</h2>
    </div>
    <?php echo $this->element('purchase_item', ['sales' => $sales]); ?>
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
            <div class="text-center">
              <button type="submit" class="btn commit">ログイン</button>
            </div>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
        <div class="signin">
          <h4>まだアカウントをお持ちはない方</h4>
          <p>まだアカウントをお持ちでない方、登録情報を入力して購入（1/5）</p>
          <?php echo $this->Form->create('CustomerEntry', ['url' => '/purchase/'. $sales_id . '/register/', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <?php echo $this->Form->hidden('PaymentGMOPurchase.sales_id', ['value' => $sales_id]); ?>
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
            <label>ニュースレターの配信</label>
            <?php echo $this->Form->select('CustomerEntry.newsletter', CUSTOMER_NEWSLETTER, ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerEntry.newsletter', null, ['wrap' => 'p']) ?>
          </div>
          <div class="row">
            <div class="text-center">
              <button type="submit" class="btn commit">配送先住所を入力へ（2/5）</button>
            </div>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
  </div>
</section>
