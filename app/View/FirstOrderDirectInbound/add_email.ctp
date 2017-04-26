<?php echo $this->element('FirstOrderDirectInbound/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>メールアドレス・パスワード入力 - minikura</title>
<?php echo $this->element('FirstOrderDirectInbound/header'); ?>
<?php echo $this->element('FirstOrderDirectInbound/nav'); ?>
<?php echo $this->element('FirstOrderDirectInbound/breadcrumb_list'); ?>
  <!-- ADRESS -->

  <form method="post" action="/first_order_direct_inbound/confirm_email" novalidate>
    <section id="dsn-adress">
      <?php if (!is_null(CakeSession::read('registered_user_login_url'))) : ?>
      <div class="dsn-form">
        <div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> このメールアドレスはご利用できません。すでにアカウントをお持ちの方は<a class="login" href="<?php echo CakeSession::read('registered_user_login_url') ?>">ログインページ</a>よりログインしてください。</div>
      </div>
      <?php endif; ?>

      <div class="dsn-wrapper">
      <?php if ($is_logined) : ?>
        <div class="dsn-form">
        <label>メールアドレス</label>
        <p><?php echo CakeSession::read('Email.email'); ?></p>
      </div>
      <?php else : ?>
        <div class="dsn-form">
          <label>メールアドレス<span class="dsn-required">※</span><br><span>半角英数記号でご入力ください。</span></label>
          <input type="url" class="dsn-mail focused" placeholder="terrada@minikura.com" size="28" maxlength="50" name="email" value="<?php echo CakeSession::read('Email.email');?>" novalidate>
          <?php echo $this->Flash->render('email');?>
          <?php echo $this->Flash->render('check_email');?>
        </div>
        <div class="dsn-form">
          <label>パスワード<span class="dsn-required">※</span><br><span>半角英数記号6文字以上でご入力ください。</span></label>
          <input type="password" class="dsn-password focused" size="20" maxlength="20" name="password">
          <?php echo $this->Flash->render('password');?>
        </div>
        <div class="dsn-form">
          <label>パスワード（確認用）<span class="dsn-required">※</span></label>
          <input type="password" class="dsn-password focused" size="20" maxlength="20" name="password_confirm">
          <?php echo $this->Flash->render('password_confirm');?>
        </div>
      <?php endif; ?>

      <div class="dsn-divider"></div>
      <div class="dsn-form dsn-form-line">
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
      <div class="dsn-divider"></div>
      <div class="dsn-form dsn-form-line">
        <label>性別<span class="dsn-required">※</span></label>
        <label class="dsn-genders"><input type="radio" name="gender" value="m" id="man"<?php if ( CakeSession::read('Email.gender') === "m" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="man" class="dsn-gender">男</label></label>
        <label class="dsn-genders"><input type="radio" name="gender" value="f" id="woman"<?php if ( CakeSession::read('Email.gender') === "f" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="woman" class="dsn-gender">女</label></label>
        <br>
        <?php echo $this->Flash->render('gender');?>
      </div>
      <div class="dsn-divider"></div>

      <?php if (!$is_logined) : ?>
        <div class="dsn-form dsn-form-line">
          <label>お知らせメール</label>
          <select class="dsn-select-info focused" name="newsletter">
            <option value="1"<?php if ( CakeSession::read('Email.newsletter') === "1" ) echo " SELECTED";?>>受信する</option>
            <option value="0"<?php if ( CakeSession::read('Email.newsletter') === "0" ) echo " SELECTED";?>>受信しない</option>
          </select>
        </div>
      <?php endif; ?>
      <?php if ($display_alliance_cd) : ?>
        <div class="dsn-form dsn-form-line">
          <label>紹介コード</label>
          <input type="url" class="dsn-referral focused" size="20" maxlength="20" name="alliance_cd" value="<?php echo CakeSession::read('Email.alliance_cd');?>">
          <br><?php echo $this->Flash->render('alliance_cd');?>
        </div>
      <?php endif; ?>

      <div class="dsn-divider"></div>

      <div class="dsn-form">
        <label class="dsn-terms"><input type="checkbox" class="dsn-term agree-before-submit focused" id="term" name="remember" value="Remember Me"><span class="check-icon"></span>
          <label for="term" class="dsn-term select_agreement">minikura利用規約に同意する<a href="https://minikura.com/use_agreement/" target="_blank" class="link-terms"><i class="fa fa-chevron-circle-right"></i> 利用規約</a></label>
        </label>
        <?php echo $this->Flash->render('remember');?>
        <span id="js-remember_validation" style="display:none;">利用規約にチェックしてください。</span>
      </div>
    </div>
  </section>
  <section class="dsn-nextback" id="js-agreement_on_page">
    <a href="/first_order_direct_inbound/add_credit?back=true" class="dsn-btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a>
    <div class="submit_disabled_wrapper_parent">
      <button type="submit" class="dsn-btn-next agree-submit" formnovalidate>最後の確認へ <i class="fa fa-chevron-circle-right"></i></button>
      <div id="js-submit_disabled_wrapper" class="submit_disabled_wrapper active"></div>
    </div>
  </section>
  </form>
<?php echo $this->element('FirstOrderDirectInbound/footer'); ?>
<?php echo $this->element('FirstOrderDirectInbound/js'); ?>
<script src="/first_order_direct_inbound_file/js/first_order_direct_inbound/add_email.js"></script>
<?php echo $this->element('FirstOrderDirectInbound/last'); ?>
