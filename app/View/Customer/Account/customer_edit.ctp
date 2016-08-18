
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
              <div class="col-lg-12">
                <h2>現在の金融機関情報変更</h2>
                <div class="col-lg-12">
                  <p class="form-control-static">xxxxxxxx銀行　xxxxxxxx支店　普通　0000000000</p>
                  <p><?php  debug($customer_account['bank_name']); ?></p>
                </div>
              </div>
              <div class="col-lg-12">
                <h2>変更する金融機関情報変更</h2>

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







                <div class="form-group col-lg-12">
                  <input class="form-control" value="" placeholder="xxxxxxxxxxxxxxxxxxxx銀行">
                </div>
                <div class="form-group col-lg-12">
                  <input class="form-control" value="" placeholder="xxxxxxxxxxxxxxxxxxxx支店">
                </div>
                <div class="form-group col-lg-12">
                  <select class="form-control">
                    <option>普通</option>
                    <option>当座</option>
                  </select>
                </div>
                <div class="form-group col-lg-12">
                  <input class="form-control" value="" placeholder="000000000000">
                </div>
                <div class="form-group col-lg-12">
                  <input class="form-control" value="" placeholder="市川　倫之介">
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <a class="btn btn-danger btn-lg btn-block animsition-link" href="/sale/account_check.html">確認する</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <a class="btn btn-primary btn-lg btn-block animsition-link" href="/sale/account_index.html">戻る</a>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
