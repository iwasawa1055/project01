<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<link href="/first_order_file/css/dsn-register.css" rel="stylesheet">
<link href="/first_order_file/css/first_order/input_amazon_payment_dev.css" rel="stylesheet">
<title>Amazonアカウントでお支払い - minikura</title>

<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>

<!-- PAGENATION -->
<section id="dsn-pagenation">
  <ul>
    <li><i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
    </li>
    <li class="dsn-on"><i class="fa fa-amazon"></i><span>Amazon<br>アカウントで<br>お支払い</span>
    </li>
    <li><i class="fa fa-check"></i><span>確認</span>
    </li>
    <li><i class="fa fa-truck"></i><span>完了</span>
    </li>
  </ul>
</section>
<div id="full" class="dsn-wrapper">
  <form method="post" action="/first_order/nv_confirm_amazon_payment" novalidate>
    <section id="dsn-adress">
      <div class="dsn-wrapper">
        <div id="dsn-amazon-pay" class="form-group col-lg-12">
          <div class="dsn-address">
            <div id="addressBookWidgetDiv">
            </div>
          </div>
          <div class="dsn-credit">
            <div id="walletWidgetDiv">
            </div>
          </div>
        </div>
        <div id="dsn-payment" class="form-group col-lg-12">
            <div id="consentWidgetDiv">
            </div>
        </div>
        <div class="dsn-divider"></div>
        <div class="dsn-form">
          <label>パスワード<span class="dsn-required">※</span><br><span>minikuraに会員登録するためのパスワードになります。<br>半角英数記号8文字以上でご入力ください。</span></label>
          <input class="dsn-password focused" type="password" size="20" maxlength="20">
        </div>
        <div class="dsn-form">
          <label>パスワード（確認用）<span class="dsn-required">※</span></label>
          <input class="dsn-password focused" type="password" size="20" maxlength="20">
        </div>
        <div class="dsn-divider"></div>
        <div class="dsn-form">
          <label>お届け希望日<span class="dsn-required">※</span></label>
          <select class="dsn-select-delivery focused">
            <option value="">0000年00月00日 午前中</option>
            <option value="">0000年00月00日 12時〜</option>
            <option value="">0000年00月00日 14時〜</option>
            <option value="">0000年00月00日 16時〜</option>
            <option value="">0000年00月00日 18時〜</option>
          </select>
        </div>
        <div class="dsn-divider"></div>
        <div class="dsn-form dsn-form-line">
          <label>お知らせメール</label>
          <select class="dsn-select-info focused">
            <option value="">受信する</option>
            <option value="">受信しない</option>
          </select>
        </div>
        <div class="dsn-form dsn-form-line">
          <label>紹介コード</label>
          <input class="dsn-referral focused" type="text" size="20" maxlength="20">
        </div>
        <div class="dsn-divider"></div>
        <div class="dsn-form">
          <label class="dsn-terms"><input type="checkbox" class="dsn-term focused" id="term"><span class="check-icon"></span> <label for="term" class="dsn-term">minikura利用規約に同意する<a href="https://minikura.com/use_agreement/" target="_blank" class="dsn-link-terms"><i class="fa fa-chevron-circle-right"></i> 利用規約</a></label></label>
        </div>
      </div>
  </section>
</form>
  <section class="dsn-nextback">
    <a href="/first_order/add_order" class="dsn-btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a>
    <a href="#" class="dsn-btn-next js-btn-submit">確認へ  <i class="fa fa-chevron-circle-right"></i></a>
  </section>
</div>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>"></script>
<script src="/first_order_file/js/first_order/input_amazon_payment.js"></script>

<?php echo $this->element('FirstOrder/last'); ?>
