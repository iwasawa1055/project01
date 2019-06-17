
  <?php echo $this->Form->create('PaymentAmazonKitAmazonPay', ['novalidate' => true]); ?>

    <div id="page-wrapper" class="lineup wrapper">
      <?php echo $this->Flash->render(); ?>

      <h1 class="page-header"><i class="fa fa-shopping-cart"></i> サービスの申し込み</h1>

      <?php echo $this->element('Order/breadcrumb_list'); ?>

      <p class="page-caption">以下の内容でサービスの申し込み手続きが完了しました。</p>

      <ul class="input-info">
        <?php $order_cnt = 1;?>
        <?php foreach($order_list as $order_type => $order_data): ?>
        <li>
          <label class="headline">ご注文内容<?php echo $order_cnt; ?></label>
          <table class="usage-details">
            <thead>
            <tr>
              <th>商品名</th>
              <td>個数</td>
              <td>価格</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($order_data as $key => $item): ?>
            <tr>
              <th><?php echo $item['kit_name'] ?></th>
              <td><?php echo $item['number'] ?></td>
              <td></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
            <tr>
              <th>合計</th>
              <td><?php echo $order_total_data[$order_type]['number'] ?></td>
              <td><?php echo $order_total_data[$order_type]['price'] ?></td>
            </tr>
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
        <?php if($order_type === 'other') :?>
        <li>
          <label class="headline">お届け日時</label>
          <ul class="li-address">
            <li><?php echo h($PaymentAmazonKitAmazonPay['select_delivery_text']); ?></li>
          </ul>
        </li>
        <?php endif; ?>
        <li class="border_gray"></li>
        <?php $order_cnt++;?>
        <?php endforeach; ?>
        <li>
          <label class="headline">決済</label>
          <ul class="li-credit">
            <li>Amazon Pay</li>
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
