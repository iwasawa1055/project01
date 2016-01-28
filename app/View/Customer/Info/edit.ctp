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
              <?php echo $this->Form->create('Customer', ['url' => ['controller' => 'info', 'action' => $action, 'step' => 'confirm'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="col-lg-12">
                <h2>お客さま情報変更</h2>
              <?php if ($this->request->data['Customer']['applying']): ?>
                <p class="form-control-static col-lg-12">以下の内容で変更申請中です。<br>
変更内容を確認させていただきますので、変更の反映にはお時間をいただきます。<br>
※変更内容によっては確認のご連絡をさせていただく場合がございます。あらかじめご了承ください。</p>
              <?php else: ?>
                <p class="form-control-static col-lg-12">変更されるお客さまの情報をご入力してください。</p>
              <?php endif; ?>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Customer.postal', ['class' => "form-control", 'placeholder'=>'郵便番号（入力していただくと以下の入力がスムーズに行なえます）', 'error' => false]); ?>
                  <?php echo $this->Form->error('Customer.postal', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Customer.pref', ['class' => "form-control", 'placeholder'=>'都道府県', 'error' => false]); ?>
                  <?php echo $this->Form->error('Customer.pref', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Customer.address1', ['class' => "form-control", 'placeholder'=>'住所', 'error' => false]); ?>
                  <?php echo $this->Form->error('Customer.address1', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Customer.address2', ['class' => "form-control", 'placeholder'=>'番地', 'error' => false]); ?>
                  <?php echo $this->Form->error('Customer.address2', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Customer.address3', ['class' => "form-control", 'placeholder'=>'建物名', 'error' => false]); ?>
                  <?php echo $this->Form->error('Customer.address3', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Customer.tel1', ['class' => "form-control", 'placeholder'=>'電話番号', 'error' => false]); ?>
                  <?php echo $this->Form->error('Customer.tel1', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Customer.lastname', ['class' => "form-control", 'placeholder'=>'姓', 'error' => false]); ?>
                  <?php echo $this->Form->error('Customer.lastname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Customer.lastname_kana', ['class' => "form-control", 'placeholder'=>'姓（カナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('Customer.lastname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Customer.firstname', ['class' => "form-control", 'placeholder'=>'名', 'error' => false]); ?>
                  <?php echo $this->Form->error('Customer.firstname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Customer.firstname_kana', ['class' => "form-control", 'placeholder'=>'名（カナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('Customer.firstname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Customer.birth_year', ['class' => "form-control", 'placeholder'=>'年（西暦）', 'error' => false]); ?>
                  <?php echo $this->Form->error('Customer.birth_year', null, ['wrap' => 'p']) ?>
                  <?php echo $this->Form->input('Customer.birth_month', ['class' => "form-control", 'placeholder'=>'月', 'error' => false]); ?>
                  <?php echo $this->Form->error('Customer.birth_month', null, ['wrap' => 'p']) ?>
                  <?php echo $this->Form->input('Customer.birth_day', ['class' => "form-control", 'placeholder'=>'日', 'error' => false]); ?>
                  <?php echo $this->Form->error('Customer.birth_day', null, ['wrap' => 'p']) ?>
                  <?php echo $this->Form->error('Customer.birth', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->radio('Customer.gender', ['m' => '男性', 'f' => '女性'], ['legend' => false]); ?>
                  <?php echo $this->Form->error('Customer.gender', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->checkbox('Customer.newsletter'); ?>
                  <?php echo $this->Form->label('Customer.newsletter', 'ニュースレターの配信を希望する'); ?>
                  <?php echo $this->Form->error('Customer.newsletter', null, ['wrap' => 'p']) ?>
                </div>
              <?php if ($this->request->data['Customer']['applying']): ?>
                <span class="col-lg-12 col-md-12 col-xs-12">
                <a class="btn btn-primary btn-lg btn-block animsition-link" href="#">戻る</a>
                </span>
              <?php else: ?>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <a class="btn btn-primary btn-lg btn-block animsition-link" href="#">クリア</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">確認する</button>
                </span>
              <?php endif; ?>
              </div>
              <?php echo $this->Form->hidden('Customer.applying'); ?>
              <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
