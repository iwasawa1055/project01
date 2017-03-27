<?php echo $this->element('FirstOrder/first_sneaker'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>クレジットカード情報入力 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav_sneaker'); ?>
<?php echo $this->element('FirstOrder/breadcrumb_list'); ?>
  <!-- ADRESS -->
  <form method="post" action="/first_order/confirm_credit" novalidate>
  <section id="adress">
    <div class="wrapper">
      <div class="form">
        <label>クレジットカード番号<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input type="tel" class="name focused" name="card_no" placeholder="0000-0000-0000-0000" size="20" maxlength="20" value="<?php echo CakeSession::read('Credit.card_no');?>">
        <?php echo $this->Flash->render('card_no');?>
      </div>
      <div class="form">
        <label>セキュリティコード<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input type="tel" class="postal focused" name="security_cd" placeholder="0123" size="6" maxlength="6" value="">
        <?php echo $this->Flash->render('security_cd');?>
      </div>
      <div class="form">
        <label>カード有効期限<span class="required">※</span></label>
        <select class="select-month focused" name="expire_month">
          <?php foreach ( $this->Html->creditcardExpireMonth() as $value => $string ) :?>
                <option value="<?php echo $value;?>"<?php if ( $value === substr(CakeSession::read('Credit.expire'),0,2) ) echo " SELECTED";?>><?php echo $string;?></option>
          <?php endforeach ?>
        </select>
        /
        <select class="select-year focused" name="expire_year">
          <?php foreach ( $this->Html->creditcardExpireYear() as $value => $string ) :?>
                <option value="<?php echo $value;?>"<?php if ( (string) $value === substr(CakeSession::read('Credit.expire'),2,2) ) echo " SELECTED";?>><?php echo $string;?></option>
          <?php endforeach ?>
        </select>
        <br>
        <?php echo $this->Flash->render('expire');?>
      </div>
      <div class="form">
        <label>カード名義<span class="required">※</span></label>
        <input type="url" class="adress2 holder_name focused" name="holder_name" placeholder="TERRADA MINIKURA" size="28" maxlength="30" value="<?php echo CakeSession::read('Credit.holder_name');?>" novalidate>
        <?php echo $this->Flash->render('holder_name');?>
      </div>
      <div class="form">
        <a href="https://minikura.com/privacy_case/" target="_blank" class="link-terms"><i class="fa fa-chevron-circle-right"></i> クレジットカード情報の取り扱いについて</a>
      </div>
    </div>
  </section>
  <section class="nextback"><a href="/first_order/add_address_sneaker" class="btn-back">
    <i class="fa fa-chevron-circle-left"></i> 戻る</a><button type="submit" class="btn-next" formnovalidate>メールアドレスを入力<i class="fa fa-chevron-circle-right"></i></button>
  </section>
  </form>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js_sneaker'); ?>
<?php echo $this->element('FirstOrder/last'); ?>
