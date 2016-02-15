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
            <?php echo $this->Form->create(false, ['url' => ['controller' => 'contact_us', 'action' => 'complete']]); ?>
              <div class="col-lg-12 none-title">
                <div class="form-group col-lg-12">
                  <label>お問い合わせの種別</label>
                  <p class="form-control-static"><?php echo CONTACTUS_DIVISION[$this->Form->data['ContactUs']['division']] ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの内容</label>
                  <p class="form-control-static">
                    <?php echo nl2br($this->Form->data['ContactUs']['text']); ?>
                  </p>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <?php echo $this->Html->link('戻る', ['controller' => 'contact_us', 'action' => 'add', 'id' => $id, '?' => ['back' => 'true']], ['class' => 'btn btn-primary btn-lg btn-block animsition-link']); ?>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">この内容で問い合わせる</button>
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
