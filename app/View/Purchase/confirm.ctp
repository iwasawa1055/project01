<section id="form">
  <div class="container">
    <div>
      <h2>入力情報を確認（3/4）（4/5）</h2>
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
        <div class="text-center btn-commit">
          <button type="submit" class="btn">この内容で購入する（5/5）</button>
        </div>
        <!-- <div class="text-center btn-commit">
          <a class="btn" href="/purchase/9999/input?back=true">戻る</a>
        </div> -->
      <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
</section>
