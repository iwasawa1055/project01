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
                  <a class="btn btn-primary btn-lg btn-block animsition-link" href="/contact_us/add">クリア</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">確認</button>
                </span>
                <?php if ($id) : ?>
                <div class="col-lg-12 announcement">
                  <div class="row">
                    <div class="col-lg-12">
                      <h3><?php echo $announcement['title'] ?></h3>
                      <h4 class="date"><?php echo $this->Html->formatYmdKanji($announcement['date']); ?></h4>
                      <h5 class="date">お知らせID：<?php echo $announcement['announcement_id'] ?></h5>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="row body">
                      <?php echo nl2br($announcement['text']) ?>
                    </div>
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
