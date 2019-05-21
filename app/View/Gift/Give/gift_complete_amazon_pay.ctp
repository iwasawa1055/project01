
<?php echo $this->Form->create('PaymentAmazonGiftAmazonPay', ['novalidate' => true]); ?>

<div id="page-wrapper" class="lineup wrapper">
  <?php echo $this->Flash->render(); ?>

  <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ギフトを贈る</h1>

  <?php echo $this->element('Order/breadcrumb_list'); ?>

  <p class="page-caption">以下の内容でギフト購入手続きが完了しました。</p>

  <ul class="input-info">
    <li>
      <label class="headline">ご注文内容</label>
      <table class="usage-details">
        <thead>
        <tr>
          <th>商品名</th>
          <td>個数</td>
          <td>価格</td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($order_list as $key => $order_data): ?>
        <tr>
          <th><?php echo $order_data['kit_name'] ?></th>
          <td><?php echo $order_data['number'] ?></td>
          <td><?php echo $order_data['price'] ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </li>
    <li>
      <label class="headline">決済</label>
      <ul class="li-credit">
        <li>Amazon Pay</li>
      </ul>
    </li>
    <li>
      <label class="headline">メール詳細</label>
      <div class="l-present-mail" id="address1">
        <ul class="input-info">
          <li>
            <label class="headline">宛先</label>
            <p class="text-confirm"><?php echo h($PaymentAmazonGiftAmazonPay['email']); ?></p>
          </li>
          <li>
            <label class="headline">贈り主</label>
            <p class="text-confirm"><?php echo h($PaymentAmazonGiftAmazonPay['sender_name']); ?></p>
          </li>
          <li>
            <label class="headline">メッセージ</label>
            <p class="text-confirm"><?php echo h($PaymentAmazonGiftAmazonPay['email_message']); ?></p>
          </li>
          <li>
            <label class="headline">数量</label>
            <p class="text-confirm"><?php echo h($PaymentAmazonGiftAmazonPay['gift_cleaning_num']); ?></p>
          </li>
        </ul>
      </div>
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
