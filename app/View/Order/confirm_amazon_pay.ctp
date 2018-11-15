<?php $this->Html->css('/css/order/dsn-purchase.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/order/order_dev.css', ['block' => 'css']); ?>

  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>
    </div>
  </div>
  <div class="row">
    <form method="post" action="/order/complete_amazon_pay" novalidate>
    <div class="col-lg-12">
      <div class="panel panel-default">
          <section id="dsn-pagenation">
              <ul>
                  <li>
                      <i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
                  </li>
                  <li class="dsn-on">
                      <i class="fa fa-check"></i><span>確認</span>
                  </li>
                  <li>
                      <i class="fa fa-truck"></i><span>完了</span>
                  </li>
              </ul>
          </section>

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
              <p><?php echo CakeSession::read('DispAddress.name');?></p>
              <p><?php echo CakeSession::read('DispAddress.tel1');?></p>
            </div>
            <div class="dsn-form">
              <label>お届け日時</label>
              <p><?php echo CakeSession::read('OrderKit.select_delivery_text') ?></p>
            </div>
            <div class="dsn-form">
              <label>お支払い方法</label>
                <p>Amazon Pay</p>
            </div>
          </div>
        </section>
      </div>
    </div>
    <div class="form-group col-lg-12">
      <div class="panel panel-red">
        <div class="panel-heading">
          <label>ご注意ください</label>
          <p>お申込み完了後、日時を含む内容の変更はお受けすることができません。<br>
          内容にお間違いないか再度ご確認の上、「購入する」にお進みください。</p>
        </div>
      </div>
    </div>
    <section class="dsn-nextback dev-forefront">
      <a href="/order/input_amazon_pay" class="dsn-btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a>
      <button type="submit" class="dsn-btn-next">購入する <i class="fa fa-chevron-circle-right"></i></button>
    </section>
    </form>
  </div>
