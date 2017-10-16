<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>注文内容確認 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>
<?php echo $this->element('FirstOrder/breadcrumb_list'); ?>
  <form method="post" action="/first_order/add_credit" novalidate>
  <!-- ADRESS -->
  <section id="adress">
    <div class="wrapper">
      <div class="form" id="price_table">
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
            <?php foreach ( CakeSession::read('FirstOrderList') as $key => $value ) {?>
              <tr>
                <th><?php echo $value['kit_name'] ?></th>
                <td><div class="text-right"><?php echo $value['number'] ?></div></td>
                <td><div class="text-right"><?php echo $value['price'] ?>円</div></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
        <?php echo $this->Flash->render('kit_price');?>
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
      <?php if (!$is_logined) : ?>
        <div class="form">
          <label>紹介コード</label>
          <p><?php echo CakeSession::read('Email.alliance_cd');?></p>
        </div>
      <?php endif; ?>
    </div>
  </section>
  <section class="nextback"><a href="/first_order/add_email?back=true" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a><button type="submit" class="btn-next">クレジットカード情報を入力 <i class="fa fa-chevron-circle-right"></i></button>
  </section>
  </form>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<script src="/first_order_file/js/first_order/confirm.js"></script>
<?php echo $this->element('FirstOrder/last'); ?>
