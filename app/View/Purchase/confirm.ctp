<?php $this->Html->script('minikura/purchase_register', ['block' => 'scriptMinikura']); ?>
<section id="form">
  <div class="container">
    <div>
      <h2>入力情報を確認（3/4）</h2>
    </div>
    <?php echo $this->element('purchase_item', ['sales' => $sales]); ?>
    <div class="row">
      <div class="form">
        <div class="a-confirm">
          <h3>メールアドレス</h3>
          <div class="form-group">
            <p><?php echo h($current_email); ?></p>
          </div>
          <h3>お届け先情報</h3>
          <div class="form-group">
            <label>郵便番号</label>
            <p><?php echo h($address['postal']); ?></p>
          </div>
          <div class="form-group">
            <label>住所</label>
            <p><?php echo h("{$address['pref']}{$address['address1']}"); ?></p>
          </div>
          <div class="form-group">
            <label>番地</label>
            <p><?php echo h($address['address2']); ?></p>
          </div>
          <div class="form-group">
            <label>建物名</label>
            <p><?php echo h($address['address3']); ?></p>
          </div>
          <div class="form-group">
            <label>電話番号</label>
            <p><?php echo h($address['tel1']); ?></p>
          </div>
          <div class="form-group">
            <label>お届け希望日時</label>
            <p><?php echo h($datetime); ?></p>
          </div>
          <div class="form-group">
            <label>お名前</label>
            <p><?php echo h("{$address['lastname_kana']}　{$address['firstname_kana']}"); ?></p>
            <p><?php echo h("{$address['lastname']}　{$address['firstname']}"); ?></p>
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
        </div>
      </div>
      <div class="row">
      <?php echo $this->Form->create('PaymentGMOPurchase', ['url' => '/purchase/'. $sales_id . '/complete', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
        <div class="text-center">
          <div class="btn-orrection">
            <a class="btn btn-info btn-xs" href="https://minikura.com/use_agreement/" target="_blank">minikura利用規約</a>
          </div>
          <div class="checkbox">
            <label>
              <input name="remember" type="checkbox" class="agree-before-submit">
              minikura利用規約に同意する </label>
          </div>
        </div>
        <div class="text-center">
          <button type="submit" class="btn commit">この内容で購入する（5/5）</button>
          <a href="/purchase/<?php echo $sales_id ?>/input?back=true" class="btn return">配送先情報入力に戻る</a>
        </div>
      <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
</section>
