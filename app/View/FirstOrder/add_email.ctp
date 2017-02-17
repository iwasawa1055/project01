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
  <form method="post" action="/FirstOrder/confirm_email">
  <section id="adress">
    <div class="wrapper">
      <?php if ($is_logined) : ?>
      <div class="form">
        <label>メールアドレス</label>
        <p><?php echo $Email['email'];?></p>
      </div>
      <?php else : ?>
        <div class="form">
          <label>メールアドレス<span class="required">※</span><br><span>半角英数記号でご入力ください。</span></label>
          <input type="email" class="mail" placeholder="terrada@minikura.com" size="28" maxlength="50" name="email" value="<?php echo CakeSession::read('Email.email');?>" required>
          <?php echo $this->Flash->render('email');?>
          <?php echo $this->Flash->render('check_email');?>
        </div>
      <?php if (CakeSession::read('registered')) : ?>
        <div class="form">
          <label>ログインしサービスをご利用ください<br></label>
          <a class="login" href="/login">ログイン</a>
        </div>
        <?php endif; ?>
        <div class="form">
          <label>パスワード<span class="required">※</span><br><span>半角英数記号8文字以上でご入力ください。</span></label>
          <input type="password" class="password" size="20" maxlength="20" name="password" required>
          <?php echo $this->Flash->render('password');?>
        </div>
        <div class="form">
          <label>パスワード（確認用）<span class="required">※</span></label>
          <input type="password" class="password" size="20" maxlength="20" name="password_confirm" required>
          <?php echo $this->Flash->render('password_confirm');?>
        </div>
      <?php endif; ?>

      <div class="divider"></div>
      <div class="form form-line">
        <label>生年月日<span class="required">※</span></label>
	
        <select class="select-birth-year" name="birth_year" required>
        <?php for ($i = date('Y'); $i >= $login_config['birthyear_start']; $i--):?>
          <option value="<?php echo $i;?>"<?php if ( $i === (int) CakeSession::read('Email.birth_year') ) echo " SELECTED";?>><?php echo $i;?>年</option>
        <?php endfor;?>
        </select>
        <select class="select-birth-month" name="birth_month" required>
        <?php for ($i = 1; $i <= 12; $i++):?>
          <option value="<?php echo $i;?>"<?php if ( $i === (int) CakeSession::read('Email.birth_month') ) echo " SELECTED";?>><?php echo $i;?>月</option>
        <?php endfor;?>
        </select>
        <select class="select-birth-day" name="birth_day" required>
        <?php for ($i = 1; $i <= 31; $i++):?>
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
          <select class="select-info" name="newsletter" required>
            <option value="1">受信する</option>
            <option value="0">受信しない</option>
          </select>
        </div>
        <div class="form form-line">
          <label>紹介コード</label>
          <input type="text" size="20" maxlength="20" name="alliance_cd" value="<?php echo CakeSession::read('Email.alliance_cd');?>">
        </div>
        <div class="divider"></div>
      <?php endif; ?>

      <div class="form">
        <label class="terms"><input type="checkbox" class="term" id="term" name="remember" value="Remember Me"<?php if ( CakeSession::read('Email.remember') === "Remember Me" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="term" class="term">minikura利用規約に同意する</label></label>
        <?php echo $this->Flash->render('remember');?>
        <a href="https://minikura.com/use_agreement/" target="_blank" class="link-terms"><i class="fa fa-chevron-circle-right"></i> minikura利用規約</a>
      </div>
    </div>
  </section>
  <section class="nextback"><a href="/first_order/add_credit?back=true" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a><button type="submit" class="btn-next">最後の確認へ <i class="fa fa-chevron-circle-right"></i></button>
  </section>
  </form>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<?php echo $this->element('FirstOrder/last'); ?>
