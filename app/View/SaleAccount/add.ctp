
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-shopping-basket"></i> アイテム販売</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <?php echo $this->Form->create('SaleAccount', ['url' => "/sale/account/confirm/", 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="col-lg-12">
                <h2>金融機関情報追加</h2>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('SaleAccount.bank_name', ['class' => 'form-control', 'placeholder' => '金融機関名', 'error' => false]);?>
                  <?php echo $this->Form->error('SaleAccount.bank_name', null, ['wrap' => 'p']);?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('SaleAccount.branch_name', ['class' => 'form-control', 'placeholder' => '支店名', 'error' => false]);?>
                  <?php echo $this->Form->error('SaleAccount.branch_name', null, ['wrap' => 'p']);?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->select('SaleAccount.bank_type', BANK_TYPE,  ['class' => 'form-control',  'empty' => '選択してください', 'error' => false]);?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('SaleAccount.bank_number', ['class' => 'form-control', 'placeholder' => '口座番号', 'error' => false]);?>
                  <?php echo $this->Form->error('SaleAccount.bank_number', null, ['wrap' => 'p']);?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('SaleAccount.bank_holder', ['class' => 'form-control', 'placeholder' => '口座名義', 'error' => false]);?>
                  <?php echo $this->Form->error('SaleAccount.bank_holder', null, ['wrap' => 'p']);?>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <button type="submit" class="btn btn-danger btn-lg btn-block animsition-link" >確認する</button>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <a class="btn btn-primary btn-lg btn-block animsition-link" href="/sale/account/index">戻る</a>
                </span>
              </div>
              <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
