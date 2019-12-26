<?php $this->Html->script('inquiry/add.js?'.time(), ['block' => 'scriptMinikura']); ?>

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
            <?php echo $this->Form->create('ZendeskInquiry', ['url' => ['controller' => 'inquiry', 'action' => 'confirm'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="col-lg-12 none-title">
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('ZendeskInquiry.lastname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓', 'error' => false]); ?>
                  <?php echo $this->Form->error('ZendeskInquiry.lastname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('ZendeskInquiry.firstname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名', 'error' => false]); ?>
                  <?php echo $this->Form->error('ZendeskInquiry.firstname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('ZendeskInquiry.email', ['class' => "form-control", 'placeholder'=>'メールアドレス', 'error' => false]); ?>
                  <?php echo $this->Form->error('ZendeskInquiry.email', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの種別</label>
                  <?php echo $this->Form->select('ZendeskInquiry.division', INQUIRY_DIVISION, ['class' => 'form-control', 'empty' => '選択してください', 'error' => false]); ?>
                  <?php echo $this->Form->error('ZendeskInquiry.division', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの内容</label>
                  <?php echo $this->Form->textarea('ZendeskInquiry.comment', ['class' => "form-control", 'rows' => 5, 'error' => false]); ?>
                  <?php echo $this->Form->error('ZendeskInquiry.comment', null, ['wrap' => 'p']) ?>
                </div>
                <div id="inquiry_bug_area">
                  <div class="form-group col-lg-12">
                    <label>不具合発生日時</label>
                    <?php echo $this->Form->input('ZendeskInquiry.bug_datetime', ['class' => "form-control", 'error' => false, 'placeholder' => '例）2016/6/10 13:00 または 2016年6月10日 13時頃 など']); ?>
                    <?php echo $this->Form->error('ZendeskInquiry.bug_datetime', null, ['wrap' => 'p']) ?>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>不具合発生URL（またはページ）</label>
                    <?php echo $this->Form->input('ZendeskInquiry.bug_url', ['class' => "form-control", 'error' => false, 'placeholder' => '例）https://mypage.minikura.com/login または ログインページ など']); ?>
                    <?php echo $this->Form->error('ZendeskInquiry.bug_url', null, ['wrap' => 'p']) ?>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>ご利用環境（OS・ブラウザ）</label>
                    <?php echo $this->Form->input('ZendeskInquiry.bug_environment', ['class' => "form-control", 'error' => false, 'placeholder' => '例）iOS9・Safari など']); ?>
                    <?php echo $this->Form->error('ZendeskInquiry.bug_environment', null, ['wrap' => 'p']) ?>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>具体的な操作と症状</label>
                    <?php echo $this->Form->textarea('ZendeskInquiry.bug_text', ['class' => "form-control", 'rows' => 5, 'error' => false]); ?>
                    <?php echo $this->Form->error('ZendeskInquiry.bug_text', null, ['wrap' => 'p']) ?>
                  </div>
                </div>
                <div class="form-group col-lg-12">
                  <label>こちらではありませんか？</label>
                  <dev id="help_link">
                    <p><a href="https://help.minikura.com/hc/ja" target="_blank">よくあるご質問はこちら</a></p>
                  </dev>
                </div>
                <span class="col-lg-12 col-md-12 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">確認する</button>
                </span>
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
