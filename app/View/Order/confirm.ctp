<?php $this->Html->css('/first_order_file/css/app.css', ['block' => 'css']); ?>
<?php $this->Html->css('/first_order_file/css/app_dev.css', ['block' => 'css']); ?>

    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>
      </div>
    </div>
    <div class="row">
      <form method="post" action="/order/complete" novalidate>
        <!-- ADRESS -->
        <section id="adress">
          <div class="wrapper">
            <div class="form">
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

                </tbody>
              </table>
            </div>

            <div class="divider"></div>
            <div class="form">
              <?php echo $this->Flash->render('customer_regist_info');?>
              <?php echo $this->Flash->render('customer_address_info');?>
              <?php echo $this->Flash->render('customer_kit_card_info');?>
              <label>お届け先住所</label>
              <p>〒<?php echo CakeSession::read('Address.postal');?></p>
              <p><?php echo CakeSession::read('Address.pref');?><?php echo CakeSession::read('Address.address1');?><?php echo CakeSession::read('Address.address2');?> <?php echo CakeSession::read('Address.address3');?></p>
              <p><?php echo CakeSession::read('Address.lastname');?>　<?php echo CakeSession::read('Address.firstname');?></p>
              <p><?php echo CakeSession::read('Address.lastname_kana');?>　<?php echo CakeSession::read('Address.firstname_kana');?></p>
              <p><?php echo CakeSession::read('Address.tel1');?></p>
            </div>
            <div class="form">
              <label>お届け日時</label>
              <p><?php echo CakeSession::read('OrderKit.select_delivery_text') ?></p>
            </div>
          </div>
        </section>
        <section class="nextback"><a href="/order/input" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a><button type="submit" class="btn-next">この内容でボックスを購入 <i class="fa fa-chevron-circle-right"></i></button>
        </section>
      </form>
    </div>
