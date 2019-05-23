<ul class="add-address">
  <li>
    <label class="headline">郵便番号</label>
    <?php echo $this->Form->input('PaymentGMOKitByCreditCard.postal', ['id' => 'postal', 'class' => 'search_address_postal', 'type' => 'tel', 'placeholder'=>'例：140-0002', 'autocomplete' => "postal-code", 'error' => false, 'label' => false, 'div' => false]); ?>
    <p class="txt-caption">入力すると住所が自動で反映されます。</p>
    <?php echo $this->Form->error('PaymentGMOKitByCreditCard.postal', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
  </li>
  <li>
    <label class="headline">都道府県</label>
    <?php echo $this->Form->input('PaymentGMOKitByCreditCard.pref', ['id' => 'pref', 'class' => 'address_pref', 'type' => 'text', 'placeholder'=>'例：東京都', 'autocomplete' => "address-level1", 'error' => false, 'label' => false, 'div' => false]); ?>
    <?php echo $this->Form->error('PaymentGMOKitByCreditCard.pref', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
  </li>
  <li>
    <label class="headline">市区郡</label>
    <?php echo $this->Form->input('PaymentGMOKitByCreditCard.address1', ['id' => 'address1', 'class' => 'address_address1', 'type' => 'text', 'placeholder'=>'例：品川区', 'autocomplete' => "address-level2", 'error' => false, 'label' => false, 'div' => false]); ?>
    <?php echo $this->Form->error('PaymentGMOKitByCreditCard.address1', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
  </li>
  <li>
    <label class="headline">町域以降</label>
    <?php echo $this->Form->input('PaymentGMOKitByCreditCard.address2', ['id' => 'address2', 'class' => 'address_address2', 'type' => 'text', 'placeholder'=>'例：東品川2-6-10', 'autocomplete' => "address-line1", 'error' => false, 'label' => false, 'div' => false]); ?>
    <?php echo $this->Form->error('PaymentGMOKitByCreditCard.address2', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
  </li>
  <li>
    <label class="headline">建物名</label>
    <?php echo $this->Form->input('PaymentGMOKitByCreditCard.address3', ['id' => 'address3', 'type' => 'text', 'placeholder'=>'例：Tビル', 'autocomplete' => "address-line2", 'error' => false, 'label' => false, 'div' => false]); ?>
    <?php echo $this->Form->error('PaymentGMOKitByCreditCard.address3', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
  </li>
  <li>
    <label class="headline">電話番号</label>
    <?php echo $this->Form->input('PaymentGMOKitByCreditCard.tel1', ['id' => 'tel1', 'type' => 'tel', 'placeholder'=>'例：0312345678', 'autocomplete' => "tel", 'error' => false, 'label' => false, 'div' => false]); ?>
    <?php echo $this->Form->error('PaymentGMOKitByCreditCard.tel1', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
  </li>
  <li>
    <label class="headline">お名前<span class="required">※</span></label>
    <ul class="col-name">
      <li>
        <?php echo $this->Form->input('PaymentGMOKitByCreditCard.lastname', ['type' => 'text', 'placeholder'=>'例：寺田', 'size' => '10', 'maxlength' => '30', 'autocomplete' => "family-name", 'error' => false, 'label' => false, 'div' => false]); ?>
      </li>
      <li>
        <?php echo $this->Form->input('PaymentGMOKitByCreditCard.firstname', ['type' => 'text', 'placeholder'=>'例：太郎', 'size' => '10', 'maxlength' => '30', 'autocomplete' => "given-name", 'error' => false, 'label' => false, 'div' => false]); ?>
      </li>
    </ul>
    <?php echo $this->Form->error('PaymentGMOKitByCreditCard.lastname', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
    <?php echo $this->Form->error('PaymentGMOKitByCreditCard.firstname', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
  </li>
</ul>
<label class="input-check">
  <?php
    echo $this->Form->input(
      'PaymentGMOKitByCreditCard.insert_address_flag',
        [
          'class' => 'cb-square',
          'label' => false,
          'error' => false,
          'type' => 'checkbox',
          'div' => false,
          'hiddenField' => false,
        ]
      );
  ?>
  <span class="icon"></span><span class="label-txt">アドレスブックに登録する</span>
</label>