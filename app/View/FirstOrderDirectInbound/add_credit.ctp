<?php echo $this->element('FirstOrderDirectInbound/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>クレジットカード情報入力 - minikura</title>
<?php echo $this->element('FirstOrderDirectInbound/header'); ?>
<?php echo $this->element('FirstOrderDirectInbound/nav'); ?>
<?php echo $this->element('FirstOrderDirectInbound/breadcrumb_list'); ?>
  <!-- ADRESS -->
  <form method="post" action="/first_order_direct_inbound/confirm_credit" novalidate>
  <section id="dsn-adress">
    <div class="dsn-wrapper">
      <div class="dsn-form">
        <label>クレジットカード番号<span class="dsn-required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input type="tel" class="dsn-name focused" name="card_no" placeholder="0000-0000-0000-0000" size="20" maxlength="20" value="<?php echo CakeSession::read('Credit.card_no');?>">
        <br>
        <?php echo $this->Flash->render('card_no');?>
      </div>
      <div class="dsn-form">
        <label>セキュリティコード<span class="dsn-required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input type="tel" class="dsn-postal focused" name="security_cd" placeholder="0123" size="6" maxlength="6" value="">
        <?php echo $this->Flash->render('security_cd');?>
      </div>
      <div class="dsn-form">
        <label>カード有効期限<span class="dsn-required">※</span></label>
        <select class="dsn-select-month focused" name="expire_month">
          <?php foreach ( $this->Html->creditcardExpireMonth() as $value => $string ) :?>
                <option value="<?php echo $value;?>"<?php if ( $value === substr(CakeSession::read('Credit.expire'),0,2) ) echo " SELECTED";?>><?php echo $string;?></option>
          <?php endforeach ?>
        </select>
        /
        <select class="dsn-select-year focused" name="expire_year">
          <?php foreach ( $this->Html->creditcardExpireYear() as $value => $string ) :?>
                <option value="<?php echo $value;?>"<?php if ( (string) $value === substr(CakeSession::read('Credit.expire'),2,2) ) echo " SELECTED";?>><?php echo $string;?></option>
          <?php endforeach ?>
        </select>
        <br>
        <?php echo $this->Flash->render('expire');?>
      </div>
      <div class="dsn-form">
        <label>カード名義<span class="dsn-required">※</span></label>
        <input type="url" class="dsn-adress2 holder_name focused" name="holder_name" placeholder="TERRADA MINIKURA" size="28" maxlength="30" value="<?php echo CakeSession::read('Credit.holder_name');?>" novalidate>
        <?php echo $this->Flash->render('holder_name');?>
      </div>
      <div class="dsn-form">
        <a href="https://minikura.com/privacy_case/" target="_blank" class="link-terms"><i class="fa fa-chevron-circle-right"></i> クレジットカード情報の取り扱いについて</a>
      </div>
    </div>
  </section>
  <section class="dsn-nextback"><a href="/first_order_direct_inbound/add_address?back=true" class="dsn-btn-back">
    <i class="fa fa-chevron-circle-left"></i> 戻る</a><button type="submit" class="dsn-btn-next" formnovalidate>メールアドレスを入力<i class="fa fa-chevron-circle-right"></i></button>
  </section>
  </form>
<?php echo $this->element('FirstOrderDirectInbound/footer'); ?>
<?php echo $this->element('FirstOrderDirectInbound/js'); ?>
<?php echo $this->element('FirstOrderDirectInbound/last'); ?>
