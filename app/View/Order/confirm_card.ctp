  <?php $this->Html->script('order/input', ['block' => 'scriptMinikura']); ?>

  <?php echo $this->Form->create('PaymentGMOKitByCreditCard', ['url' => ['controller' => 'order', 'action' => 'complete_card'], 'novalidate' => true]); ?>

  <div id="page-wrapper" class="lineup wrapper">
      <?php echo $this->Flash->render(); ?>

      <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>

      <?php echo $this->element('Order/breadcrumb_list'); ?>

      <p class="page-caption">以下の内容でボックス購入手続きを行います。</p>

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
              <tr>
                <th>合計</th>
                <td><?php echo $order_total_data[$order_type]['number'] ?></td>
                <td><?php echo $order_total_data[$order_type]['price'] ?></td>
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
        <?php if($order_type === 'other') :?>
        <li>
          <label class="headline">お届け日時</label>
          <ul class="li-address">
            <li><?php echo h($PaymentGMOKitByCreditCard['select_delivery_text']); ?></li>
          </ul>
        </li>
        <?php endif; ?>
        <li class="border_gray"></li>
        <?php $order_cnt++;?>
        <?php endforeach; ?>
        <li>
          <label class="headline">決済</label>
          <ul class="li-credit">
            <li>ご登録のクレジットカード</li>
            <li><?php echo h($card_data['card_no']); ?></li>
            <li><?php echo h($card_data['holder_name']); ?></li>
          </ul>
        </li>
        <li class="caution-box">
          <p class="title">注意事項</p>
          <div class="content">
            <label id="confirm_check" class="input-check">
              <input type="checkbox" class="cb-square">
              <span class="icon"></span>
              <span class="label-txt">
                お申込み完了後、日時を含む内容の変更はお受けすることができません。<br>
              </span>
            </label>
            <?php if($order_type === 'hanger') :?>
            <label id="hanger_check" class="input-check">
              <input type="checkbox" class="cb-square">
              <span class="icon"></span>
              <span class="label-txt">
                Closetはminikuraの他の商品と異なり、お届け日時が選べません。<br>
                ネコポスでの配送となりお客さまのポストに直接投函・配達します。<br>
              </span>
            </label>
            <?php endif; ?>
          </div>
        </li>
      </ul>
    </div>
    <div class="nav-fixed">
      <ul>
        <li><a class="btn-d-gray animsition-link" href="/order/add">戻る</a>
        </li>
        <li>
          <button id="execute" class="btn-red" type="button">ボックスを購入</button>
        </li>
      </ul>
    </div>
  <?php echo $this->Form->end(); ?>
