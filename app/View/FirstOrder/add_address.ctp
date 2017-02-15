<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>お届け先住所入力 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>
  <section id="pagenation">
    <ul>
      <li><i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
      </li>
      <li class="on"><i class="fa fa-pencil-square-o"></i><span>お届け先<br>登録</span>
      </li>
      <li><i class="fa fa-credit-card"></i><span>カード<br>登録</span>
      </li>
      <li><i class="fa fa-envelope"></i><span>メール<br>登録</span>
      </li>
      <li><i class="fa fa-check"></i><span>確認</span>
      </li>
      <li><i class="fa fa-truck"></i><span>完了</span>
      </li>
    </ul>
  </section>
  <!-- ADRESS -->
  <form method="post" action="/FirstOrder/confirm_address">
  <section id="adress">
    <div class="wrapper">
      <div class="form">
        <label>お名前<span class="required">※</span></label>
        <input type="text" name="lastname" class="name-last" placeholder="寺田" size="10" maxlength="30" value="<?php echo $Address['lastname'];?>" required><input type="text" name="firstname" class="name-first" placeholder="太郎" size="10" maxlength="30" value="<?php echo $Address['firstname'];?>" required>
        <?php echo $this->Flash->render('lastname'); ?>
        <?php echo $this->Flash->render('firstname'); ?>
      </div>
      <div class="form">
        <label>フリガナ<span class="required">※</span></label>
        <input type="text" name="lastname_kana" class="name-last-kana" placeholder="テラダ" size="10" maxlength="30" value="<?php echo $Address['lastname_kana'];?>" required><input type="text" name="firstname_kana" class="name-first-kana" placeholder="タロウ" size="10" maxlength="30" value="<?php echo $Address['firstname_kana'];?>" required>
        <?php echo $this->Flash->render('lastname_kana'); ?>
        <?php echo $this->Flash->render('firstname_kana'); ?>
      </div>
      <div class="divider"></div>
      <div class="form">
        <label>郵便番号<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。<br>入力すると以下の住所が自動で入力されます。</span></label>
        <input type="text" name="postal" class="postal search_address_postal" placeholder="0123456" size="8" maxlength="8" value="<?php echo $Address['postal'];?>" required>
        <?php echo $this->Flash->render('postal');?>
      </div>
      <div class="form">
        <label>都道府県<span class="required">※</span></label>
        <input type="text" name="pref" class="adress1 address_pref" placeholder="東京都" size="28" maxlength="50" value="<?php echo $Address['pref'];?>" required>
        <?php echo $this->Flash->render('pref');?>
      </div>
      <div class="form">
        <label>住所<span class="required">※</span></label>
        <input type="text" name="address1" class="adress1 address_address1" placeholder="品川区" size="28" maxlength="50" value="<?php echo $Address['address1'];?>" required>
        <?php echo $this->Flash->render('address1');?>
      </div>
      <div class="form">
        <label>番地<span class="required">※</span></label>
        <input type="text" name="address2" class="adress2 address_address2" placeholder="東品川2-2-28" size="28" maxlength="50" value="<?php echo $Address['address2'];?>" required>
        <?php echo $this->Flash->render('address2');?>
      </div>
      <div class="form">
        <label>建物名</label>
        <input type="text" name="address3" class="build" placeholder="Tビル" size="28" maxlength="50" value="<?php echo $Address['address3'];?>">
        <?php echo $this->Flash->render('address3');?>
      </div>
      <div class="divider"></div>
      <div class="form">
        <label>電話番号<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input type="text" name="tel1" class="tel" placeholder="01234567890" size="15" maxlength="15" value="<?php echo $Address['tel1'];?>" required>
        <?php echo $this->Flash->render('tel1');?>
      </div>
      <div class="divider"></div>
      <div class="form">
        <label>お届け希望日<span class="required">※</span></label>
        <select name="datetime_cd" class="select-delivery" required>
          <option value="">0000年00月00日 午前中</option>
          <option value="">0000年00月00日 12時〜</option>
          <option value="">0000年00月00日 14時〜</option>
          <option value="">0000年00月00日 16時〜</option>
          <option value="">0000年00月00日 18時〜</option>
        </select>
        <?php echo $this->Flash->render('datetime_cd');?>
      </div>
    </div>
  </section>
  <section class="nextback"><a href="/first_order/add_order?back=true" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a><button type="submit" class="btn-next">クレジットカード情報を入力<i class="fa fa-chevron-circle-right"></i></button>  </section>
  </form>

<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?libraries=places"></script>
<script type="text/javascript" src="/js/minikura/address.js"></script>
<?php echo $this->element('FirstOrder/last'); ?>
