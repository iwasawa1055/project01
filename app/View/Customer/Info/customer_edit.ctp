<?php $this->Html->script('https://maps.google.com/maps/api/js?libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/address', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/customer_info', ['block' => 'scriptMinikura']); ?>
<?php if (!empty($validErrors)) { $this->validationErrors['CustomerInfo'] = $validErrors; } ?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-truck"></i> お客さま情報変更</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <?php echo $this->Form->create('CustomerInfo', ['url' => ['controller' => 'info', 'action' => $action, 'step' => 'confirm'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="col-lg-12">
                <h2>お客さま情報変更</h2>
                <p class="form-control-static col-lg-12">変更されるお客さまの情報をご入力してください。</p>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.postal', ['class' => "form-control search_address_postal", 'maxlength' => 8, 'placeholder'=>'郵便番号（入力すると以下の住所が自動で入力されます）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.postal', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.pref', ['class' => "form-control address_pref", 'maxlength' => 4, 'placeholder'=>'都道府県', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.pref', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.address1', ['class' => "form-control address_address1", 'maxlength' => 8, 'placeholder'=>'住所', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.address1', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.address2', ['class' => "form-control address_address2", 'maxlength' => 18, 'placeholder'=>'番地', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.address2', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.address3', ['class' => "form-control", 'maxlength' => 30, 'placeholder'=>'建物名', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.address3', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.tel1', ['class' => "form-control", 'maxlength' => 20, 'placeholder'=>'電話番号', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.tel1', null, ['wrap' => 'p']) ?>
                </div>
              <?php if ($customer->isPrivateCustomer()) : ?>
                <?php // 個人 ?>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.lastname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.lastname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.lastname_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓（カナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.lastname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.firstname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.firstname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.firstname_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名（カナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.firstname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-4">
                  <?php echo $this->Form->input('CustomerInfo.birth_year', ['class' => "form-control", 'placeholder'=>'年（西暦）', 'maxlength' => 4, 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.birth_year', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-4">
                  <?php echo $this->Form->input('CustomerInfo.birth_month', ['class' => "form-control date_zero_padding", 'placeholder'=>'月', 'maxlength' => 2, 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.birth_month', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-4">
                  <?php echo $this->Form->input('CustomerInfo.birth_day', ['class' => "form-control date_zero_padding", 'placeholder'=>'日', 'maxlength' => 2, 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.birth_day', null, ['wrap' => 'p']) ?>
                </div>
                <div class="col-lg-12">
                <?php echo $this->Form->error('CustomerInfo.birth', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <label>性別</label>
                  <?php echo $this->Form->select('CustomerInfo.gender', CUSTOMER_GENDER, ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.gender', null, ['wrap' => 'p']) ?>
                </div>
              <?php else : ?>
                <?php // 法人 ?>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.company_name', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'会社名（漢字）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.company_name', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.company_name_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'会社名（カタカナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.company_name_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.staff_name', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'担当者名（漢字）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.staff_name', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.staff_name_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'担当者名（カタカナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.staff_name_kana', null, ['wrap' => 'p']) ?>
                </div>
              <?php endif; ?>
                <div class="form-group col-lg-12">
                  <label>ニュースレターの配信</label>
                  <?php echo $this->Form->select('CustomerInfo.newsletter', CUSTOMER_NEWSLETTER, ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.newsletter', null, ['wrap' => 'p']) ?>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/customer/info/edit">クリアする</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">確認する</button>
                </span>
              </div>
              <?php echo $this->Form->hidden('CustomerInfo.applying'); ?>
              <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
