  <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-credit-card"></i> クレジットカード変更</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
            <form action="/customer/credit_card/confirm/" method="post">
              <div class="col-lg-12">
                <div class="form-group">
                  <?php echo $this->Form->input('card_no', ['class' => "form-control", 'placeholder'=>'クレジットカード番号']); ?>
                  <p class="help-block">ハイフン有り無しどちらでもご入力いただけます。</p>
                </div>
                <div class="form-group">
                  <?php echo $this->Form->input('security_cd', ['class' => "form-control", 'placeholder'=>'セキュリティコード']); ?>
                  <p class="help-block">カード裏面に記載された３〜4桁の番号をご入力ください。</p>
                </div>
                <div class="form-group">
                  <label>有効期限</label>
                  <?php echo $this->Form->select('expire_month', $select_months, ['class' => 'form-control', 'empty' => null]); ?>
                </div>
                <div class="form-group">
                  <?php echo $this->Form->select('expire_year', $select_years, ['class' => 'form-control', 'empty' => null]); ?>
                </div>
                <div class="form-group">
                  <?php echo $this->Form->input('holder_name', ['class' => "form-control", 'placeholder'=>'クレジットカード名義']); ?>
                  <p class="help-block">（※半角大文字英字 半角スペース . - ・）</p>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12"> <a class="btn btn-primary btn-lg btn-block animsition-link" href="#">クリア</a> </span>
                <span class="col-lg-6 col-md-6 col-xs-12"> <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">確認</button> </span>
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
