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
            <?php echo $this->Form->create('Inquiry', ['url' => ['controller' => 'inquiry', 'action' => 'confirm'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="col-lg-12 none-title">
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Inquiry.lastname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓', 'error' => false]); ?>
                  <?php echo $this->Form->error('Inquiry.lastname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Inquiry.lastname_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓（カナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('Inquiry.lastname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Inquiry.firstname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名', 'error' => false]); ?>
                  <?php echo $this->Form->error('Inquiry.firstname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Inquiry.firstname_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名（カナ）', 'error' => false]); ?>
                  <?php echo $this->Form->error('Inquiry.firstname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->input('Inquiry.email', ['class' => "form-control", 'placeholder'=>'メールアドレス', 'error' => false]); ?>
                  <?php echo $this->Form->error('Inquiry.email', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの種別</label>
                  <?php echo $this->Form->select('Inquiry.division', INQUIRY_DIVISION, ['class' => 'form-control', 'empty' => '選択してください', 'error' => false]); ?>
                  <?php echo $this->Form->error('Inquiry.division', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの内容</label>
                  <?php echo $this->Form->textarea('Inquiry.text', ['class' => "form-control", 'rows' => 5, 'error' => false]); ?>
                  <?php echo $this->Form->error('Inquiry.text', null, ['wrap' => 'p']) ?>
                </div>
                <div id="bug_area">
                  <div class="form-group col-lg-12">
                    <label>不具合発生日時</label>
                    <?php echo $this->Form->input('Inquiry.bug_datetime', ['class' => "form-control", 'error' => false, 'placeholder' => '例）2016/6/10 13:00 または 2016年6月10日 13時頃 など']); ?>
                    <?php echo $this->Form->error('Inquiry.bug_datetime', null, ['wrap' => 'p']) ?>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>不具合発生URL（またはページ）</label>
                    <?php echo $this->Form->input('Inquiry.bug_url', ['class' => "form-control", 'error' => false, 'placeholder' => '例）https://mypage.minikura.com/login または ログインページ など']); ?>
                    <?php echo $this->Form->error('Inquiry.bug_url', null, ['wrap' => 'p']) ?>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>ご利用環境（OS・ブラウザ）</label>
                    <?php echo $this->Form->input('Inquiry.bug_environment', ['class' => "form-control", 'error' => false, 'placeholder' => '例）iOS・Safari など']); ?>
                    <?php echo $this->Form->error('Inquiry.bug_environment', null, ['wrap' => 'p']) ?>
                  </div>
                  <div class="form-group col-lg-12">
                    <label>具体的な操作と症状</label>
                    <?php echo $this->Form->textarea('Inquiry.bug_text', ['class' => "form-control", 'rows' => 5, 'error' => false]); ?>
                    <?php echo $this->Form->error('Inquiry.bug_text', null, ['wrap' => 'p']) ?>
                  </div>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/inquiry/add">クリアする</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">確認する</button>
                </span>
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
