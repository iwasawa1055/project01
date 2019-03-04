<ul class="input-info add-credit">
  <input type="hidden" id="shop_id" value="<?php echo Configure::read('app.gmo.shop_id'); ?>">
  <li>
    <label class="headline">クレジットカード番号</label>
    <input autocomplete="cc-number" id="cardno" class="name focused" name="cardno" placeholder="例：1234-5678-1234-5678">
    <div class="dsn-form">
      <div id="error_cardno"></div>
      <?php echo $this->Flash->render('new_card_no');?>
    </div>
  </li>
  <li>
    <label class="headline">有効期限</label>
    <ul class="expiration">
      <li>
        <select class="dsn-select-month focused" name="expiremonth" id="expiremonth">
          <?php foreach ($this->Html->creditcardExpireMonth() as $value => $string) :?>
          <option value="<?php echo $value;?>"<?php if ( $value === substr(CakeSession::read('Credit.expire'),0,2) ) echo " SELECTED";?>><?php echo $string;?></option>
          <?php endforeach; ?>
        </select>
      </li>
      <li>
        <select class="dsn-select-year focused" name="expireyear" id="expireyear">
          <?php foreach ($this->Html->creditcardExpireYear() as $value => $string) :?>
          <option value="<?php echo $value;?>"<?php if ( (string) $value === substr(CakeSession::read('Credit.expire'),2,2) ) echo " SELECTED";?>><?php echo $string;?></option>
          <?php endforeach; ?>
        </select>
      </li>
    </ul>
  </li>
  <li>
    <label class="headline">クレジットカード名義</label>
    <input autocomplete="cc-name" id="holdername" name="holdername" placeholder="例：TARO TERRADA">
    <p class="txt-caption">（※半角大文字英字 半角スペース . - ・）</p>
  </li>
  <li>
    <label class="headline">セキュリティコード</label>
    <input autocomplete="cc-csc" id="securitycode" name="securitycode" placeholder="例：123">
    <p class="txt-caption">カード裏面に記載された3〜4桁の番号をご入力ください。</p>
    <div id="error_securitycode"></div>
    <?php echo $this->Flash->render('new_security_cd');?>
    <?php echo $this->Flash->render('buy_kit_security_cd_error');?>
  </li>
  <li>
    <?php echo $this->element('Order/securitycode'); ?>
  </li>
  <li>
    <?php echo $this->element('Order/creditcard'); ?>
  </li>
</ul>