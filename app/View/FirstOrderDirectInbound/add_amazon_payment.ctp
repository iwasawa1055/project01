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
    <form method="post" action="/first_order_direct_inbound/confirm_amazon_pay" novalidate>
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
              <label class="dsn-cargo-selected"><input type="radio" name="cargo" value="ヤマト運輸" id="yamato"  <?php if ( CakeSession::read('Address.cargo') === "ヤマト運輸" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="yamato" class="dsn-cargo-select"> ヤマト運輸に取りに来てもらう</label></label>
            <div class="dsn-yamato">
              <div class="dsn-form">
                <label>集荷希望日<span class="dsn-required">※</span></label>
                <select class="dsn-select-delivery focused" name="date_cd" id="InboundDayCd">
                  <?php foreach ( CakeSession::read('Address.select_delivery_day_list') as $key => $value ) {?>
                  <option value="<?php echo $value->date_cd;?>"<?php if ( $value->date_cd === CakeSession::read('Address.date_cd') ) echo " selected";?>><?php echo $value->text;?></option>
                  <?php } ?>
                </select>
                <br>
                <?php echo $this->Flash->render('date_cd');?>
                <input type="hidden" name="select_delivery_day" id="select_delivery_day" value="<?php if (!empty(CakeSession::read('Address.select_delivery_day'))) : ?><?php echo h(CakeSession::read('Address.select_delivery_day'))?><?php else : ?><?php endif; ?>">
              </div>
              <div class="dsn-form">
                <label>集荷希望時間<span class="dsn-required">※</span></label>
                <select class="dsn-select-delivery focused" name="time_cd" id="InboundTimeCd">
                  <?php foreach ( CakeSession::read('Address.select_delivery_time_list') as $key => $value ) {?>
                  <option value="<?php echo $value->time_cd;?>"<?php if ( $value->time_cd === CakeSession::read('Address.time_cd') ) echo " selected";?>><?php echo $value->text;?></option>
                  <?php } ?>
                </select>
                <br>
                <?php echo $this->Flash->render('time_cd');?>
                <input type="hidden" name="select_delivery_time" id="select_delivery_time" value="<?php if (!empty(CakeSession::read('Address.select_delivery_time'))) : ?><?php echo h(CakeSession::read('Address.select_delivery_time'))?><?php else : ?><?php endif; ?>">
              </div>
            </div>
            <label class="dsn-cargo-selected"><input type="radio" name="cargo" value="着払い" id="arrival" <?php if ( CakeSession::read('Address.cargo') === "着払い" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="arrival" class="dsn-cargo-select"> 自分で送る（持ち込みで着払い）</label></label>
            <p class="dsn-arrival">着払いをご選択の場合はminikura運営事務局よりご連絡を差し上げます。<br> ※注意事項
              <br> ご連絡時のメールに記載する住所へ、ヤマト運輸の着払いでお送りください。
              <br> コンビニやヤマト営業所への持ち込みとなります。
            </p>
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
            <br>
            <?php echo $this->Flash->render('birth');?>
          </div>
          <div class="dsn-form dsn-form-line">
            <label>性別<span class="dsn-required">※</span></label>
            <label class="dsn-genders"><input type="radio" name="gender" value="m" id="man"<?php if ( CakeSession::read('Email.gender') === "m" ) echo " CHECKED";?>><span class="check-icon"></span><label for="man" class="dsn-gender">男</label></label>
            <label class="dsn-genders"><input type="radio" name="gender" value="f" id="woman"<?php if ( CakeSession::read('Email.gender') === "f" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="woman" class="dsn-gender">女</label></label>
            <br>
            <?php echo $this->Flash->render('gender');?>
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
            <label class="dsn-terms"><input type="checkbox" class="dsn-term focused" id="term" name="remember" value="Remember Me"><span class="check-icon"></span> 
              <label for="term" class="dsn-term">minikura利用規約に同意する<a href="https://minikura.com/use_agreement/" target="_blank" class="dsn-link-terms"><i class="fa fa-chevron-circle-right"></i> 利用規約</a></label>
            </label>
            <?php echo $this->Flash->render('remember');?>
            <span id="js-remember_validation" style="display:none;">利用規約にチェックしてください。</span>
          </div>
        </div>
      </section>
      <section class="dsn-nextback">
        <button href="#" class="dsn-btn-next js-btn-submit">最後の確認へ <i class="fa fa-chevron-circle-right"></i></button>
      </section>
    </form>
  </div>


<?php echo $this->element('FirstOrderDirectInbound/footer'); ?>
<?php echo $this->element('FirstOrderDirectInbound/js'); ?>
<script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>"></script>
<script src="/first_order_direct_inbound_file/js/first_order_direct_inbound/add_amazon_payment.js"></script>
<script src="/first_order_direct_inbound_file/js/dsn-mybox.js"></script>

<?php echo $this->element('FirstOrderDirectInbound/last'); ?>
