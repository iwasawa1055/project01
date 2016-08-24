<section id="form">
  <div class="container">
  <?php  echo $this->Flash->render();?>  
    <div>
      <h2>ログイン/配送先情報入力</h2>
    </div>
    <div class="row">
      <div class="info">
        <div class="photo">
          <img src="<?php echo $sales['item_image']['0']['image_url']; ?>" alt="" />
        </div>
        <div class="caption">
          <h3><?php echo h($sales['sales_title']); ?></h3>
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
            <div class="text-center btn-commit">
              <button type="submit" class="btn">配送先住所を入力へ（2/5）</button>
            </div>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
  </div>
</section>
