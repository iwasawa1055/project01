
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
                <h2>金融機関情報追加</h2>
                <?php echo $this->Form->create('CustomerAccount', ['url' => "/customer/account/complete/", 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                <div class="form-group col-lg-12">
                  <label>金融機関名</label>
                  <p><?php echo $this->Form->data['CustomerAccount']['bank_name']; ?>銀行</p>
                </div>
                <div class="form-group col-lg-12">
                  <label>支店名</label>
                  <p><?php echo $this->Form->data['CustomerAccount']['bank_branch_name']; ?>支店</p>
                </div>
                <div class="form-group col-lg-12">
                  <label>口座種別</label>
                  <p><?php echo BANK_ACCOUNT_TYPE[$this->Form->data['CustomerAccount']['bank_account_type']]; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>口座番号</label>
                  <p><?php echo $this->Form->data['CustomerAccount']['bank_account_number']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>口座名義</label>
                  <p><?php echo $this->Form->data['CustomerAccount']['bank_account_holder']; ?></p>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <button class="btn btn-danger btn-lg btn-block animsition-link" >追加する</button>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <a class="btn btn-primary btn-lg btn-block animsition-link" href="/customer/account/add">戻る</a>
                </span>
              </div>
              <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
