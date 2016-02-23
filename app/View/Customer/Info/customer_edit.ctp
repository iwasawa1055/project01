<?php $this->Html->script('http://maps.google.com/maps/api/js?libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/address', ['block' => 'scriptMinikura']); ?>
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
              <?php echo $this->Form->create('CustomerInfoV3', ['url' => ['controller' => 'info', 'action' => $action, 'step' => 'confirm'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="col-lg-12">
                <h2>お客さま情報変更</h2>
                <p class="form-control-static col-lg-12">変更されるお客さまの情報をご入力してください。</p>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfoV3.postal', ['class' => "form-control search_address_postal", 'placeholder'=>'郵便番号（入力していただくと以下の入力がスムーズに行なえます）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.postal', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfoV3.pref', ['class' => "form-control address_pref", 'placeholder'=>'都道府県', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.pref', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfoV3.address1', ['class' => "form-control address_address1", 'placeholder'=>'住所', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.address1', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfoV3.address2', ['class' => "form-control address_address2", 'placeholder'=>'番地', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.address2', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfoV3.address3', ['class' => "form-control", 'placeholder'=>'建物名', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.address3', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfoV3.tel1', ['class' => "form-control", 'placeholder'=>'電話番号', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.tel1', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfoV3.lastname', ['class' => "form-control", 'placeholder'=>'姓', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.lastname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfoV3.lastname_kana', ['class' => "form-control", 'placeholder'=>'姓（カナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.lastname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfoV3.firstname', ['class' => "form-control", 'placeholder'=>'名', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.firstname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfoV3.firstname_kana', ['class' => "form-control", 'placeholder'=>'名（カナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.firstname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfoV3.birth_year', ['class' => "form-control", 'placeholder'=>'年（西暦）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.birth_year', null, ['wrap' => 'p']) ?>
                  <?php echo $this->Form->input('CustomerInfoV3.birth_month', ['class' => "form-control", 'placeholder'=>'月', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.birth_month', null, ['wrap' => 'p']) ?>
                  <?php echo $this->Form->input('CustomerInfoV3.birth_day', ['class' => "form-control", 'placeholder'=>'日', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.birth_day', null, ['wrap' => 'p']) ?>
                  <?php echo $this->Form->error('CustomerInfoV3.birth', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <label>性別</label>
                  <?php echo $this->Form->select('CustomerInfoV3.gender', CUSTOMER_GENDER, ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.gender', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <label>ニュースレターの配信</label>
                  <?php echo $this->Form->select('CustomerInfoV3.newsletter', CUSTOMER_NEWSLETTER, ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfoV3.newsletter', null, ['wrap' => 'p']) ?>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/customer/info/edit">クリア</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">確認する</button>
                </span>
              </div>
              <?php echo $this->Form->hidden('CustomerInfoV3.applying'); ?>
              <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
