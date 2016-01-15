  <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-envelope"></i> メールアドレス変更</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
            <?php echo $this->Form->create('CustomerEmail', ['url' => ['controller' => 'email', 'action' => 'confirm'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="col-lg-12">
                <div class="form-group">
                  <?php echo $this->Form->input('email', ['class' => "form-control", 'placeholder'=>'新しいメールアドレス']); ?>
                </div>
                <div class="form-group">
                  <?php echo $this->Form->input('email_confirm', ['class' => "form-control", 'placeholder'=>'新しいメールアドレス（再入力）']); ?>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12"> <a class="btn btn-primary btn-lg btn-block animsition-link" href="/customer/email/edit">クリア</a> </span>
                <span class="col-lg-6 col-md-6 col-xs-12"> <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">確認</button> </span> 
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
