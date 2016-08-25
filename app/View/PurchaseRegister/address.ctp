<?php $this->Html->script('https://maps.google.com/maps/api/js?libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/purchase_register', ['block' => 'scriptMinikura']); ?>
<section id="form">
  <div class="container narrow">
    <div>
      <h2>配送先住所を入力（2/5）</h2>
    </div>
    <div class="row">
      <div class="info">
        <div class="photo">
          <img src="<?php echo $sale_image; ?>" alt="" />
        </div>
        <div class="caption">
          <h3><?php echo h($sales_title); ?></h3>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form">
        <div class="address">
        <?php echo $this->Form->create('CustomerRegistInfo', ['url' => ['controller' => 'PurchaseRegister', 'action' => 'address'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <h4>お届け先の住所を入力してください。</h4>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerRegistInfo.postal', ['class' => "form-control search_address_postal", 'maxlength' => 8, 'placeholder'=>'郵便番号（入力すると以下の住所が自動で入力されます）', 'error' => false]); ?>
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
            <?php echo $this->Form->input('CustomerRegistInfo.birth_year', ['class' => "form-control", 'placeholder'=>'年（西暦）', 'maxlength' => 4, 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.birth_year', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerRegistInfo.birth_month', ['class' => "form-control date_zero_padding", 'placeholder'=>'月', 'maxlength' => 2, 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.birth_month', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->input('CustomerRegistInfo.birth_day', ['class' => "form-control date_zero_padding", 'placeholder'=>'日', 'maxlength' => 2, 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.birth_day', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <?php echo $this->Form->error('CustomerRegistInfo.birth', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <label>性別</label>
            <?php echo $this->Form->select('CustomerRegistInfo.gender', CUSTOMER_GENDER, ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.gender', null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group">
            <label>お届け希望日時</label>
            <?php echo $this->Form->select('CustomerRegistInfo.datetime_cd', $this->Order->setDatetime($datetime), ['class' => 'form-control', 'empty' => null, 'error' => false]); ?>
            <?php echo $this->Form->error('CustomerRegistInfo.datetime_cd', null, ['wrap' => 'p']) ?>
          </div>
          <div class="row">
            <div class="text-center btn-commit">
              <button type="submit" class="btn">クレジットカード情報の入力へ（3/5）</button>
            </div>
          </div>
        <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
  </div>
</section>
