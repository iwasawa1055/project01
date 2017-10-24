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
          <label for="term" class="dsn-term select_agreement"><a href="https://minikura.com/use_agreement/" target="_blank" class="dsn-link-terms">minikura利用規約</a>に同意する<a href="https://minikura.com/use_agreement/" target="_blank" class="link-terms"><i class="fa fa-chevron-circle-right"></i> 利用規約</a></label>
        </label>
        <?php echo $this->Flash->render('remember');?>
        <span id="js-remember_validation" style="display:none;">利用規約にチェックしてください。</span>
      </div>
    </div>
  </section>
  <section class="dsn-nextback" id="js-agreement_on_page">
    <a href="/first_order_direct_inbound/add_address?back=true" class="dsn-btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a>
    <div class="submit_disabled_wrapper_parent">
      <button type="submit" class="dsn-btn-next agree-submit" formnovalidate>注文内容の確認へ  <i class="fa fa-chevron-circle-right"></i></button>
      <div id="js-submit_disabled_wrapper" class="submit_disabled_wrapper active"></div>
    </div>
  </section>
  </form>
<?php echo $this->element('FirstOrderDirectInbound/footer'); ?>
<?php echo $this->element('FirstOrderDirectInbound/js'); ?>
<script src="/first_order_direct_inbound_file/js/first_order_direct_inbound/add_email.js"></script>
<?php echo $this->element('FirstOrderDirectInbound/last'); ?>
