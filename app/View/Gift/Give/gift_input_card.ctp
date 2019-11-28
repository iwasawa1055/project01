<?php $this->Html->script('gift/give/input_card.js?'.time(), ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script(Configure::read("app.gmo.token_url"), ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('libGmoCreditCardPayment', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('gmoCreditCardPayment', ['block' => 'scriptMinikura']); ?>

  <?php echo $this->Form->create('PaymentGMOPurchaseGift', ['url' => ['controller' => 'give', 'action' => 'input_card'], 'novalidate' => true]); ?>

    <div id="page-wrapper" class="l-detail-gift wrapper">
      <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ギフトを贈る</h1>
      <ul class="pagenation">
        <li class="on"><span class="number">1</span><span class="txt">ギフト<br>選択</span>
        </li>
        <li><span class="number">2</span><span class="txt">確認</span>
        </li>
        <li><span class="number">3</span><span class="txt">完了</span>
        </li>
      </ul>

      <div class="dsn-wrapper dev-wrapper"></div>

      <ul class="items">
        <li class="item">
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
      <section class="l-select-payment">
        <div class="input-card">
          <h4>クレジットカード情報の入力</h4>
          <ul class="select-card input-check-list">
            <li>
              <label class="input-check">
                <?php
                  echo $this->Form->input(
                    'PaymentGMOPurchaseGift.select-card',
                    [
                      'id'    => '',
                      'class' => 'cb-square card_check_type',
                      'label' => false,
                      'error' => false,
                      'options' => [
                        'as-card' => '<span class="icon"></span><span class="label-txt">登録済みのカードを使用する</span>' . '[' . '<label for="as-card" class="dsn-select-card">' . $card_data['card_no'] . '</label>' . ']'
                      ],
                      'type' => 'radio',
                      'div' => false,
                      'hiddenField' => false,
                      'checked' => 'checked'
                    ]
                  );
                ?>
              </label>
            </li>
            <li>
              <label class="input-check">
                <?php
                  echo $this->Form->input(
                    'PaymentGMOPurchaseGift.select-card',
                    [
                      'id'    => '',
                      'class' => 'cb-square card_check_type',
                      'label' => false,
                      'error' => false,
                      'options' => [
                        'change-card' => '<span class="icon"></span><span class="label-txt">登録したカードを変更する</span>'
                      ],
                      'type' => 'radio',
                      'div' => false,
                      'hiddenField' => false
                    ]
                  );
                ?>
              </label>
            </li>
          </ul>
        </div>
        <div id="gmo_validate_error"></div>
        <div id="gmo_credit_card_info"></div>
        <div class="dsn-form card_error">
          <?php echo $this->Flash->render('customer_kit_card_info');?>
        </div>
        <?php echo $this->Form->error('PaymentGMOPurchaseGift.card_no', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
        <div id="input-exist" class="input-card">
          <h4>登録済みのカードを使用する</h4>
          <p class="page-caption">セキュリティコードをご入力ください。</p>
          <ul class="input-info add-credit">
            <li>
              <label class="headline">セキュリティコード<span class="required">※</span></label>
              <?php echo $this->Form->input('PaymentGMOPurchaseGift.security_cd', ['id' => 'security_cd', 'class' => "cb-square", 'placeholder'=>'例：0123', 'size' => '6', 'maxlength' => '6', 'autocomplete' => "cc-csc", 'error' => false, 'label' => false, 'div' => false]); ?>
              <p class="txt-caption">カード裏面に記載された3〜4桁の番号をご入力ください。</p>
              <?php echo $this->Form->error('PaymentGMOPurchaseGift.security_cd', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            </li>
          </ul>
        </div>
        <div id="input-change" class="input-card">
          <h4>登録したカードを変更する</h4>
          <p class="page-caption">利用するカード情報をご入力ください。</p>
          <?php echo $this->element('Order/add-credit'); ?>
          <a class="btn-red dsn-btn-credit execute" href="javascript:void(0)">このカードに変更する</a>
        </div>
        <div id="input-new" class="input-card">
          <h4>カードを新規登録する</h4>
          <p class="page-caption">利用するカード情報をご入力ください。</p>
          <?php echo $this->element('Order/add-credit-new'); ?>
          <a class="btn-red dsn-btn-credit execute" href="javascript:void(0)">このカードを登録する</a>
        </div>
      </section>
      <h3 class="title-present-mail">メールで送る</h3>
      <div class="l-present-mail" id="address1">
        <ul class="input-info">
          <li>
            <label class="headline">宛先</label>
            <?php echo $this->Form->input("PaymentGMOPurchaseGift.receiver_email", ['id' => 'receiver_email', 'class' => "cb-square", 'placeholder'=>'受け取る人のメールアドレスを入力ください', 'size' => '28', 'maxlength' => '50', 'autocomplete' => "cc-csc", 'error' => false, 'label' => false, 'div' => false]); ?>
            <?php echo $this->Form->error("PaymentGMOPurchaseGift.receiver_email", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            <p class="txt-caption">半角英数記号でご入力ください。</p>
          </li>
          <li>
            <label class="headline">贈り主</label>
            <?php echo $this->Form->input("PaymentGMOPurchaseGift.sender_name", ['id' => 'name', 'class' => "cb-square", 'placeholder'=>'お客さまのお名前を入力ください', 'size' => '10', 'maxlength' => '30', 'autocomplete' => "cc-csc", 'error' => false, 'label' => false, 'div' => false]); ?>
            <?php echo $this->Form->error("PaymentGMOPurchaseGift.sender_name", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
          </li>
          <li>
            <label class="headline">メッセージ</label>
            <?php echo $this->Form->textarea("PaymentGMOPurchaseGift.email_message", ['class' => "form-control", 'placeholder'=>'メッセージを入力ください', 'rows' => 8, 'error' => false]); ?>
            <?php echo $this->Form->error("PaymentGMOPurchaseGift.email_message", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
          </li>
          <li>
            <label class="headline">数量</label>
            <div class="spinner">
              <input type="button" name="spinner_down" class="btn-spinner spinner-down">
              <?php echo $this->Form->input("PaymentGMOPurchaseGift.gift_cleaning_num", ['type' => 'text', 'default' => '0', 'class' => "input-spinner box_type_cleaning", 'error' => false, 'label' => false, 'div' => false, 'readonly' => 'readonly']); ?>
              <input type="button" name="spinner_up" class="btn-spinner spinner-up">
            </div>
            <?php echo $this->Form->error("PaymentGMOPurchaseGift.gift_cleaning_num", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
          </li>
        </ul>
      </div>
    </div>
    <div class="nav-fixed">
      <ul>
        <li>
          <button class="btn-red" type="submit">ボックスの確認</button>
        </li>
      </ul>
    </div>
  <?php echo $this->Form->end(); ?>

  <input type="hidden" value="<?php if (!empty($card_data)): ?>1<?php else: ?>0<?php endif; ?>" id="is_update">
