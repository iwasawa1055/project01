<?php $this->Html->script('gift/give/input_amazon_pay.js?'.time(), ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('https://maps.google.com/maps/api/js?key=' . Configure::read('app.googlemap.api.key') . '&libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/address', ['block' => 'scriptMinikura']); ?>

<?php $this->Html->css('/css/order/dsn-purchase.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/dsn-amazon-pay.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/order/input_amazon_pay_dev.css', ['block' => 'css']); ?>

  <?php echo $this->Form->create('PaymentAmazonGiftAmazonPay', ['url' => ['controller' => 'give', 'action' => 'input_amazon_pay'], 'novalidate' => true]); ?>

    <div id="page-wrapper" class="l-detail-gift wrapper">
      <?php echo $this->Flash->render(); ?>

      <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ギフトを贈る</h1>
      <ul class="pagenation">
        <li class="on"><span class="number">1</span><span class="txt">ギフト<br>選択</span>
        </li>
        <li><span class="number">2</span><span class="txt">確認</span>
        </li>
        <li><span class="number">3</span><span class="txt">完了</span>
        </li>
      </ul>

      <div class="head_validation">
        <?php echo $this->Flash->render('customer_amazon_pay_info');?>
      </div>

      <div class="dsn-wrapper dev-wrapper"></div>

      <ul class="items">
        <li id="cleaning" class="item">
          <div class="l-image-lineup">
            <picture>
              <img src="/images/order/photo-cleaning@1x.jpg" srcset="/images/order/photo-cleaning@1x.jpg 1x, /images/order/photo-cleaning@2x.jpg 2x" alt="クリーニングパック">
            </picture>
          </div>
          <div class="l-title-lineup">
            <h3 class="title-item">衣類保管5点まで無料<span>クリーニングパック</span></h3>
            <p class="text-description">6ヶ月保管+クリーニング料セット</p>
            <p class="text-price">ボックス代金<span class="price-hb">6,000</span>円</p>
          </div>
          <div class="l-action-lineup">
            <p class="text-size">W40cm×H40cm×D40cm</p>
            <p class="text-caption">そのまま宅配便で送れる40cm四方の大容量なチャック付き不織布専用バッグです。</p>
            <p class="text-note">ギフトでは5着までが無料、それ以降の衣類には別途保管料がつきます。</p>
          </div>
        </li>
      </ul>
      <ul class="input-info">
        <li>
          <label class="headline">お客様情報</label>
        </li>
        <!-- AmazonPayment wedget表示処理 -->
        <li id="dsn-amazon-pay">
          <div class="dsn-adress">
            <div id="addressBookWidgetDiv">
            </div>
          </div>
          <div class="dsn-credit">
            <div id="walletWidgetDiv">
            </div>
          </div>
        </li>
        <div class="dsn-divider"></div>
      </ul>
      <h3 class="title-present-mail">メールで送る</h3>
      <div class="l-present-mail" id="address1">
        <ul class="input-info">
          <li>
            <label class="headline">宛先</label>
            <?php echo $this->Form->input("PaymentAmazonGiftAmazonPay.receiver_email", ['id' => 'receiver_email', 'class' => "cb-square", 'placeholder'=>'受け取る人のメールアドレスを入力ください', 'size' => '28', 'maxlength' => '50', 'autocomplete' => "cc-csc", 'error' => false, 'label' => false, 'div' => false]); ?>
            <?php echo $this->Form->error("PaymentAmazonGiftAmazonPay.receiver_email", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            <p class="txt-caption">半角英数記号でご入力ください。</p>
          </li>
          <li>
            <label class="headline">贈り主</label>
            <?php echo $this->Form->input("PaymentAmazonGiftAmazonPay.sender_name", ['id' => 'name', 'class' => "cb-square", 'placeholder'=>'お客さまのお名前を入力ください', 'size' => '10', 'maxlength' => '30', 'autocomplete' => "cc-csc", 'error' => false, 'label' => false, 'div' => false]); ?>
            <?php echo $this->Form->error("PaymentAmazonGiftAmazonPay.sender_name", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
          </li>
          <li>
            <label class="headline">メッセージ</label>
            <?php echo $this->Form->textarea("PaymentAmazonGiftAmazonPay.email_message", ['class' => "form-control", 'placeholder'=>'メッセージを入力ください', 'rows' => 8, 'error' => false]); ?>
            <?php echo $this->Form->error("PaymentAmazonGiftAmazonPay.email_message", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
          </li>
          <li>
            <label class="headline">数量</label>
            <div class="spinner">
              <input type="button" name="spinner_down" class="btn-spinner spinner-down">
              <?php echo $this->Form->input("PaymentAmazonGiftAmazonPay.gift_cleaning_num", ['type' => 'text', 'default' => '0', 'class' => "input-spinner box_type_cleaning", 'error' => false, 'label' => false, 'div' => false, 'readonly' => 'readonly']); ?>
              <input type="button" name="spinner_up" class="btn-spinner spinner-up">
            </div>
            <?php echo $this->Form->error("PaymentAmazonGiftAmazonPay.gift_cleaning_num", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
          </li>
        </ul>
      </div>
    </div>
    <div class="nav-fixed">
      <ul>
        <li>
          <button class="btn-red js-btn-submit" type="button">ボックスの確認</button>
        </li>
      </ul>
    </div>
  <?php echo $this->Form->end(); ?>
