    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-truck"></i> 住所・お届け先変更</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">

              <?php echo $this->Form->create('CustomerAddress', ['url' => ['controller' => 'address', 'action' => $action, 'step' => 'confirm'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>

            <div class="row">
              <div class="col-lg-12">
                <h2>お届け先追加</h2>
                <?php echo $this->Form->hidden('CustomerAddress.address_id'); ?>
                <div class="form-group col-lg-12">
                    <?php echo $this->Form->input('CustomerAddress.postal', ['class' => "form-control", 'placeholder'=>'郵便番号（入力していただくと以下の入力がスムーズに行なえます）', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.postal', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                    <?php echo $this->Form->input('CustomerAddress.pref', ['class' => "form-control", 'placeholder'=>'都道府県', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.pref', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                    <?php echo $this->Form->input('CustomerAddress.address1', ['class' => "form-control", 'placeholder'=>'住所', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.address1', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                    <?php echo $this->Form->input('CustomerAddress.address2', ['class' => "form-control", 'placeholder'=>'番地', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.address2', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                    <?php echo $this->Form->input('CustomerAddress.address3', ['class' => "form-control", 'placeholder'=>'建物名', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.address3', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                    <?php echo $this->Form->input('CustomerAddress.tel1', ['class' => "form-control", 'placeholder'=>'電話番号', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.tel1', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-md-6">
                    <?php echo $this->Form->input('CustomerAddress.lastname_kana', ['class' => "form-control", 'placeholder'=>'苗字カナ', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.lastname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-md-6">
                    <?php echo $this->Form->input('CustomerAddress.firstname_kana', ['class' => "form-control", 'placeholder'=>'名前カナ', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.firstname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-md-6">
                    <?php echo $this->Form->input('CustomerAddress.lastname', ['class' => "form-control", 'placeholder'=>'苗字', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.lastname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-md-6">
                    <?php echo $this->Form->input('CustomerAddress.firstname', ['class' => "form-control", 'placeholder'=>'名前', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.firstname', null, ['wrap' => 'p']) ?>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                    <a class="btn btn-primary btn-lg btn-block animsition-link" href="/customer/address/add"> クリア </a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                    <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">確認</button>
                </span>
              </div>
            </div>

            <?php echo $this->Form->end(); ?>
          </div>
        </div>
      </div>
    </div>
