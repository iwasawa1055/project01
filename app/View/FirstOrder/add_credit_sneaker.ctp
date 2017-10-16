<?php echo $this->element('FirstOrder/first_sneaker'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>クレジットカード情報登録 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav_sneaker'); ?>
<?php echo $this->element('FirstOrder/breadcrumb_list'); ?>
  <!-- ADRESS -->
  <form id="credit_info" method="post" action="/first_order/confirm_credit" novalidate>
  <input type="hidden" id="shop_id" value="<?php echo Configure::read('app.gmo.shop_id'); ?>">
  <section id="adress">
    <div class="wrapper">
      <div class="form">
        <label>クレジットカード番号<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input type="tel" id="cardno" class="name focused" name="cardno" placeholder="0000-0000-0000-0000" size="20" maxlength="20" value="">
        <div id="error_cardno"></div>
        <?php echo $this->Flash->render('gmo_token');?>
      </div>
      <div class="form">
        <label>セキュリティコード<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input type="tel" id="securitycode" class="postal focused" name="securitycode" placeholder="0123" size="6" maxlength="6" value="">
        <div id="error_securitycode"></div>
      </div>
      <div class="form">
        <label>カード有効期限<span class="required">※</span></label>
        <select class="select-month focused" id="expiremonth" name="expiremonth">
          <?php foreach ( $this->Html->creditcardExpireMonth() as $value => $string ) :?>
                <option value="<?php echo $value;?>"><?php echo $string;?></option>
          <?php endforeach ?>
        </select>
        /
        <select class="select-year focused" id="expireyear" name="expireyear">
          <?php foreach ( $this->Html->creditcardExpireYear() as $value => $string ) :?>
                <option value="<?php echo $value;?>"><?php echo $string;?></option>
          <?php endforeach ?>
        </select>
        <br>
        <div id="error_expire"></div>
      </div>
      <div class="form">
        <label>カード名義<span class="required">※</span></label>
        <input type="url" id="holdername" class="adress2 holder_name focused" name="holdername" placeholder="TERRADA MINIKURA" size="28" maxlength="30" value="" novalidate>
        <div id="error_holdername"></div>
      </div>
      <div class="form">
        <a href="https://minikura.com/privacy_case/" target="_blank" class="link-terms"><i class="fa fa-chevron-circle-right"></i> クレジットカード情報の取り扱いについて</a>
      </div>
    </div>
  </section>
  <section class="nextback"><a href="/first_order/add_address_sneaker" class="btn-back">
    <i class="fa fa-chevron-circle-left"></i> 戻る</a><button type="button" id="execute" class="btn-next" formnovalidate>このカードでボックスを購入<i class="fa fa-chevron-circle-right"></i></button>
  </section>
  </form>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js_sneaker'); ?>
<script type='text/javascript' src='https://pt01.mul-pay.jp/ext/js/token.js'></script>
<script type='text/javascript' src="/js/jquery.gmoCreditPayment.js"></script>
<script type='text/javascript' src="/first_order_file/js/sneaker/add_credit.js"></script>
<?php echo $this->element('FirstOrder/last'); ?>
