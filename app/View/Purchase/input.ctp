<?php $this->Html->script('minikura/purchase', ['block' => 'scriptMinikura']); ?>
<section id="form">
  <div class="container">
  <?php  echo $this->Flash->render();?>
    <div>
      <h2>配送情報を選択（2/4）</h2>
    </div>
    <div class="row">
      <div class="info">
        <div class="photo">
          <img src="/market/images/item.jpg" alt="" />
        </div>
        <div class="caption">
          <h3>極美品 NIKE FLYKNIT RACER us9 jp27cm フライニット 007極美品 NIKE FLYKNIT RACER us9 jp27cm フライニット 007</h3>
        </div>
      </div>
    </div>
    <div class="row">
      <?php echo $this->Form->create('Purchase', ['url' => '/purchase/'. $sales_id . '/confirm', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true, 'class' => 'select-add-address-form']); ?>
      <div class="form">
        <div class="a-confirm">
          <h3>メールアドレス</h3>
          <div class="form-group">
            <p><?php echo $current_email ?></p>
          </div>
          <div class="btn-orrection">
            <a href="/email/edit.html" class="animsition-link btn">メールアドレスを変更する</a>
          </div>
          <h3>お届け先情報</h3>
            <div class="form-group">
              <label>お届け先</label>
              <?php echo $this->Form->select('Purchase.address_id', $this->Order->setAddress($address), ['class' => 'form-control select-add-address', 'empty' => '選択してください', 'error' => false]); ?>
              <?php echo $this->Form->error('Purchase.address_id', null, ['wrap' => 'p']) ?>
              <?php if (!$this->Form->isFieldError('Purchase.address_id')) : ?>
              <?php echo $this->Form->error('Purchase.lastname', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Purchase.lastname_kana', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Purchase.firstname', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Purchase.firstname_kana', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Purchase.name', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Purchase.tel1', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Purchase.postal', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Purchase.pref', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Purchase.address', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Purchase.address1', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Purchase.address2', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Purchase.address3', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <label>お届け希望日時</label>
              <?php echo $this->Form->select('Purchase.datetime_cd', $this->Order->setDatetime($datetime), ['class' => 'form-control', 'empty' => null, 'error' => false]); ?>
              <?php echo $this->Form->error('Purchase.datetime_cd', null, ['wrap' => 'p']) ?>
            </div>
<!-- 
            <div class="form-group">
              <label>お届け希望時間</label>
              <select class="form-control">
              </select>
            </div>
 -->
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
            <?php echo $this->Form->input('Purchase.security_cd', ['class' => "form-control", 'placeholder'=>'セキュリティコード', 'maxlength' => 4, 'error' => false]); ?>
            <?php echo $this->Form->error('Purchase.security_cd', null, ['wrap' => 'p']) ?>
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
            <a href="/credit_card/edit.html" class="animsition-link btn">クレジットカード情報を修正する</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="text-center btn-commit">
          <button type="submit" class="btn">この内容で購入する（3/4）</button>
        </div>
      <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
</section>
