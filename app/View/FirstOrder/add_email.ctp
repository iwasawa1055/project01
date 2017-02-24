<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>メールアドレス・パスワード入力 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>
  <section id="pagenation">
    <ul>
      <li><i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
      </li>
      <li><i class="fa fa-pencil-square-o"></i><span>お届け先<br>登録</span>
      </li>
      <li><i class="fa fa-credit-card"></i><span>カード<br>登録</span>
      </li>
      <li class="on"><i class="fa fa-envelope"></i><span>メール<br>登録</span>
      </li>
      <li><i class="fa fa-check"></i><span>確認</span>
      </li>
      <li><i class="fa fa-truck"></i><span>完了</span>
      </li>
    </ul>
  </section>
  <!-- ADRESS -->
  <?php if (!is_null(CakeSession::read('registered_user_login_url'))) : ?>
    <section id="adress">
      <div class="wrapper">
        <div class="form">
          <div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> このメールアドレスはご利用できません。すでにアカウントをお持ちの方は<a class="login" href="<?php echo CakeSession::read('registered_user_login_url') ?>">ログインページ</a>よりログインしてください。</div>
          </div>
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
          <input type="email" class="mail" placeholder="terrada@minikura.com" size="28" maxlength="50" name="email" value="<?php echo CakeSession::read('Email.email');?>">
          <?php echo $this->Flash->render('email');?>
          <?php echo $this->Flash->render('check_email');?>
        </div>
        <div class="form">
          <label>パスワード<span class="required">※</span><br><span>半角英数記号6文字以上でご入力ください。</span></label>
          <input type="password" class="password" size="20" maxlength="20" name="password">
          <?php echo $this->Flash->render('password');?>
        </div>
        <div class="form">
          <label>パスワード（確認用）<span class="required">※</span></label>
          <input type="password" class="password" size="20" maxlength="20" name="password_confirm">
          <?php echo $this->Flash->render('password_confirm');?>
        </div>
      <?php endif; ?>

      <div class="divider"></div>
      <div class="form form-line">
        <label>生年月日<span class="required">※</span></label>
	
        <select class="select-birth-year" name="birth_year">
        <?php for ($i = date('Y'); $i >= $birthyear_configure['birthyear_start']; $i--) :?>
          <option value="<?php echo $i;?>"<?php if ( $i === (int) CakeSession::read('Email.birth_year') ) echo " SELECTED";?>><?php echo $i;?>年</option>
        <?php endfor;?>
        </select>
        <select class="select-birth-month" name="birth_month">
        <?php for ($i = 1; $i <= 12; $i++) :?>
          <option value="<?php echo $i;?>"<?php if ( $i === (int) CakeSession::read('Email.birth_month') ) echo " SELECTED";?>><?php echo $i;?>月</option>
        <?php endfor;?>
        </select>
        <select class="select-birth-day" name="birth_day">
        <?php for ($i = 1; $i <= 31; $i++) :?>
          <option value="<?php echo $i;?>"<?php if ( $i === (int) CakeSession::read('Email.birth_day') ) echo " SELECTED";?>><?php echo $i;?>日</option>
        <?php endfor;?>
        </select>
        <?php echo $this->Flash->render('birth');?>
      </div>
      <div class="form form-line">
        <label>性別<span class="required">※</span></label>
        <label class="genders"><input type="radio" name="gender" value="m" id="man"<?php if ( CakeSession::read('Email.gender') === "m" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="man" class="gender">男</label></label><label class="genders"><input type="radio" name="gender" value="f" id="woman"<?php if ( CakeSession::read('Email.gender') === "f" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="woman" class="gender">女</label></label>
        <?php echo $this->Flash->render('gender');?>
      </div>
      <div class="divider"></div>

      <?php if (!$is_logined) : ?>
        <div class="form form-line">
          <label>お知らせメール</label>
          <select class="select-info" name="newsletter">
            <option value="1"<?php if ( CakeSession::read('Email.newsletter') === "1" ) echo " SELECTED";?>>受信する</option>
            <option value="0"<?php if ( CakeSession::read('Email.newsletter') === "0" ) echo " SELECTED";?>>受信しない</option>
          </select>
        </div>
        <div class="form form-line">
          <label>紹介コード</label>
          <input type="text" size="20" maxlength="20" name="alliance_cd" value="<?php echo CakeSession::read('Email.alliance_cd');?>">
          <?php echo $this->Flash->render('code_and_starter_kit');?>
        </div>
      <?php if (CakeSession::read('code_and_starter_kit') === true) : ?>
        <div class="form form-line">
          <label class="text-danger">monoスターターキットを購入する場合、紹介コードをご利用できません。 <br>
          紹介コードを使用しない（入力欄を空欄にする）か、再度ボックス選択画面からご注文ください。
          </label>
          <a  href="/first_order/index?option=all">ボックス選択ページへ</a>
        </div>
      <?php endif; ?>

        <div class="divider"></div>
      <?php endif; ?>

      <div class="form">
        <label class="terms"><input type="checkbox" class="term agree-before-submit" id="term" name="remember" value="Remember Me"><span class="check-icon"></span> <label for="term" class="term">minikura利用規約に同意する</label></label>
        <?php echo $this->Flash->render('remember');?>
        <a href="https://minikura.com/use_agreement/" target="_blank" class="link-terms"><i class="fa fa-chevron-circle-right"></i> minikura利用規約</a>
      </div>
    </div>
  </section>
  <section class="nextback" id="js-agreement_on_page">
    <a href="/first_order/add_credit?back=true" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a>
    <button type="submit" class="btn-next">最後の確認へ <i class="fa fa-chevron-circle-right"></i></button>
  </section>
  </form>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<script src="/first_order_file/js/first_order/add_email.js"></script>
<?php echo $this->element('FirstOrder/last'); ?>
