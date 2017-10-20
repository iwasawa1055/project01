<?php echo $this->element('FirstOrder/first_sneaker'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>メールアドレス・パスワード入力 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav_sneaker'); ?>
<?php echo $this->element('FirstOrder/breadcrumb_list'); ?>
  <!-- ADRESS -->
  <?php if (!is_null(CakeSession::read('registered_user_login_url'))) : ?>
    <section id="adress">
        <div class="form">
          <div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> このメールアドレスはご利用できません。すでにアカウントをお持ちの方は<a class="login" href="<?php echo CakeSession::read('registered_user_login_url') ?>">ログインページ</a>よりログインしてください。</div>
        </div>
    </section>
  <?php endif; ?>

  <form method="post" action="/first_order/confirm_email" novalidate>
  <section id="adress">
    <div class="wrapper">
      <?php if ($is_logined) : ?>
      <div class="form">
        <label>メールアドレス</label>
        <p><?php echo CakeSession::read('Email.email'); ?></p>
      </div>
      <?php else : ?>
        <div class="form">
          <label>メールアドレス<span class="required">※</span><br><span>半角英数記号でご入力ください。</span></label>
          <input type="url" class="mail focused" placeholder="terrada@minikura.com" size="28" maxlength="50" name="email" value="<?php echo CakeSession::read('Email.email');?>" novalidate>
          <?php echo $this->Flash->render('email');?>
          <?php echo $this->Flash->render('check_email');?>
        </div>
        <div class="form">
          <label>パスワード<span class="required">※</span><br><span>半角英数記号6文字以上でご入力ください。</span></label>
          <input type="password" class="password focused" size="20" maxlength="20" name="password">
          <?php echo $this->Flash->render('password');?>
        </div>
        <div class="form">
          <label>パスワード（確認用）<span class="required">※</span></label>
          <input type="password" class="password focused" size="20" maxlength="20" name="password_confirm">
          <?php echo $this->Flash->render('password_confirm');?>
        </div>
      <?php endif; ?>

      <?php if (!$is_logined) : ?>
        <div class="form form-line">
          <label>お知らせメール</label>
          <select class="select-info focused" name="newsletter">
            <option value="1"<?php if ( CakeSession::read('Email.newsletter') === "1" ) echo " SELECTED";?>>受信する</option>
            <option value="0"<?php if ( CakeSession::read('Email.newsletter') === "0" ) echo " SELECTED";?>>受信しない</option>
          </select>
        </div>

      <?php endif; ?>
      <div class="divider"></div>

      <div class="form">
        <label class="terms"><input type="checkbox" class="term agree-before-submit focused" id="term" name="remember" value="Remember Me"><span class="check-icon"></span>
          <label for="term" class="term select_agreement">minikura利用規約に同意する<a href="https://minikura.com/use_agreement/" target="_blank" class="link-terms"><i class="fa fa-chevron-circle-right"></i> 利用規約</a></label>
        </label>
        <?php echo $this->Flash->render('remember');?>
        <span id="js-remember_validation" style="display:none;">利用規約にチェックしてください。</span>
      </div>
    </div>
  </section>
  <section class="nextback" id="js-agreement_on_page">
    <a href="/first_order/add_credit_sneaker" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a>
    <div class="submit_disabled_wrapper_parent">
      <button type="submit" class="btn-next agree-submit" formnovalidate>注文内容の確認へ <i class="fa fa-chevron-circle-right"></i></button>
      <div id="js-submit_disabled_wrapper" class="submit_disabled_wrapper active"></div>
    </div>
  </section>
  </form>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js_sneaker'); ?>
<script src="/first_order_file/js/first_order/add_email.js"></script>
<?php echo $this->element('FirstOrder/last'); ?>
