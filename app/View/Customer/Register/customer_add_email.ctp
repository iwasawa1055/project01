  <section class="registry">
    <div class="container">
      <h1 class="page-header"><i class="fa fa-keyboard-o"></i> ユーザー登録</h1>
      <?php echo $this->Form->create('CustomerRegistInfo', ['url' => ['controller' => 'register', 'action' => 'customer_add_email']]); ?>
      <div class="col-lg-12 col-md-12 none-title">
        <div class="form-group col-lg-12">
          <?php echo $this->Form->input('CustomerRegistInfo.email', ['class' => "form-control", 'placeholder'=>'メールアドレス', 'error' => false]); ?>
          <?php echo $this->Form->error('CustomerRegistInfo.email', null, ['wrap' => 'p']) ?>
        </div>
        <div class="form-group col-lg-12">
          <button type="submit" class="">メールを送信する</button>
          <a class="" href="/login">TOPへ戻る</a>
        </div>
      </div>
      <?php echo $this->Form->end(); ?>
    </div>
  </section>