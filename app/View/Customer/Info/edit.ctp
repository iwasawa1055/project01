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
                  <?php echo $this->Form->input('CustomerInfo.postal', ['class' => "form-control", 'placeholder'=>'郵便番号（入力していただくと以下の入力がスムーズに行なえます）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.postal', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.pref', ['class' => "form-control", 'placeholder'=>'都道府県', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.pref', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.address1', ['class' => "form-control", 'placeholder'=>'住所', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.address1', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.address2', ['class' => "form-control", 'placeholder'=>'番地', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.address2', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.address3', ['class' => "form-control", 'placeholder'=>'建物名', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.address3', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.tel1', ['class' => "form-control", 'placeholder'=>'電話番号', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.tel1', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.lastname', ['class' => "form-control", 'placeholder'=>'姓', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.lastname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.lastname_kana', ['class' => "form-control", 'placeholder'=>'姓（カナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.lastname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.firstname', ['class' => "form-control", 'placeholder'=>'名', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.firstname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.firstname_kana', ['class' => "form-control", 'placeholder'=>'名（カナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.firstname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('CustomerInfo.birth_year', ['class' => "form-control", 'placeholder'=>'年（西暦）', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.birth_year', null, ['wrap' => 'p']) ?>
                  <?php echo $this->Form->input('CustomerInfo.birth_month', ['class' => "form-control", 'placeholder'=>'月', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.birth_month', null, ['wrap' => 'p']) ?>
                  <?php echo $this->Form->input('CustomerInfo.birth_day', ['class' => "form-control", 'placeholder'=>'日', 'error' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.birth_day', null, ['wrap' => 'p']) ?>
                  <?php echo $this->Form->error('CustomerInfo.birth', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->radio('CustomerInfo.gender', CUSTOMER_GENDER, ['legend' => false]); ?>
                  <?php echo $this->Form->error('CustomerInfo.gender', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->checkbox('CustomerInfo.newsletter'); ?>
                  <?php echo $this->Form->label('CustomerInfo.newsletter', 'ニュースレターの配信を希望する'); ?>
                  <?php echo $this->Form->error('CustomerInfo.newsletter', null, ['wrap' => 'p']) ?>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block animsition-link" href="/customer/info/edit">クリア</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">確認する</button>
                </span>
              </div>
              <?php echo $this->Form->hidden('CustomerInfo.applying'); ?>
              <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
