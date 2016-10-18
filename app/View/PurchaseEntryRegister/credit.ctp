<section id="form">
  <div class="container narrow">
    <div>
      <h2>クレジットカード情報を入力（3/5）</h2>
    </div>
    <?php echo $this->element('purchase_item', ['sales' => $sales]); ?>
    <div class="row">
      <div class="form">
        <div class="address">
          <h4>クレジットカード情報を入力してください。</h4>
          <?php echo $this->Form->create('PaymentGMOSecurityCard', ['url' => ['controller' => 'PurchaseEntryRegister', 'action' => 'credit'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <div class="form-group ">
            <?php echo $this->Form->input('PaymentGMOSecurityCard.card_no', ['class' => "form-control", 'maxlength' => 19, 'placeholder'=>'クレジットカード番号', 'error' => false]); ?>
            <?php echo $this->Form->error('PaymentGMOSecurityCard.card_no', null, ['wrap' => 'p']) ?>
            <p class="help-block">ハイフン有り無しどちらでもご入力いただけます。</p>
          </div>
          <div class="form-group ">
            <?php echo $this->Form->input('PaymentGMOSecurityCard.security_cd', ['class' => "form-control", 'maxlength' => 4, 'placeholder'=>'セキュリティコード', 'error' => false]); ?>
            <?php echo $this->Form->error('PaymentGMOSecurityCard.security_cd', null, ['wrap' => 'p']) ?>
            <p class="help-block">カード裏面に記載された３〜4桁の番号をご入力ください。</p>
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="link">※セキュリティコードとは？</a>
            <div id="collapseOne" class="panel-collapse collapse panel panel-default">
              <div class="panel-body">
                <p>セキュリティコードとは、クレジットカード番号とは異なる、3桁または4桁の番号で、第三者がお客様のクレジットカードを不正利用出来ないようにする役割があります。</p>
                <p>カードの種類によってセキュリティコードの記載位置が異なりますので、下記をご覧ください。</p>
                <h4>Visa、Mastercard等の場合</h4>
                <p>カードの裏面の署名欄に記入されている3桁の番号です。</p>
                <p>カード番号の下3桁か、その後に記載されています。</p>
                <p><img src="/trade/images/cvv2visa.gif" alt="" /></p>
                <h4>American Expressの場合</h4>
                <p>カードの表面に記入されている4桁の番号です。</p>
                <p>カード番号の下4桁か、その後に記載されています。</p>
                <p><img src="/trade/images/cvv2amex.gif" alt="" /></p>
              </div>
            </div>
          </div>
          <div class="form-group ">
            <label>有効期限</label>
            <?php echo $this->Form->select('PaymentGMOSecurityCard.expire_month', $this->Html->creditcardExpireMonth(), ['class' => 'form-control', 'empty' => null, 'error' => false]); ?>
          </div>
          <div class="form-group ">
            <?php echo $this->Form->select('PaymentGMOSecurityCard.expire_year', $this->Html->creditcardExpireYear(), ['class' => 'form-control', 'empty' => null, 'error' => false]); ?>
            <?php echo $this->Form->error('PaymentGMOSecurityCard.expire', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group ">
            <?php echo $this->Form->input('PaymentGMOSecurityCard.holder_name', ['class' => "form-control", 'placeholder'=>'クレジットカード名義', 'error' => false]); ?>
            <?php echo $this->Form->error('PaymentGMOSecurityCard.holder_name', null, ['wrap' => 'p']) ?>
            <p class="help-block">（※半角大文字英数字、半角スペース）</p>
          </div>
          <div class="row">
            <div class="text-center">
              <button type="submit" class="btn commit">入力情報を確認へ（4/5）</button>
            </div>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
  </div>
</section>
