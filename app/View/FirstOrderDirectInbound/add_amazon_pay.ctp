<?php echo $this->element('FirstOrderDirectInbound/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<link href="/first_order_direct_inbound_file/css/dsn-mybox.css" rel="stylesheet">
<link href="/css/dsn-amazon-pay.css" rel="stylesheet">
<link href="/first_order_direct_inbound_file/css/first_order_direct_inbound/add_amazon_pay_dev.css" rel="stylesheet">
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
    <form method="post" action="/first_order_direct_inbound/confirm_amazon_pay" novalidate>
      <section id="dsn-adress">
        <div class="dsn-wrapper dev-wrapper">

          <div class='dsn-form'>
            <?php echo $this->Flash->render('customer_amazon_pay_info');?>
            <?php echo $this->Flash->render('customer_address_info');?>
          </div>

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

          <div class="dsn-form">
            <?php // アマゾンから取得した情報をバリデーション ?>
            <?php echo $this->Flash->render('postal');?>
            <?php echo $this->Flash->render('pref');?>
            <?php echo $this->Flash->render('address1');?>
            <?php echo $this->Flash->render('address2');?>
            <?php echo $this->Flash->render('tel1');?>
          </div>

          <?php /* すでに会員登録済みの場合は、入力フォームを非表示とする. */?>
          <?php if (CakeSession::read('FirstOrderDirectInbound.regist_user_complete') === true) : ?>

          <input type="hidden" id="amazon_billing_agreement_id" value="<?php echo CakeSession::read('FirstOrderDirectInbound.amazon_pay.amazon_billing_agreement_id'); ?>">
          <input type="hidden" id="regist_user_flg" value="1">

          <div class="dsn-divider"></div>
          <div class="dsn-form">
            <label>預け入れ方法<span class="dsn-required">※</span></label>
              <label class="dsn-cargo-selected"><input type="radio" name="cargo" value="ヤマト運輸" id="yamato"  <?php if ( CakeSession::read('Address.cargo') === "ヤマト運輸" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="yamato" class="dsn-cargo-select"> ヤマト運輸に取りに来てもらう</label></label>
            <div class="dsn-yamato">
              <div class="dsn-form">
                <label>集荷希望日<span class="dsn-required">※</span></label>
                <select class="dsn-select-delivery focused" name="date_cd" id="InboundDayCd">
                </select>
                <br>
                <?php echo $this->Flash->render('date_cd');?>
                <input type="hidden" name="select_delivery_day" id="select_delivery_day" value="<?php echo !empty(CakeSession::read('Address.date_cd')) ? CakeSession::read('Address.date_cd') : ''; ?>">
              </div>
              <div class="dsn-form">
                <label>集荷希望時間<span class="dsn-required">※</span></label>
                <select class="dsn-select-delivery focused" name="time_cd" id="InboundTimeCd">
                </select>
                <br>
                <?php echo $this->Flash->render('time_cd');?>
                <input type="hidden" name="select_delivery_time" id="select_delivery_time" value="<?php echo !empty(CakeSession::read('Address.time_cd')) ?  CakeSession::read('Address.time_cd') : ''; ?>">
              </div>
            </div>
            <label class="dsn-cargo-selected"><input type="radio" name="cargo" value="着払い" id="arrival" <?php if ( CakeSession::read('Address.cargo') === "着払い" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="arrival" class="dsn-cargo-select"> 自分で送る（持ち込みで着払い）</label></label>
            <p class="dsn-arrival">着払いをご選択の場合はminikura運営事務局よりご連絡を差し上げます。<br> ※注意事項
              <br> ご連絡時のメールに記載する住所へ、ヤマト運輸の着払いでお送りください。
              <br> コンビニやヤマト営業所への持ち込みとなります。
            </p>
          </div>

          <?php /* 会員登録が未完了の場合は、入力フォームを表示とする. */?>
          <?php else: ?>

          <input type="hidden" id="regist_user_flg" value="0">
          <input type="hidden" id="amazon_billing_agreement_id" value="">

          <div class="dsn-divider"></div>
          <div class="dsn-form">
            <label>お名前<span class="required">※</span></label>
            <input type="text" name="lastname" class="dsn-name-last lastname focused" placeholder="寺田" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.lastname');?>">
            <input type="text" name="firstname" class="dsn-name-first firstname focused" placeholder="太郎" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.firstname');?>">
            <br>
            <?php echo $this->Flash->render('lastname'); ?>
            <?php echo $this->Flash->render('firstname'); ?>
          </div>
          <div class="dsn-form">
            <label>フリガナ<span class="required">※</span></label>
            <input type="text" name="lastname_kana" class="dsn-name-last-kana lastname_kana focused" placeholder="テラダ" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.lastname_kana');?>">
            <input type="text" name="firstname_kana" class="dsn-name-first-kana firstname_kana focused" placeholder="タロウ" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.firstname_kana');?>">
            <br>
            <?php echo $this->Flash->render('lastname_kana'); ?>
            <?php echo $this->Flash->render('firstname_kana'); ?>
          </div>
          <div class="dsn-divider"></div>
          <div class="dsn-form">
            <label>預け入れ方法<span class="dsn-required">※</span></label>
            <label class="dsn-cargo-selected"><input type="radio" name="cargo" value="ヤマト運輸" id="yamato"  <?php if ( CakeSession::read('Address.cargo') === "ヤマト運輸" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="yamato" class="dsn-cargo-select"> ヤマト運輸に取りに来てもらう</label></label>
            <div class="dsn-yamato">
              <div class="dsn-form">
                <label>集荷希望日</label>
                <select name="date_cd" id="InboundDayCd" class="dsn-select-delivery focused dev-input-form">
                </select>
                <br>
                <?php echo $this->Flash->render('date_cd');?>
                <input type="hidden" name="select_delivery_day" id="select_delivery_day" value="<?php echo !empty(CakeSession::read('Address.date_cd')) ? CakeSession::read('Address.date_cd') : ''; ?>">
              </div>
              <div class="dsn-form">
                <label>集荷希望時間</label>
                <select name="time_cd" id="InboundTimeCd" class="dsn-select-delivery focused dev-input-form">
                </select>
                <input type="hidden" name="select_delivery_time" id="select_delivery_time" value="<?php echo !empty(CakeSession::read('Address.time_cd')) ?  CakeSession::read('Address.time_cd') : ''; ?>">
                <br>
                <?php echo $this->Flash->render('time_cd');?>
              </div>
            </div>
            <label class="dsn-cargo-selected"><input type="radio" name="cargo" value="着払い" id="arrival" <?php if ( CakeSession::read('Address.cargo') === "着払い" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="arrival" class="dsn-cargo-select"> 自分で送る（持ち込みで着払い）</label></label>
            <p class="dsn-arrival">着払いをご選択の場合はminikura運営事務局よりご連絡を差し上げます。<br> ※注意事項
              <br> ご連絡時のメールに記載する住所へ、ヤマト運輸の着払いでお送りください。
              <br> コンビニやヤマト営業所への持ち込みとなります。
            </p>
          </div>
          <div class="dsn-divider"></div>
          <div class="dsn-form dsn-form-line">
            <label>お知らせメール</label>
            <select class="dsn-select-info focused" name="newsletter">
              <option value="1"<?php if ( CakeSession::read('Email.newsletter') === "1" ) echo " SELECTED";?>>受信する</option>
              <option value="0"<?php if ( CakeSession::read('Email.newsletter') === "0" ) echo " SELECTED";?>>受信しない</option>
            </select>
          </div>
          <div class="dsn-form dsn-form-line">
            <label>紹介コード</label>
            <input class="dsn-referral focused" type="text" size="20" name="alliance_cd" maxlength="20" value="<?php echo CakeSession::read('Email.alliance_cd');?>">
            <br>
            <?php echo $this->Flash->render('alliance_cd');?>
          </div>
          <div class="dsn-divider"></div>
          <div class="dsn-form">
            <label class="dsn-terms"><input type="checkbox" class="dsn-term agree-before-submit focused" id="term" name="remember" value="Remember Me"><span class="check-icon"></span>
              <label for="term" class="dsn-term"><a href="https://minikura.com/use_agreement/" target="_blank" class="dsn-link-terms">minikura利用規約</a>と<a href="https://minikura.com/privacy/" target="_blank" class="dsn-link-terms">個人情報の取扱いについて</a>に同意する</label>
            </label>
            <?php echo $this->Flash->render('remember');?>
            <span id="js-remember_validation" style="display:none;">利用規約にチェックしてください。</span>
          </div>
          <?php endif; ?>
        </div>
      </section>
      <section class="dsn-nextback">
        <button class="dsn-btn-next agree-submit js-btn-submit" type="submit">最後の確認へ <i class="fa fa-chevron-circle-right"></i></button>
      </section>
    </form>
  </div>


<?php echo $this->element('FirstOrderDirectInbound/footer'); ?>
<?php echo $this->element('FirstOrderDirectInbound/js'); ?>
<?php if (empty(CakeSession::read('FirstOrder.amazon_pay.access_token'))):?>
<script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>"></script>
<?php endif; ?>
<script src="/first_order_direct_inbound_file/js/first_order_direct_inbound/add_amazon_pay.js"></script>
<script src="/first_order_direct_inbound_file/js/dsn-mybox.js"></script>
<script src="/js/jquery.airAutoKana.js"></script>
<script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>"></script>

<?php echo $this->element('FirstOrderDirectInbound/last'); ?>
