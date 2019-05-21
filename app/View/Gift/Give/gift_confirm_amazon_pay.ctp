<?php $this->Html->script('gift/give/input_amazon_pay.js?'.time(), ['block' => 'scriptMinikura']); ?>

  <?php echo $this->Form->create('PaymentAmazonGiftAmazonPay', ['url' => ['controller' => 'give', 'action' => 'complete_amazon_pay'], 'novalidate' => true]); ?>

  <div id="page-wrapper" class="lineup wrapper">
    <?php echo $this->Flash->render(); ?>

    <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ギフトを贈る</h1>

    <?php echo $this->element('Order/breadcrumb_list'); ?>

    <p class="page-caption">以下の内容でギフト購入手続きを行います。</p>

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
      <li class="caution-box">
        <p class="title">注意事項(ご確認の上、チェックしてください)</p>
        <div class="content">
          <label id="confirm_check" class="input-check">
            <input type="checkbox" class="cb-square">
            <span class="icon"></span>
            <span class="label-txt">
                aaaaaaaaaa
              </span>
          </label>
          <label id="confirm_check" class="input-check">
            <input type="checkbox" class="cb-square">
            <span class="icon"></span>
            <span class="label-txt">
                bbbbbbbbbb
              </span>
          </label>
          <label id="confirm_check" class="input-check">
            <input type="checkbox" class="cb-square">
            <span class="icon"></span>
            <span class="label-txt">
                cccccccccc
              </span>
          </label>
        </div>
      </li>
    </ul>
  </div>
  <div class="nav-fixed">
    <ul>
      <li><a class="btn-d-gray" href="/gift/give/add">戻る</a>
      </li>
      <li>
        <button id="execute" class="btn-red" type="button">ボックスを購入</button>
      </li>
    </ul>
  </div>
  <?php echo $this->Form->end(); ?>
