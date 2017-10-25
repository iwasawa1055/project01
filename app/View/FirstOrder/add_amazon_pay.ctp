<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<link href="/first_order_file/css/dsn-register.css" rel="stylesheet">
<link href="/first_order_file/css/first_order/add_amazon_pay_dev.css" rel="stylesheet">
<link href="/css/dsn-amazon-pay.css" rel="stylesheet">
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
      <div class="dsn-wrapper dev-wrapper">
        <div class="dsn-form">
          <?php echo $this->Flash->render('customer_regist_info');?>
          <?php echo $this->Flash->render('customer_amazon_pay_info');?>
          <?php echo $this->Flash->render('customer_address_info');?>
          <?php echo $this->Flash->render('customer_kit_info');?>
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
        <?php if (CakeSession::read('FirstOrder.regist_user_complete') === true) : ?>

        <input type="hidden" id="amazon_billing_agreement_id" value="<?php echo CakeSession::read('FirstOrder.amazon_pay.amazon_billing_agreement_id'); ?>">
        <input type="hidden" id="regist_user_flg" value="1">
        <div class="dsn-form dsn-form-line">
          <label>お届け希望日<span class="dsn-required">※</span></label>
          <input type="hidden" id="js-datetime_cd" value="<?php echo CakeSession::read('Address.datetime_cd');?>">
          <select class="dsn-select-delivery focused" id="datetime_cd" name="datetime_cd">
            <option value="">以下からお選びください</option>
          </select>
          <?php echo $this->Flash->render('datetime_cd');?>
        </div>

        <?php /* 会員登録が未完了の場合は、入力フォームを表示とする. */?>
        <?php else: ?>

        <input type="hidden" id="regist_user_flg" value="0">
        <input type="hidden" id="amazon_billing_agreement_id" value="">

        <div class="dsn-divider dev-divider"></div>
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
          <label>お届け希望日<span class="dsn-required">※</span></label>
          <input type="hidden" id="js-datetime_cd" value="<?php echo CakeSession::read('Address.datetime_cd');?>">
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
        </div>
        <div class="dsn-form dsn-form-line">
          <label>紹介コード</label>
          <input class="dsn-referral focused" type="text" size="20" maxlength="20" name="alliance_cd">
        </div>
        <br>
        <div class="dsn-form">
          <?php echo $this->Flash->render('newsletter');?>
          <?php echo $this->Flash->render('alliance_cd');?>
        </div>
        <div class="dsn-divider"></div>
        <div class="dsn-form">
          <label class="dsn-terms">
            <input type="checkbox" class="dsn-term agree-before-submit focused" id="term" name="remember" value="Remember Me"><span class="check-icon"></span>
              <label for="term" class="dsn-term"><a href="https://minikura.com/use_agreement/" target="_blank">minikura利用規約</a>に同意する
              </label>
            </label>
          <?php echo $this->Flash->render('remember');?>
          <span id="js-remember_validation" style="display:none;">利用規約にチェックしてください。</span>
        </div>

        <?php endif; ?>

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
<script src="/js/jquery.airAutoKana.js"></script>
<script src="/first_order_file/js/first_order/add_amazon_pay.js"></script>

<?php echo $this->element('FirstOrder/last'); ?>
