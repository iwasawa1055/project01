    <?php if (!empty($validErrors)) { $this->validationErrors['ContactUs'] = $validErrors; } ?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-pencil-square-o"></i> お問い合わせ</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
            <?php echo $this->Form->create('ContactUs', ['url' => ['controller' => 'contact_us', 'action' => 'confirm', 'id' => $id], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="col-lg-12 none-title">
                <?php if ($customer->isEntry()) : ?>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('ContactUs.lastname', ['class' => "form-control", 'placeholder'=>'姓', 'error' => false]); ?>
                  <?php echo $this->Form->error('ContactUs.lastname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('ContactUs.lastname_kana', ['class' => "form-control", 'placeholder'=>'姓（カナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('ContactUs.lastname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('ContactUs.firstname', ['class' => "form-control", 'placeholder'=>'名', 'error' => false]); ?>
                  <?php echo $this->Form->error('ContactUs.firstname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('ContactUs.firstname_kana', ['class' => "form-control", 'placeholder'=>'名（カナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('ContactUs.firstname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('ContactUs.email', ['class' => "form-control", 'placeholder'=>'メールアドレス', 'error' => false]); ?>
                  <?php echo $this->Form->error('ContactUs.email', null, ['wrap' => 'p']) ?>
                </div>
                <?php endif; ?>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの種別</label>
                  <?php echo $this->Form->select('ContactUs.division', CONTACTUS_DIVISION, ['class' => 'form-control', 'empty' => '選択してください', 'error' => false]); ?>
                  <?php echo $this->Form->error('ContactUs.division', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの内容</label>
                  <?php echo $this->Form->textarea('ContactUs.text', ['class' => "form-control", 'rows' => 5, 'error' => false]); ?>
                  <?php echo $this->Form->error('ContactUs.text', null, ['wrap' => 'p']) ?>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/contact_us/<?php echo empty($id) ? '' : $id.'/' ?>add">クリア</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">確認</button>
                </span>
              <?php if ($id) : ?>
                <div class="col-lg-12 col-md-12 col-xs-12">
                  <h3 class="col-lg-12"><?php echo h($announcement['title']); ?></h3>
                  <h4 class="date col-lg-12"><?php echo $this->Html->formatYmdKanji($announcement['date']); ?></h4>
                  <div class="col-lg-12">
                    <?php echo nl2br(h($announcement['text'])); ?>
                  </div>
                </div>
              <?php endif; ?>
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
