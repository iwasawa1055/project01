<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<meta name="format-detection" content="telephone=no">
<title>注文内容確認 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>
<section id="pagenation">
  <ul>
    <li><i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
    </li>
    <li><i class="fa fa-pencil-square-o"></i><span>お届け先<br>登録</span>
    </li>
    <li><i class="fa fa-credit-card"></i><span>カード<br>登録</span>
    </li>
    <li><i class="fa fa-envelope"></i><span>メール<br>登録</span>
    </li>
    <li class="on"><i class="fa fa-check"></i><span>確認</span>
    </li>
    <li><i class="fa fa-truck"></i><span>完了</span>
    </li>
  </ul>
</section>
  <form method="post" action="/first_order/complete" novalidate>
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
          <?php if (key_exists('starter', CakeSession::read('Order'))) {?>
            <tr>
              <th><?php echo Configure::read('app.first_order.starter_kit.name') ?></th>
              <td><div class="text-right">1</div></td>
              <td><div class="text-right"><?php echo Configure::read('app.first_order.starter_kit.price') ?>円</div></td>
            </tr>
          <?php } else { ?>
            <?php foreach ( CakeSession::read('PurchaseOrder') as $key => $value ) {?>
              <tr>
                <th><?php echo $value['kit_name'] ?></th>
                <td><div class="text-right"><?php echo $value['number'] ?></div></td>
                <td><div class="text-right"><?php echo $value['price'] ?>円</div></td>
              </tr>
            <?php } ?>
          <?php } ?>
          </tbody>
        </table>
      </div>

      <div class="divider"></div>
      <div class="form">
        <?php echo $this->Flash->render('customer_regist_info');?>
        <?php echo $this->Flash->render('customer_card_info');?>
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
        <p><?php echo CakeSession::read('Address.select_delivery_text') ?></p>
      </div>
      <div class="divider"></div>
      <div class="form">
        <label>メールアドレス</label>
        <p><?php echo CakeSession::read('Email.email');?></p>
      </div>
      <?php if (!$is_logined) : ?>
        <div class="form">
          <label>お知らせメール</label>
          <p>
            <?php if ( CakeSession::read('Email.newsletter') === "1" ) : ?>
              希望する
            <?php else: ?>
              希望しない
            <?php endif ?>
          </p>
        </div>
      <?php endif; ?>
    </div>
  </section>
  <section class="nextback"><a href="/first_order/add_email?back=true" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a><button type="submit" class="btn-next">この内容でボックスを購入 <i class="fa fa-chevron-circle-right"></i></button>
  </section>
  </form>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<?php echo $this->element('FirstOrder/last'); ?>
