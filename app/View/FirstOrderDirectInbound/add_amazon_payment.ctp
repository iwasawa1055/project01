<?php echo $this->element('FirstOrderDirectInbound/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<link href="/first_order_direct_inbound_file/css/dsn-mybox.css" rel="stylesheet">
<link href="/css/dsn-amazon-pay.css" rel="stylesheet">
<link href="/first_order_direct_inbound_file/css/first_order_direct_inbound/add_amazon_payment_dev.css" rel="stylesheet">
<title>Amazonアカウントでお支払い - minikura</title>

<?php echo $this->element('FirstOrderDirectInbound/header'); ?>
<?php echo $this->element('FirstOrderDirectInbound/nav'); ?>

<!-- PAGENATION -->
  <section id="dsn-pagenation">
    <ul>
      <li><i class="fa fa-pencil-square-o"></i><span>集荷内容<br>登録</span>
      </li>
      <li class="dsn-on"><i class="fa fa-amazon"></i><span>Amazon<br>アカウントで<br>お支払い</span>
      </li>
      <li><i class="fa fa-check"></i><span>確認</span>
      </li>
      <li><i class="fa fa-truck"></i><span>完了</span>
      </li>
    </ul>
  </section>
  <!-- ADRESS -->
  <div id="full" class="dsn-wrapper">
    <form method="post" action="/first_order_direct_inbound/nv_confirm_amazon_payment" novalidate>
      <section id="dsn-adress">
        <div class="dsn-wrapper">
          <div class="dsn-divider"></div>

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
            <label>パスワード<span class="dsn-required">※</span><br><span>半角英数記号8文字以上でご入力ください。</span></label>
            <input class="dsn-password focused" type="password" size="20" maxlength="20" name="password">
            <?php echo $this->Flash->render('password');?>
          </div>
          <div class="dsn-form">
            <label>パスワード（確認用）<span class="dsn-required">※</span></label>
            <input class="dsn-password focused" type="password" size="20" maxlength="20" name="password_confirm">
            <?php echo $this->Flash->render('password_confirm');?>
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
          <div class="dsn-divider"></div>
          <div class="dsn-form form-line">
            <label>生年月日<span class="dsn-required">※</span></label>
            <select class="dsn-select-birth-year focused">
              <option value="">1985年</option>
              <option value="">1986年</option>
              <option value="">1987年</option>
              <option value="">1988年</option>
              <option value="">1989年</option>
              <option value="">1990年</option>
            </select>
            <select class="dsn-select-birth-month focused">
              <option value="">1月</option>
              <option value="">2月</option>
              <option value="">3月</option>
              <option value="">4月</option>
              <option value="">5月</option>
              <option value="">6月</option>
              <option value="">7月</option>
              <option value="">8月</option>
              <option value="">9月</option>
              <option value="">10月</option>
              <option value="">11月</option>
              <option value="">12月</option>
            </select>
            <select class="dsn-select-birth-day focused">
              <option value="">1日</option>
              <option value="">2日</option>
              <option value="">3日</option>
              <option value="">4日</option>
              <option value="">5日</option>
              <option value="">6日</option>
              <option value="">7日</option>
              <option value="">8日</option>
              <option value="">9日</option>
              <option value="">10日</option>
              <option value="">11日</option>
              <option value="">12日</option>
              <option value="">13日</option>
              <option value="">14日</option>
              <option value="">15日</option>
              <option value="">16日</option>
              <option value="">17日</option>
              <option value="">18日</option>
              <option value="">19日</option>
              <option value="">20日</option>
              <option value="">21日</option>
              <option value="">22日</option>
              <option value="">23日</option>
              <option value="">24日</option>
              <option value="">25日</option>
              <option value="">26日</option>
              <option value="">27日</option>
              <option value="">28日</option>
              <option value="">29日</option>
              <option value="">30日</option>
              <option value="">31日</option>
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
      <section class="dsn-nextback"><a href="#" class="dsn-btn-next js-btn-submit">最後の確認へ <i class="fa fa-chevron-circle-right"></i></a>
      </section>
    </form>
  </div>


<?php echo $this->element('FirstOrderDirectInbound/footer'); ?>
<?php echo $this->element('FirstOrderDirectInbound/js'); ?>
<script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>"></script>
<script src="/first_order_direct_inbound_file/js/first_order_direct_inbound/add_amazon_payment.js"></script>

<?php echo $this->element('FirstOrderDirectInbound/last'); ?>
