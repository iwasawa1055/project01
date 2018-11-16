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
            <?php echo $this->Form->create('ZendeskInquiry', ['url' => ['controller' => 'inquiry', 'action' => 'complete']]); ?>
              <div class="col-lg-12 none-title">
                <div class="form-group col-lg-12">
                  <label>お名前</label>
                  <p class="form-control-static"><?php echo h($this->Form->data['ZendeskInquiry']['lastname']); ?> <?php echo h($this->Form->data['ZendeskInquiry']['firstname']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>メールアドレス</label>
                  <p class="form-control-static"><?php echo $this->Form->data['ZendeskInquiry']['email']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの種別</label>
                  <p class="form-control-static"><?php echo INQUIRY_DIVISION[$this->Form->data['ZendeskInquiry']['division']] ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの内容</label>
                  <p class="form-control-static">
                    <?php echo nl2br(h($this->Form->data['ZendeskInquiry']['comment'])); ?>
                  </p>
                </div>
                <?php if ($this->Form->data['ZendeskInquiry']['division'] === CONTACT_DIVISION_BUG) :?>
                  <div class="form-group col-lg-12">
                    <label>不具合発生日時</label>
                    <p class="form-control-static">
                      <?php echo h($this->Form->data['ZendeskInquiry']['bug_datetime']); ?>
                    </p>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>不具合発生URL（またはページ）</label>
                    <p class="form-control-static">
                      <?php echo h($this->Form->data['ZendeskInquiry']['bug_url']); ?>
                    </p>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>ご利用環境（OS・ブラウザ）</label>
                    <p class="form-control-static">
                      <?php echo h($this->Form->data['ZendeskInquiry']['bug_environment']); ?>
                    </p>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>具体的な操作と症状</label>
                    <p class="form-control-static">
                      <?php echo nl2br(h($this->Form->data['ZendeskInquiry']['bug_text'])); ?>
                    </p>
                  </div>
                <?php endif;?>
                <div class="form-group col-lg-12">
                  <a class="btn btn-info btn-xs btn-block" href="<?php echo Configure::read('site.static_content_url'); ?>/use_agreement/" target="_blank">minikura 利用規約</a>
                  <label>
                    <input class="agree-before-submit" type="checkbox">
                    minikura 利用規約に同意する
                  </label>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/inquiry/add?back=true">戻る</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">この内容で問い合わせる</button>
                </span>
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
