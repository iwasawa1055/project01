
  <?php echo $this->Form->create('PaymentGMOKitByCreditCard', ['novalidate' => true]); ?>

  <div id="page-wrapper" class="lineup wrapper">
    <?php echo $this->Flash->render(); ?>

    <h1 class="page-header"><i class="fa fa-shopping-cart"></i> サービスの申し込み</h1>

    <?php echo $this->element('Order/breadcrumb_list'); ?>

    <p class="page-caption">以下の内容でサービスの申し込み手続きが完了しました。</p>
    <div class="l-breakdown">
      <?php foreach($order_list as $order_type => $order_product_list): ?>
      <?php foreach($order_product_list as $product_cd => $order_kit_list): ?>
      <ul class="l-bd-item" id="mono">
        <li class="l-bd-header">
          <ul class="l-bd-title">
            <li class="img-bd-title">
              <picture>
                <img src="/images/order/photo-<?php echo PRODUCT_DATA_ARRAY[$product_cd]['photo_name']; ?>@1x.jpg" alt="minikura<?php echo PRODUCT_NAME[$product_cd]; ?>">
              </picture>
            </li>
            <li class="txt-bd-title"><?php echo PRODUCT_NAME[$product_cd]; ?>
            </li>
          </ul>
        </li>
        <?php foreach($order_kit_list as $kit_cd => $kit_data): ?>
        <li>
          <ul class="list-bd">
            <li class="body">
              <dl class="content">
                <dt class="items">プラン名</dt>
                <dd class="value"><?php echo $kit_data['kit_name']; ?></dd>
              </dl>
            </li>
            <li class="body">
              <dl class="content">
                <dt class="items">個数</dt>
                <dd class="value"><?php echo $kit_data['number']; ?></dd>
              </dl>
            </li>
            <li class="body">
              <dl class="content">
                <dt class="items">サービス申し込み料</dt>
                <dd class="value">
                  <?php if($order_type === 'cleaning') :?>
                  <?php echo number_format($order_total_data['price']); ?>
                  <?php else:?>
                  0円
                  <?php endif; ?>
                </dd>
              </dl>
            </li>
            <?php if($order_type !== 'cleaning') :?>
            <li class="body">
              <dl class="content">
                <dt class="items">月額保管料</dt>
                <dd class="value">
                  <?php echo number_format(PRODUCT_DATA_ARRAY[$product_cd]['monthly_price'] * $kit_data['number']); ?>円
                </dd>
              </dl>
            </li>
            <?php endif ?>
          </ul>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endforeach; ?>
      <?php endforeach; ?>
    </div>
    <ul class="l-subtotal" id="subtotal">
      <li>
        <ul class="list-bd">
          <li class="body">
            <dl class="content">
              <dt class="items">初月合計金額</dt>
              <dd class="value"><span class="txt-value"><?php echo number_format($order_total_data['price']); ?></span>円</dd>
            </dl>
          </li>
        </ul>
      </li>
    </ul>
    <ul class="input-info">
      <li>
        <label class="headline">配送住所</label>
        <ul class="li-address">
          <li>〒<?php echo h($PaymentGMOKitByCreditCard['postal']); ?></li>
          <li><?php echo h($PaymentGMOKitByCreditCard['address']); ?></li>
          <li><?php echo h($PaymentGMOKitByCreditCard['name']); ?></li>
          <li><?php echo h($PaymentGMOKitByCreditCard['tel1']); ?></li>
        </ul>
      </li>
      <?php if (!(array_key_exists('hanger', $order_list) && count($order_list) == 1)) :?>
      <li>
        <label class="headline">お届け日時</label>
        <ul class="li-address">
          <li><?php echo h($PaymentGMOKitByCreditCard['select_delivery_text']); ?></li>
        </ul>
      </li>
      <?php endif; ?>
      <li class="border_gray"></li>
      <li>
        <label class="headline">決済</label>
        <ul class="li-credit">
          <li>ご登録のクレジットカード</li>
          <li><?php echo h($card_data['card_no']); ?></li>
          <li><?php echo h($card_data['holder_name']); ?></li>
        </ul>
      </li>
    </ul>
  </div>
  <div class="nav-fixed">
    <ul>
      <li><a class="btn-red" href="/">トップへ</a>
      </li>
    </ul>
  </div>
  <?php echo $this->Form->end(); ?>
  <input type='hidden' id='order_id' value='<?php echo $order_id; ?>'>
  <input type='hidden' id='order_list_criteo_json' value='<?php echo $order_list_criteo_json; ?>'>
  <input type='hidden' id='order_list_a8_json' value='<?php echo $order_list_a8_json; ?>'>
  <input type='hidden' id='order_total_price' value='<?php echo $order_total_data['price']; ?>'>

  <!-- A8 コンバージョン設定 -->
  <span id="a8sales"></span>
  <script src="//statics.a8.net/a8sales/a8sales.js"></script>
  <script>
  var a8_item_json = document.getElementById('order_list_a8_json').value;
  var a8_params = {
    "pid": '<?php echo Configure::read("app.a8.pid"); ?>',
    "order_number": document.getElementById('order_id').value,
    "currency": "JPY",
    "items": JSON.parse(a8_item_json),
    "total_price": document.getElementById('order_total_price').value
  };
  a8sales(a8_params);
  </script>

  <script type="text/javascript">
      var products_list = JSON.parse(document.getElementById('order_list_criteo_json').value);
      var dataLayer = dataLayer || [];
      dataLayer.push({
          'PageType': 'Transactionpage',
          'HashedEmail': document.getElementById('hashed_email').value,
          'ProductTransactionProducts': products_list,
          'TransactionID': document.getElementById('order_id').value'
      });
  </script>
