<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<link href="/first_order_file/css/dsn-register.css" rel="stylesheet">
<link href="/first_order_file/css/first_order/add_amazon_pay_dev.css" rel="stylesheet">
<link href="/css/dsn-amazon-pay.css" rel="stylesheet">
<link href="/first_order_file/css/first_order/dsn-amazon-pay_dev.css" rel="stylesheet">
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
  <form method="post" action="/first_order/confirm_amazon_pay" novalidate>
    <section id="dsn-adress">
      <?php echo $this->Flash->render('customer_regist_info');?>
      <?php echo $this->Flash->render('customer_amazon_pay_info');?>
      <?php echo $this->Flash->render('customer_address_info');?>
      <?php echo $this->Flash->render('customer_kit_info');?>
      <div class="dsn-wrapper">
        <div class="dsn-form"><div class="alert alert-danger" role="alert" id="error_alert"><i class="fa fa-exclamation-triangle"></i> </div></div>
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
        <div class="dsn-form">
          <?php // アマゾンから取得した情報をバリデーション ?>
          <?php echo $this->Flash->render('lastname'); ?>
          <?php echo $this->Flash->render('firstname'); ?>
          <?php echo $this->Flash->render('lastname_kana'); ?>
          <?php echo $this->Flash->render('firstname_kana'); ?>
          <?php echo $this->Flash->render('postal');?>
          <?php echo $this->Flash->render('pref');?>
          <?php echo $this->Flash->render('address1');?>
          <?php echo $this->Flash->render('address2');?>
          <?php echo $this->Flash->render('tel1');?>
        </div>

        <div id="dsn-payment" class="form-group col-lg-12">
            <div id="consentWidgetDiv">
            </div>
          <span class="validation" id="payment_consent_alert">お支払方法の設定は必須です。</span>
        </div>
        <div class="dsn-divider"></div>
        <div class="dsn-form">
          <label>パスワード<span class="dsn-required">※</span><br><span>minikuraに会員登録するためのパスワードになります。<br>半角英数記号8文字以上でご入力ください。</span></label>
          <input class="dsn-password focused" type="password" size="20" maxlength="20" name="password">
          <?php echo $this->Flash->render('password');?>
        </div>
        <div class="dsn-form">
          <label>パスワード（確認用）<span class="dsn-required">※</span></label>
          <input class="dsn-password focused" type="password" size="20" maxlength="20" name="password_confirm">
          <?php echo $this->Flash->render('password_confirm');?>
        </div>
        <div class="dsn-divider"></div>
        <div class="dsn-form form-line">
          <label>生年月日<span class="dsn-required">※</span></label>
          <select class="dsn-select-birth-year focused" name="birth_year">
            <?php for ($i = date('Y'); $i >= $birthyear_configure['birthyear_start']; $i--) :?>
              <option value="<?php echo $i;?>"<?php if ( $i === (int) CakeSession::read('Email.birth_year') ) echo " SELECTED";?>><?php echo $i;?>年</option>
            <?php endfor;?>
          </select>
          <select class="dsn-select-birth-month focused" name="birth_month">
            <?php for ($i = 1; $i <= 12; $i++) :?>
              <option value="<?php echo $i;?>"<?php if ( $i === (int) CakeSession::read('Email.birth_month') ) echo " SELECTED";?>><?php echo $i;?>月</option>
            <?php endfor;?>
          </select>
          <select class="dsn-select-birth-day focused" name="birth_day">
            <?php for ($i = 1; $i <= 31; $i++) :?>
              <option value="<?php echo $i;?>"<?php if ( $i === (int) CakeSession::read('Email.birth_day') ) echo " SELECTED";?>><?php echo $i;?>日</option>
            <?php endfor;?>
          </select>
        </div>
        <?php echo $this->Flash->render('birth');?>
        <div class="dsn-form dsn-form-line">
          <label>性別<span class="dsn-required">※</span></label>
          <label class="dsn-genders"><input type="radio" name="gender" value="m" id="man"<?php if ( CakeSession::read('Email.gender') === "m" ) echo " CHECKED";?>><span class="check-icon"></span><label for="man" class="dsn-gender">男</label></label>
          <label class="dsn-genders"><input type="radio" name="gender" value="f" id="woman"<?php if ( CakeSession::read('Email.gender') === "f" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="woman" class="dsn-gender">女</label></label>
        </div>
        <?php echo $this->Flash->render('gender');?>
        <div class="dsn-divider"></div>
        <div class="dsn-form">
          <label>お届け希望日<span class="dsn-required">※</span></label>
          <select class="dsn-select-delivery focused" id="datetime_cd" name="datetime_cd">
            <option value="">以下からお選びください</option>
          </select>
          <?php echo $this->Flash->render('datetime_cd');?>
        </div>
        <div class="dsn-divider"></div>
        <div class="dsn-form dsn-form-line">
          <label>お知らせメール</label>
          <select class="dsn-select-info focused" name="newsletter">
            <option value="1">受信する</option>
            <option value="0">受信しない</option>
          </select>
          <?php echo $this->Flash->render('newsletter');?>
        </div>
        <div class="dsn-form dsn-form-line">
          <label>紹介コード</label>
          <input class="dsn-referral focused" type="text" size="20" maxlength="20" name="alliance_cd">
          <?php echo $this->Flash->render('alliance_cd');?>
        </div>
        <div class="dsn-divider"></div>
        <div class="dsn-form">
          <label class="dsn-terms">
            <input type="checkbox" class="dsn-term agree-before-submit focused" id="term" name="remember" value="Remember Me"><span class="check-icon"></span>
              <label for="term" class="dsn-term">minikura利用規約に同意する<a href="https://minikura.com/use_agreement/" target="_blank" class="dsn-link-terms"><i class="fa fa-chevron-circle-right"></i> 利用規約</a>
              </label>
            </label>
          <?php echo $this->Flash->render('remember');?>
          <span id="js-remember_validation" style="display:none;">利用規約にチェックしてください。</span>
        </div>
      </div>
  </section>
  <section class="dsn-nextback">
    <a href="/first_order/add_order" class="dsn-btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a>
    <div class="submit_disabled_wrapper_parent">
      <button type="submit" class="btn-next agree-submit js-btn-submit" formnovalidate>確認へ <i class="fa fa-chevron-circle-right"></i></button>
      <div id="js-submit_disabled_wrapper" class="submit_disabled_wrapper active"></div>
    </div>
  </section>
  </form>
</div>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>"></script>
<script src="/first_order_file/js/first_order/add_amazon_pay.js"></script>

<?php echo $this->element('FirstOrder/last'); ?>
