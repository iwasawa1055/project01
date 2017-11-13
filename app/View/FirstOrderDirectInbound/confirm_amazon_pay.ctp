<?php echo $this->element('FirstOrderDirectInbound/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<link href="/first_order_direct_inbound_file/css/dsn-mybox.css" rel="stylesheet">
<link href="/css/dsn-amazon-pay.css" rel="stylesheet">
<title>注文内容確認 - minikura</title>
<?php echo $this->element('FirstOrderDirectInbound/header'); ?>
<?php echo $this->element('FirstOrderDirectInbound/nav'); ?>

<!-- PAGENATION -->
  <section id="dsn-pagenation">
    <ul>
      <li><i class="fa fa-pencil-square-o"></i><span>集荷内容<br>登録</span>
      </li>
      <li><i class="fa fa-amazon"></i><span>Amazon<br>アカウントで<br>お支払い</span>
      </li>
      <li class="dsn-on"><i class="fa fa-check"></i><span>確認</span>
      </li>
      <li><i class="fa fa-truck"></i><span>完了</span>
      </li>
    </ul>
  </section>
  
  <form method="post" action="/first_order_direct_inbound/complete_amazon_pay" novalidate>
  <!-- ADRESS -->
  <section id="dsn-adress">
    <div class="dsn-wrapper">
      <div class="dsn-form" id="price_table">
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

      <div class="dsn-divider"></div>
      <div class="dsn-form">
        <?php echo $this->Flash->render('customer_regist_info');?>
        <?php echo $this->Flash->render('customer_card_info');?>
        <?php echo $this->Flash->render('customer_address_info');?>
        <?php echo $this->Flash->render('inbound_direct');?>

        <?php if (CakeSession::read('Address.cargo') !== "着払い") : ?>
          <label>集荷先住所</label>
        <?php else: ?>
          <label>ご登録住所</label>
        <?php endif; ?>

        <p>〒<?php echo CakeSession::read('Address.postal');?></p>
        <p><?php echo CakeSession::read('Address.pref');?><?php echo CakeSession::read('Address.address1');?><?php echo CakeSession::read('Address.address2');?> <?php echo CakeSession::read('Address.address3');?></p>
        <p><?php echo CakeSession::read('Address.lastname');?>　<?php echo CakeSession::read('Address.firstname');?></p>
        <p><?php echo CakeSession::read('Address.lastname_kana');?>　<?php echo CakeSession::read('Address.firstname_kana');?></p>
        <p><?php echo CakeSession::read('Address.tel1');?></p>
      </div>

      <?php if (CakeSession::read('Address.cargo') !== "着払い") : ?>
        <div class="dsn-form">
          <label>集荷日時</label>
          <p><?php echo CakeSession::read('Address.select_delivery_text') ?></p>
        </div>
      <?php else: ?>
        <div class="dsn-form">
          <label>預け入れ方法</label>
          <p>自分で送る（持ち込みで着払い）</p>
        </div>
      <?php endif; ?>
      <div class="dsn-divider"></div>
      <div class="dsn-form">
        <label>メールアドレス</label>
        <p><?php echo CakeSession::read('Email.email');?></p>
      </div>
      <div class="dsn-form">
        <label>お知らせメール</label>
        <p>
          <?php if ( CakeSession::read('Email.newsletter') === "1" ) : ?>
            希望する
          <?php else: ?>
            希望しない
          <?php endif ?>
        </p>
      </div>
      <div class="dsn-form">
        <label>紹介コード</label>
        <p><?php echo CakeSession::read('Email.alliance_cd');?></p>
      </div>
    </div>
  </section>
  <section class="dsn-nextback"><a href="/first_order_direct_inbound/add_amazon_pay?back=true" class="dsn-btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a><button type="submit" class="dsn-btn-next">この内容で申し込みをする <i class="fa fa-chevron-circle-right"></i></button>
  </section>
  </form>
<?php echo $this->element('FirstOrderDirectInbound/footer'); ?>
<?php echo $this->element('FirstOrderDirectInbound/js'); ?>
<script src="/first_order_file/js/first_order/confirm.js"></script>
<script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>"></script>
<?php echo $this->element('FirstOrderDirectInbound/last'); ?>
