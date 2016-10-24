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
        <?php echo $this->Form->create('CustomerInfo', ['url' => ['controller' => 'PurchaseEntryRegister', 'action' => 'address'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <h4>お届け先の住所を入力してください。</h4>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerInfo.postal', ['class' => "form-control search_address_postal", 'maxlength' => 8, 'placeholder'=>'郵便番号（入力すると以下の住所が自動で入力されます）', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.postal', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerInfo.pref', ['class' => "form-control address_pref", 'maxlength' => 4, 'placeholder'=>'都道府県', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.pref', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerInfo.address1', ['class' => "form-control address_address1", 'maxlength' => 8, 'placeholder'=>'住所', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.address1', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerInfo.address2', ['class' => "form-control address_address2", 'maxlength' => 18, 'placeholder'=>'番地', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.address2', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerInfo.address3', ['class' => "form-control", 'maxlength' => 30, 'placeholder'=>'建物名', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.address3', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerInfo.tel1', ['class' => "form-control", 'maxlength' => 20, 'placeholder'=>'電話番号', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.tel1', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerInfo.lastname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.lastname', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerInfo.lastname_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓（カナ）', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.lastname_kana', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerInfo.firstname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.firstname', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerInfo.firstname_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名（カナ）', 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.firstname_kana', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerInfo.birth_year', ['class' => "form-control", 'placeholder'=>'年（西暦）', 'maxlength' => 4, 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.birth_year', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerInfo.birth_month', ['class' => "form-control date_zero_padding", 'placeholder'=>'月', 'maxlength' => 2, 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.birth_month', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerInfo.birth_day', ['class' => "form-control date_zero_padding", 'placeholder'=>'日', 'maxlength' => 2, 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.birth_day', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->error('CustomerInfo.birth', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <label>性別</label>
            <?php echo $this->Form->select('CustomerInfo.gender', CUSTOMER_GENDER, ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.gender', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <label>お届け希望日時</label>
            <?php echo $this->Form->select('CustomerInfo.datetime_cd', $this->Order->setDatetime($datetime), ['class' => 'form-control', 'empty' => null, 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerInfo.datetime_cd', null, ['wrap' => 'p']) ?>
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
