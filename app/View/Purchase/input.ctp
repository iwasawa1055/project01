<?php $this->Html->script('minikura/purchase', ['block' => 'scriptMinikura']); ?>
<section id="form">
  <div class="container">
  <?php  echo $this->Flash->render();?>
    <div>
      <h2>配送情報を選択（2/4）</h2>
    </div>
    <?php echo $this->element('purchase_item', ['sales' => $sales]); ?>
    <div class="row">
      <?php echo $this->Form->create('PaymentGMOPurchase', ['url' => '/purchase/'. $sales_id . '/confirm', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true, 'class' => 'select-add-address-form']); ?>
      <div class="form">
        <div class="a-confirm">
          <h3>メールアドレス</h3>
          <div class="form-group">
            <p><?php echo $current_email ?></p>
          </div>
          <h3>お届け先情報</h3>
            <div class="form-group">
              <label>お届け先</label>
              <?php echo $this->Form->select('PaymentGMOPurchase.address_id', $this->Order->setAddress($address), ['class' => 'form-control select-add-address', 'empty' => '選択してください', 'error' => false]); ?>
              <?php echo $this->Form->error('PaymentGMOPurchase.address_id', null, ['wrap' => 'p']) ?>
              <?php if (!$this->Form->isFieldError('PaymentGMOPurchase.address_id')) : ?>
              <?php echo $this->Form->error('PaymentGMOPurchase.lastname', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('PaymentGMOPurchase.lastname_kana', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('PaymentGMOPurchase.firstname', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('PaymentGMOPurchase.firstname_kana', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('PaymentGMOPurchase.name', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('PaymentGMOPurchase.tel1', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('PaymentGMOPurchase.postal', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('PaymentGMOPurchase.pref', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('PaymentGMOPurchase.address', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('PaymentGMOPurchase.address1', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('PaymentGMOPurchase.address2', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('PaymentGMOPurchase.address3', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <label>お届け希望日時</label>
              <?php echo $this->Form->select('PaymentGMOPurchase.datetime_cd', $this->Order->setDatetime($datetime), ['class' => 'form-control', 'empty' => null, 'error' => false]); ?>
              <?php echo $this->Form->error('PaymentGMOPurchase.datetime_cd', null, ['wrap' => 'p']) ?>
            </div>
        </div>
        <div class="c-confirm">
          <h3>クレジットカード情報</h3>
          <div class="form-group">
            <label>クレジットカード番号</label>
            <p><?php echo h($default_payment['card_no']); ?></p>
          </div>
          <div class="form-group">
            <label>有効期限</label>
            <p><?php echo h($default_payment['expire_month']); ?>月/<?php echo h(2000 + $default_payment['expire_year']); ?>年</p>
          </div>
          <div class="form-group">
            <label>クレジットカード名義</label>
            <p><?php echo h($default_payment['holder_name']); ?></p>
          </div>
          <div class="form-group ">
            <?php echo $this->Form->input('PaymentGMOPurchase.security_cd', ['class' => "form-control", 'placeholder'=>'セキュリティコード', 'maxlength' => 4, 'error' => false]); ?>
            <?php echo $this->Form->error('PaymentGMOPurchase.security_cd', null, ['wrap' => 'p']) ?>
            <p class="help-block">カード裏面に記載された３〜4桁の番号をご入力ください。</p>
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="link">※セキュリティコードとは？</a>
            <div id="collapseOne" class="panel-collapse collapse panel panel-default">
              <div class="panel-body">
                <p>セキュリティコードとは、クレジットカード番号とは異なる、3桁または4桁の番号で、第三者がお客様のクレジットカードを不正利用出来ないようにする役割があります。</p>
                <p>カードの種類によってセキュリティコードの記載位置が異なりますので、下記をご覧ください。</p>
                <h4>Visa、Mastercard等の場合</h4>
                <p>カードの裏面の署名欄に記入されている3桁の番号です。</p>
                <p>カード番号の下3桁か、その後に記載されています。</p>
                <p><img src="/market/images/cvv2visa.gif" alt="" /></p>
                <h4>American Expressの場合</h4>
                <p>カードの表面に記入されている4桁の番号です。</p>
                <p>カード番号の下4桁か、その後に記載されています。</p>
                <p><img src="/market/images/cvv2amex.gif" alt="" /></p>
              </div>
            </div>
          </div>
          <div class="btn-orrection">
            <input type="submit" name="editCard" value="クレジットカード情報を修正する" class="btn page-transition-link" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="text-center">
          <button type="submit" class="btn commit">この内容で確認する（3/4）</button>
        </div>
      <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
</section>
