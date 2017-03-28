<?php $this->Html->css('/css/order/dsn-purchase.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/order/order_dev.css', ['block' => 'css']); ?>

  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>
    </div>
  </div>
  <div class="row">
    <form method="post" action="/order/complete" novalidate>
    <div class="col-lg-12">
      <div class="panel panel-default">
        <?php echo $this->element('Order/breadcrumb_list'); ?>

        <!-- ADRESS -->
        <section id="dsn-adress">
          <div class="dsn-wrapper">
            <div class="dsn-form">
              <label>ご注文内容</label>
              <table>
                <thead>
                <tr>
                  <td>商品名</td>
                  <td>個数</td>
                  <td>価格</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ( CakeSession::read('OrderList') as $key => $value ) {?>
                <tr>
                  <th><?php echo $value['kit_name'] ?></th>
                  <td><div class="text-right"><?php echo $value['number'] ?></div></td>
                  <td><div class="text-right"><?php echo $value['price'] ?>円</div></td>
                </tr>
                <?php } ?>
                <tr>
                  <th>合計</th>
                  <td><div class="text-right"><?php echo CakeSession::read('OrderTotalList')['number'] ?></div></td>
                  <td><div class="text-right"><?php echo number_format(CakeSession::read('OrderTotalList')['price']) ?>円</div></td>
                </tr>
                </tbody>
              </table>
            </div>
            <div class="dsn-divider"></div>
            <div class="dsn-form">
              <label>お届け先住所</label>
              <p>〒<?php echo CakeSession::read('DispAddress.postal');?></p>
              <p><?php echo CakeSession::read('DispAddress.pref');?><?php echo CakeSession::read('DispAddress.address1');?><?php echo CakeSession::read('DispAddress.address2');?> <?php echo CakeSession::read('DispAddress.address3');?></p>
              <p><?php echo CakeSession::read('DispAddress.lastname');?>　<?php echo CakeSession::read('DispAddress.firstname');?></p>
              <p class="dev-text-decoration-none"><?php echo CakeSession::read('DispAddress.tel1');?></p>
            </div>
            <div class="dsn-form">
              <label>お届け日時</label>
              <p><?php echo CakeSession::read('OrderKit.select_delivery_text') ?></p>
            </div>
            <div class="dsn-form">
              <label>お支払い方法</label>
              <?php if(CakeSession::read('OrderKit.is_credit')) {?>
                <p>クレジットカード</p>
              <?php } else { ?>
                <p>口座振替</p>
              <?php } ?>
            </div>
          </div>
        </section>
      </div>
    </div>
    <section class="dsn-nextback dev-forefront">
      <a href="/order/input" class="dsn-btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a>
      <button type="submit" class="dsn-btn-next">購入する <i class="fa fa-chevron-circle-right"></i></button>
    </section>
    </form>
  </div>
