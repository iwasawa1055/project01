
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-exchange"></i> minikuraTRADE</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <?php echo $this->Form->create('CustomerAccount', ['url' => "/customer/account/confirm/", 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="col-lg-12">
                <h2>金融機関情報追加</h2>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerAccount.bank_name', ['class' => 'form-control', 'placeholder' => '金融機関名', 'error' => false]);?>
                  <?php echo $this->Form->error('CustomerAccount.bank_name', null, ['wrap' => 'p']);?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerAccount.bank_branch_name', ['class' => 'form-control', 'placeholder' => '支店名', 'error' => false]);?>
                  <?php echo $this->Form->error('CustomerAccount.bank_branch_name', null, ['wrap' => 'p']);?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->select('CustomerAccount.bank_account_type', BANK_ACCOUNT_TYPE,  ['class' => 'form-control',  'empty' => '選択してください', 'error' => false]);?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerAccount.bank_account_number', ['class' => 'form-control', 'placeholder' => '口座番号', 'error' => false]);?>
                  <?php echo $this->Form->error('CustomerAccount.bank_account_number', null, ['wrap' => 'p']);?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerAccount.bank_account_holder', ['class' => 'form-control', 'placeholder' => '口座名義', 'error' => false]);?>
                  <?php echo $this->Form->error('CustomerAccount.bank_account_holder', null, ['wrap' => 'p']);?>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <button type="submit" class="btn btn-danger btn-lg btn-block animsition-link" >確認する</button>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <a class="btn btn-primary btn-lg btn-block animsition-link" href="/customer/account/index">戻る</a>
                </span>
              </div>
              <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
