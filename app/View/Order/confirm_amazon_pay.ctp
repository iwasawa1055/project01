
  <?php echo $this->Form->create('PaymentAmazonKitAmazonPay', ['url' => ['controller' => 'order', 'action' => 'complete_amazon_pay'], 'novalidate' => true]); ?>

  <div id="page-wrapper" class="lineup wrapper">
    <?php echo $this->Flash->render(); ?>

    <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>

    <?php echo $this->element('Order/breadcrumb_list'); ?>

    <p class="page-caption">以下の内容でボックス購入手続きを行います。</p>

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
          <?php foreach ($order_list as $key => $item): ?>
          <tr>
            <th><?php echo $item['kit_name'] ?></th>
            <td><?php echo $item['number'] ?></td>
            <td></td>
          </tr>
          <?php endforeach; ?>
          <tr>
            <th>合計</th>
            <td><?php echo $order_total_data['number'] ?></td>
            <td><?php echo $order_total_data['price'] ?></td>
          </tr>
          </tbody>
        </table>
      </li>
      <li>
        <label class="headline">配送住所</label>
        <ul class="li-address">
          <li>〒<?php echo h($PaymentAmazonKitAmazonPay['postal']); ?></li>
          <li><?php echo h($PaymentAmazonKitAmazonPay['address']); ?></li>
          <li><?php echo h($PaymentAmazonKitAmazonPay['name']); ?></li>
          <li><?php echo h($PaymentAmazonKitAmazonPay['tel1']); ?></li>
        </ul>
      </li>
      <?php if(!empty($PaymentAmazonKitAmazonPay['datetime_cd'])) :?>
      <li>
        <label class="headline">お届け日時</label>
        <ul class="li-address">
          <li><?php echo h($PaymentAmazonKitAmazonPay['select_delivery_text']); ?></li>
        </ul>
      </li>
      <?php endif; ?>
    </ul>
    <div class="panel panel-red">
      <div class="panel-heading">
        <label>ご注意ください</label>
        <p>お申込み完了後、日時を含む内容の変更はお受けすることができません。<br>
          内容にお間違いないか再度ご確認の上、「購入する」にお進みください。</p>
      </div>
    </div>
  </div>
  <div class="nav-fixed">
    <ul>
      <li><a class="btn-d-gray" href="/order/add">戻る</a>
      </li>
      <li><button class="btn-red" type="submit">ボックスを購入</button>
      </li>
    </ul>
  </div>
  <?php echo $this->Form->end(); ?>
