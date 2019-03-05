
  <?php echo $this->Form->create('PaymentGMOKitByCreditCard', ['novalidate' => true]); ?>

    <div id="page-wrapper" class="lineup wrapper">
      <?php echo $this->Flash->render(); ?>

      <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>

      <?php echo $this->element('Order/breadcrumb_list'); ?>

      <p class="page-caption">以下の内容でボックス購入手続きが完了しました。</p>

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
            <li>〒<?php echo h($PaymentGMOKitByCreditCard['postal']); ?></li>
            <li><?php echo h($PaymentGMOKitByCreditCard['address']); ?></li>
            <li><?php echo h($PaymentGMOKitByCreditCard['name']); ?></li>
            <li><?php echo h($PaymentGMOKitByCreditCard['tel1']); ?></li>
          </ul>
        </li>
        <?php if(!empty($PaymentGMOKitByCreditCard['datetime_cd'])) :?>
        <li>
          <label class="headline">お届け日時</label>
          <ul class="li-address">
            <li><?php echo h($PaymentGMOKitByCreditCard['select_delivery_text']); ?></li>
          </ul>
        </li>
        <?php endif; ?>
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
