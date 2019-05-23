<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>お届け先住所入力 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>
<?php echo $this->element('FirstOrder/breadcrumb_list'); ?>
  <!-- ADRESS -->
  <form method="post" action="/first_order/confirm_address" novalidate>
  <section id="adress">
    <div class="wrapper">
      <div class="form">
        <label>お名前<span class="required">※</span></label>
        <input type="text" name="lastname" class="name-last lastname focused" placeholder="寺田" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.lastname');?>">
        <input type="text" name="firstname" class="name-first firstname focused" placeholder="太郎" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.firstname');?>">
        <br>
        <?php echo $this->Flash->render('lastname'); ?>
        <?php echo $this->Flash->render('firstname'); ?>
      </div>
      <div class="form">
        <label>フリガナ<span class="required">※</span></label>
        <input type="text" name="lastname_kana" class="name-last-kana lastname_kana focused" placeholder="テラダ" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.lastname_kana');?>">
        <input type="text" name="firstname_kana" class="name-first-kana firstname_kana focused" placeholder="タロウ" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.firstname_kana');?>">
        <br>
        <?php echo $this->Flash->render('lastname_kana'); ?>
        <?php echo $this->Flash->render('firstname_kana'); ?>
      </div>
      <div class="divider"></div>
      <div class="form">
        <label>郵便番号<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。<br>入力すると以下の住所が自動で入力されます。</span></label>
        <input type="tel" name="postal" id="postal" class="postal search_address_postal focused" placeholder="0123456" size="8" maxlength="8" value="<?php echo CakeSession::read('Address.postal');?>">
        <?php echo $this->Flash->render('postal');?>
      </div>
      <div class="form">
        <label>都道府県<span class="required">※</span></label>
        <input type="text" name="pref" class="adress1 address_pref focused" placeholder="東京都" size="28" value="<?php echo CakeSession::read('Address.pref');?>">
        <?php echo $this->Flash->render('pref');?>
      </div>
      <div class="form">
        <label>市区郡<span class="required">※</span></label>
        <input type="text" name="address1" class="adress1 address_address1 focused" placeholder="品川区" size="28" value="<?php echo CakeSession::read('Address.address1');?>">
        <?php echo $this->Flash->render('address1');?>
      </div>
      <div class="form">
        <label>町域以降<span class="required">※</span></label>
        <input type="text" name="address2" class="adress2 address_address2 focused" placeholder="東品川2-2-28" size="28" value="<?php echo CakeSession::read('Address.address2');?>">
        <?php echo $this->Flash->render('address2');?>
      </div>
      <div class="form">
        <label>建物名</label>
        <input type="text" name="address3" class="build focused" placeholder="Tビル" size="28" value="<?php echo CakeSession::read('Address.address3');?>">
        <?php echo $this->Flash->render('address3');?>
      </div>
      <div class="divider"></div>
      <div class="form">
        <label>電話番号<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input type="tel" name="tel1" class="tel focused" placeholder="01234567890" size="15" maxlength="15" value="<?php echo CakeSession::read('Address.tel1');?>">
        <?php echo $this->Flash->render('tel1');?>
      </div>
      <div class="divider"></div>
      <div class="form">
        <label>お届け希望日<span class="required ">※</span></label>
        <select name="datetime_cd" id="datetime_cd" class="select-delivery focused">
          <option value="">以下からお選びください</option>
          <?php if(CakeSession::read('Address.select_delivery_list')): ?>
            <?php foreach ( CakeSession::read('Address.select_delivery_list') as $key => $value ) {?>
            <option value="<?php echo $value->datetime_cd;?>"<?php if ( $value->datetime_cd === CakeSession::read('Address.datetime_cd') ) echo " selected";?>><?php echo $value->text;?></option>
            <?php } ?>
          <?php endif; ?>
        </select>
        <?php echo $this->Flash->render('datetime_cd');?>
        <input type="hidden" name="select_delivery" id="select_delivery" value="<?php if (!empty(CakeSession::read('Address.select_delivery'))) : ?><?php echo h(CakeSession::read('Address.select_delivery'))?><?php else : ?><?php endif; ?>">

      </div>
    </div>
  </section>
  <section class="nextback">
    <a href="/first_order/add_order?back=true" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a>
    <button type="submit" class="btn-next">メールアドレスを入力 <i class="fa fa-chevron-circle-right"></i></button>
  </section>
  </form>

<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=<?php echo Configure::read('app.googlemap.api.key'); ?>&libraries=places"></script>
<script src="/js/minikura/address.js"></script>
<script src="/js/jquery.airAutoKana.js"></script>
<script src="/first_order_file/js/first_order/add_address.js"></script>

<?php echo $this->element('FirstOrder/last'); ?>
