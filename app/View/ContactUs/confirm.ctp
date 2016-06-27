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
                <?php if ($customer->isEntry()) : ?>
                <div class="form-group col-lg-12">
                  <label>お名前</label>
                  <p class="form-control-static"><?php echo $this->CustomerInfo->setName($this->Form->data['ContactUs']); ?></p>
                </div>
                <?php endif; ?>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの種別</label>
                  <p class="form-control-static"><?php echo CONTACTUS_DIVISION[$this->Form->data['ContactUs']['division']] ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの内容</label>
                  <p class="form-control-static">
                    <?php echo nl2br(h($this->Form->data['ContactUs']['text'])); ?>
                  </p>
                </div>
                <?php if ($this->Form->data['ContactUs']['division'] === CONTACT_DIVISION_BUG) :?>
                  <div class="form-group col-lg-12">
                    <label>不具合発生日時</label>
                    <p class="form-control-static">
                      <?php echo h($this->Form->data['ContactUs']['bug_datetime']); ?>
                    </p>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>不具合発生URL（またはページ）</label>
                    <p class="form-control-static">
                      <?php echo h($this->Form->data['ContactUs']['bug_url']); ?>
                    </p>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>ご利用環境（OS・ブラウザ）</label>
                    <p class="form-control-static">
                      <?php echo h($this->Form->data['ContactUs']['bug_environment']); ?>
                    </p>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>具体的な操作と症状</label>
                    <p class="form-control-static">
                      <?php echo nl2br(h($this->Form->data['ContactUs']['bug_text'])); ?>
                    </p>
                  </div>
                <?php endif;?>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <?php echo $this->Html->link('戻る', ['controller' => 'contact_us', 'action' => 'add', 'id' => $id, '?' => ['back' => 'true']], ['class' => 'btn btn-primary btn-lg btn-block']); ?>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">この内容で問い合わせる</button>
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
