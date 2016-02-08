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
              <div class="col-lg-12">
                <h2>クレジットカード変更</h2>
            <?php echo $this->Form->create('PaymentGMOSecurityCard', ['url' => ['controller' => 'credit_card', 'action' => 'confirm'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('PaymentGMOSecurityCard.card_no', ['class' => "form-control", 'maxlength' => 19, 'placeholder'=>'クレジットカード番号', 'error' => false]); ?>
                  <?php echo $this->Form->error('PaymentGMOSecurityCard.card_no', null, ['wrap' => 'p']) ?>
                  <p class="help-block">ハイフン有り無しどちらでもご入力いただけます。</p>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('PaymentGMOSecurityCard.security_cd', ['class' => "form-control", 'maxlength' => 4, 'placeholder'=>'セキュリティコード', 'error' => false]); ?>
                  <?php echo $this->Form->error('PaymentGMOSecurityCard.security_cd', null, ['wrap' => 'p']) ?>
                  <p class="help-block">カード裏面に記載された３〜4桁の番号をご入力ください。</p>
                </div>
                <div class="form-group col-lg-12">
                  <label>有効期限</label>
                  <?php echo $this->Form->select('PaymentGMOSecurityCard.expire_month', $this->Html->creditcardExpireMonth(), ['class' => 'form-control', 'empty' => null, 'error' => false]); ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->select('PaymentGMOSecurityCard.expire_year', $this->Html->creditcardExpireYear(), ['class' => 'form-control', 'empty' => null, 'error' => false]); ?>
                  <?php echo $this->Form->error('PaymentGMOSecurityCard.expire', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('PaymentGMOSecurityCard.holder_name', ['class' => "form-control", 'placeholder'=>'クレジットカード名義', 'error' => false]); ?>
                  <?php echo $this->Form->error('PaymentGMOSecurityCard.holder_name', null, ['wrap' => 'p']) ?>
                  <p class="help-block">（※半角大文字英字 半角スペース）</p>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block animsition-link" href="/customer/credit_card/edit">クリア</a> </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">確認</button>
                </span>
                <?php echo $this->Form->hidden('PaymentGMOSecurityCard.card_seq'); ?>
            <?php echo $this->Form->end(); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
