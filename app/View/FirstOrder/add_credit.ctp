<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>クレジットカード情報登録 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>
<?php echo $this->element('FirstOrder/breadcrumb_list'); ?>
  <!-- ADRESS -->
  <form id="credit_info" method="post" action="/first_order/confirm_credit" novalidate>
  <input type="hidden" id="shop_id" value="<?php echo Configure::read('app.gmo.shop_id'); ?>">
  <section id="adress">
    <div class="wrapper">
      <div class="form">
        <div id="gmo_validate_error"></div>
        <div id="gmo_credit_card_info"></div>
        <?php echo $this->Flash->render('gmo_token');?>
      </div>
      <div class="form">
        <label>クレジットカード番号<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input type="tel" id="cardno" class="name focused" name="cardno" placeholder="0000-0000-0000-0000" size="20" maxlength="20" value="">
      </div>
      <div class="form">
        <label>セキュリティコード<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input type="tel" id="securitycode" class="postal focused" name="securitycode" placeholder="0123" size="6" maxlength="6" value="">
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
      </div>
      <div class="form">
        <label>カード名義<span class="required">※</span></label>
        <input type="url" id="holdername" class="adress2 holder_name focused" name="holdername" placeholder="TERRADA MINIKURA" size="28" maxlength="30" value="" novalidate>
      </div>
      <div class="form">
        <a href="https://minikura.com/privacy_case/" target="_blank" class="link-terms"><i class="fa fa-chevron-circle-right"></i> クレジットカード情報の取り扱いについて</a>
      </div>
    </div>
  </section>
  <section class="nextback"><a href="/first_order/confirm?back=true" class="btn-back">
    <i class="fa fa-chevron-circle-left"></i> 戻る</a><button type="button" id="execute" class="btn-next" formnovalidate>このカードでボックスを購入<i class="fa fa-chevron-circle-right"></i></button>
  </section>
  </form>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<script type='text/javascript' src='<?php echo Configure::read("app.gmo.token_url"); ?>'></script>
<script type='text/javascript' src="/js/libGmoCreditCardPayment.js"></script>
<script type='text/javascript' src="/js/gmoCreditCardPayment.js"></script>
<script type='text/javascript' src="/first_order_file/js/first_order/add_credit.js"></script>
<?php echo $this->element('FirstOrder/last'); ?>
