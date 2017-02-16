<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>クレジットカード情報入力 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>
  <section id="pagenation">
    <ul>
      <li><i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
      </li>
      <li><i class="fa fa-pencil-square-o"></i><span>お届け先<br>登録</span>
      </li>
      <li class="on"><i class="fa fa-credit-card"></i><span>カード<br>登録</span>
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
  <form method="post" action="/FirstOrder/confirm_credit">
  <section id="adress">
    <div class="wrapper">
      <div class="form">
        <label>クレジットカード番号<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input type="text" class="name" name="card_no" placeholder="0000-0000-0000-0000" size="20" maxlength="20" value="<?php echo $Credit['card_no'];?>" required>
        <?php echo $this->Flash->render('card_no');?>
      </div>
      <div class="form">
        <label>セキュリティコード<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input type="text" class="postal" name="security_cd" placeholder="0123" size="6" maxlength="6" value="<?php echo $Credit['security_cd'];?>" required>
        <?php echo $this->Flash->render('security_cd');?>
      </div>
      <div class="form">
        <label>カード有効期限<span class="required">※</span></label>
        <select class="select-month" name="expire_month" required>
          <?php foreach ( $this->Html->creditcardExpireMonth() as $value=>$string ) {?>
                <option value="<?php echo $value;?>"<?php if ( $value === substr($Credit['expire'],0,2) ) echo " SELECTED";?>><?php echo $string;?></option>
          <?php } ?>
        </select>
        /
        <select class="select-year" name="expire_year" required>
          <?php foreach ( $this->Html->creditcardExpireYear() as $value=>$string ) {?>
                <option value="<?php echo $value;?>"<?php if ( (string) $value === substr($Credit['expire'],2,2) ) echo " SELECTED";?>><?php echo $string;?></option>
          <?php } ?>
        </select>
        <?php echo $this->Flash->render('expire');?>
      </div>
      <div class="form">
        <label>カード名義<span class="required">※</span></label>
        <input type="text" class="adress2" name="holder_name" placeholder="TERRADA MINIKURA" size="28" maxlength="30" value="<?php echo $Credit['holder_name'];?>" required>
        <?php echo $this->Flash->render('holder_name');?>
      </div>
    </div>
  </section>
  <section class="nextback"><a href="/first_order/add_address?back=true" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a><button type="submit" class="btn-next">メールアドレスを入力<i class="fa fa-chevron-circle-right"></i></button>
  </section>
  </form>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<?php echo $this->element('FirstOrder/last'); ?>
