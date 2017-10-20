<?php $this->Html->script('https://maps.google.com/maps/api/js?libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/purchase_register', ['block' => 'scriptMinikura']); ?>
<section id="form">
  <div class="container narrow">
    <div>
      <h2>配送先住所を入力（2/5）</h2>
    </div>
    <?php echo $this->element('purchase_item', ['sales' => $sales]); ?>
    <div class="row">
      <div class="form">
        <div class="address">
        <?php echo $this->Form->create('CustomerRegistInfo', ['url' => ['controller' => 'PurchaseRegister', 'action' => 'address'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <h4>お届け先の住所を入力してください。</h4>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerRegistInfo.postal', ['id' => "CustomerInfoPostal", 'class' => "form-control search_address_postal", 'maxlength' => 8, 'placeholder'=>'郵便番号（入力すると以下の住所が自動で入力されます）', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.postal', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerRegistInfo.pref', ['class' => "form-control address_pref", 'maxlength' => 4, 'placeholder'=>'都道府県', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.pref', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerRegistInfo.address1', ['class' => "form-control address_address1", 'maxlength' => 8, 'placeholder'=>'住所', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.address1', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerRegistInfo.address2', ['class' => "form-control address_address2", 'maxlength' => 18, 'placeholder'=>'番地', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.address2', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerRegistInfo.address3', ['class' => "form-control", 'maxlength' => 30, 'placeholder'=>'建物名', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.address3', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerRegistInfo.tel1', ['class' => "form-control", 'maxlength' => 20, 'placeholder'=>'電話番号', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.tel1', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerRegistInfo.lastname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.lastname', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerRegistInfo.lastname_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓（カナ）', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.lastname_kana', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerRegistInfo.firstname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.firstname', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerRegistInfo.firstname_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名（カナ）', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.firstname_kana', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <label>お届け希望日時</label>
            <?php echo $this->Form->select('CustomerRegistInfo.datetime_cd', $this->Order->setDatetime($datetime), ['id' => 'CustomerInfoDatetimeCd', 'class' => 'form-control', 'empty' => null, 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.datetime_cd', null, ['wrap' => 'p']) ?>
          </div>
          <div class="row">
            <div class="text-center">
              <button type="submit" class="btn commit">クレジットカード情報の入力へ（3/5）</button>
            </div>
          </div>
        <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
  </div>
</section>
