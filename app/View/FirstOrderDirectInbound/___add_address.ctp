<?php echo $this->element('FirstOrderDirectInbound/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<link href="/first_order_direct_inbound_file/css/first_order_direct_inbound/input_amazon_payment_dev.css" rel="stylesheet">
<link href="/first_order_direct_inbound_file/css/first_order_direct_inbound/dsn-mybox_dev.css" rel="stylesheet">
<!-- Bootstrap Core CSS -->
<link href="/css/bootstrap.min.css" rel="stylesheet">
<!-- Global CSS -->
<link href="https://minikura.com/contents/common/css/app.min.css" rel="stylesheet">
<!-- Remodal CSS -->
<link href="/css/remodal.css" rel="stylesheet">
<link href="/css/remodal-theme.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<link href="/css/dsn-amazon-pay.css" rel="stylesheet">
<title>お届け先住所入力 - minikura</title>

<?php echo $this->element('FirstOrderDirectInbound/header'); ?>
<?php echo $this->element('FirstOrderDirectInbound/nav'); ?>

  <section id="dsn-pagenation">
    <ul>
      <li class="dsn-on"><i class="fa fa-pencil-square-o"></i><span>集荷内容<br>登録</span>
      </li>
      <li><i class="fa fa-credit-card"></i><span>カード<br>登録</span>
      </li>
      <li><i class="fa fa-envelope"></i><span>メール<br>登録</span>
      </li>
      <li><i class="fa fa-check"></i><span>確認</span>
      </li>
      <li><i class="fa fa-truck"></i><span>完了</span>
      </li>
    </ul>
  </section>
  <!-- ADRESS -->
  <section id="dsn-adress">
    <div class="dsn-wrapper">
      <div class="dsn-form">
        <label class="dsn-title">minikuraダイレクトとは？</label>
        <p class="dsn-direct">minikura専用キットを購入する事なく、お手持ちの段ボールやケースをそのままminikuraへ預けていただけるサービスです。
          <br>保管方法はminikuraHAKOと同じ、ボックス単位での保管となります。
          <br>ご自宅にある荷物を梱包して、ヤマト運輸へ集荷を依頼するかお客さま自身で着払いにてminikuraまでお送りください。
        </p>
        <p class="dsn-size">制限サイズ：120サイズ（3辺の合計が120cm以内）重さ15kgまで<br> 幅上限サイズ：59cm
          <br> 高さ上限サイズ：37cm
        </p>
        <label class="dsn-title">ご注意</label>
        <ul class="dsn-caution">
          <li>制限サイズより大きいサイズを預け入れした場合、ボックス料金が追加でかかりますので、ご注意ください。</li>
          <li>お送りいただいたボックスが保管が難しい場合は別途300円追加の上、弊社指定のボックスに入れ替え保管させていただきます。</li>
          <li>お送りいただいたボックスが取り出し時、配送に耐えられないと弊社が判断した場合は別途300円追加の上、弊社指定のボックスに入れ替え保管させていただきます。</li>
          <li>お預かり申し込みできないものを <a href="https://minikura.com/use_agreement/index.html#attachment1" target="_blank">minikrua利用規約 <i class="fa fa-external-link-square"></i></a> でご確認いただき申し込みください。</li>
        </ul>
        <label>預け入れ個数<span class="dsn-required">※</span></label>
        <select class="dsn-select-number">
          <option>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
          <option>6</option>
          <option>7</option>
          <option>8</option>
          <option>9</option>
          <option>10</option>
        </select>
      </div>
    </div>
  </section>

  <section id="dsn-signin-btns">
    <a class="dsn-btn dsn-btn-signin">minikuraで会員登録する <i class="fa fa-chevron-circle-right"></i></a>
    <a class="dsn-btn dsn-btn-amazon">
      <div id="AmazonPayButtonDirect">
      </div>
    </a>
  </section>

  <section id="dsn-signin">
    <section id="dsn-adress">
      <div class="dsn-wrapper">
        <div class="dsn-form">
          <label>お名前<span class="dsn-required">※</span></label>
          <input class="dsn-name-last focused" placeholder="寺田" size="10" maxlength="30"><input class="dsn-name-first focused" placeholder="太郎" size="10" maxlength="30">
        </div>
        <div class="dsn-form">
          <label>フリガナ<span class="dsn-required">※</span></label>
          <input class="dsn-name-last-kana focused" placeholder="テラダ" size="10" maxlength="30"><input class="dsn-name-first-kana focused" placeholder="タロウ" size="10" maxlength="30">
        </div>
        <div class="dsn-divider"></div>
        <div class="dsn-form">
          <label>郵便番号<span class="dsn-required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。<br>入力すると以下の住所が自動で入力されます。</span></label>
          <input class="dsn-postal focused" placeholder="0123456" size="8" maxlength="8">
        </div>
        <div class="dsn-form">
          <label>都道府県市区郡（町村）<span class="dsn-required">※</span></label>
          <input class="dsn-adress1 focused" placeholder="東京都品川区東品川" size="28" maxlength="50">
        </div>
        <div class="dsn-form">
          <label>丁目以降<span class="required">※</span></label>
          <input class="dsn-adress2 focused" placeholder="2-2-28" size="28" maxlength="50">
        </div>
        <div class="dsn-form">
          <label>建物名</label>
          <input class="dsn-build focused" placeholder="Tビル" size="28" maxlength="50">
        </div>
        <div class="dsn-divider"></div>
        <div class="dsn-form">
          <label>電話番号<span class="dsn-required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
          <input class="dsn-tel focused" placeholder="01234567890" size="15" maxlength="15">
        </div>
        <div class="dsn-divider"></div>
        <div class="dsn-form">
          <label>預け入れ方法<span class="dsn-required">※</span></label>
          <label class="dsn-cargo-selected"><input type="radio" name="cargo" value="ヤマト運輸" id="yamato" checked><span class="check-icon"></span> <label for="yamato" class="dsn-cargo-select"> ヤマト運輸に取りに来てもらう</label></label>
          <div class="dsn-yamato">
            <div class="dsn-form">
              <label>集荷希望日<span class="dsn-required">※</span></label>
              <select class="dsn-select-delivery focused">
                <option value="">0000年00月00日</option>
                <option value="">0000年00月00日</option>
                <option value="">0000年00月00日</option>
                <option value="">0000年00月00日</option>
                <option value="">0000年00月00日</option>
              </select>
            </div>
            <div class="dsn-form">
              <label>集荷希望時間<span class="dsn-required">※</span></label>
              <select class="dsn-select-delivery focused">
                <option value="">午前中</option>
                <option value="">12時〜</option>
                <option value="">14時〜</option>
                <option value="">16時〜</option>
                <option value="">18時〜</option>
              </select>
            </div>
          </div>
          <label class="dsn-cargo-selected"><input type="radio" name="cargo" value="着払い" id="arrival"><span class="check-icon"></span> <label for="arrival" class="dsn-cargo-select"> 自分で送る（持ち込みで着払い）</label></label>
          <p class="dsn-arrival">着払いをご選択の場合はminikura運営事務局よりご連絡を差し上げます。<br> ※注意事項
            <br> ご連絡時のメールに記載する住所へ、ヤマト運輸の着払いでお送りください。
            <br> コンビニやヤマト営業所への持ち込みとなります。
          </p>
        </div>
      </div>
    </section>
    <section class="dsn-nextback"><a href="/register/index.php" class="dsn-btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a><a href="credit.php" class="dsn-btn-next">クレジットカード情報を入力 <i class="fa fa-chevron-circle-right"></i></a>
    </section>
  </section>

<?php echo $this->element('FirstOrderDirectInbound/footer'); ?>
<?php echo $this->element('FirstOrderDirectInbound/js'); ?>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?libraries=places"></script>
<script src="/js/minikura/address.js"></script>
<script src="/js/jquery.airAutoKana.js"></script>

<script src="/first_order_direct_inbound_file/js/dsn-mybox.js"></script>
<script src="/first_order_direct_inbound_file/js/first_order_direct_inbound/add_address.js"></script>
<script src="/first_order_direct_inbound_file/js/first_order_direct_inbound/input_amazon_payment.js"></script>
<script type="text/javascript">
  function showButton() {
    var authRequest;
    var host = location.protocol + '//' + location.hostname;
    OffAmazonPayments.Button("AmazonPayButton", AppAmazonPaymentLogin.SELLER_ID, {
      type: "PwA",
      color: "Gold",
      size: "medium",
      authorization: function () {
        loginOptions = {scope: "profile payments:widget", popup: "true"};
        authRequest = amazon.Login.authorize(loginOptions, host + "/first_order/input_amazon_profile");
      }
    });
  }
  function showButtonDirect() {
    var authRequest;
    var host = location.protocol + '//' + location.hostname;
    OffAmazonPayments.Button("AmazonPayButtonDirect", AppAmazonPaymentLogin.SELLER_ID, {
      type: "PwA",
      color: "Gold",
      size: "medium",
      authorization: function () {
        loginOptions = {scope: "profile payments:widget", popup: "true"};
        authRequest = amazon.Login.authorize(loginOptions, host + "/first_order_direct_inbound/input_amazon_profile");
      }
    });
  }

</script>

<script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>"></script>
<?php echo $this->element('FirstOrderDirectInbound/last'); ?>
