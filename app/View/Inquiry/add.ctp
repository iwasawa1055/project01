<?php $this->Html->script('/js/inquiry/confirm', ['block' => 'scriptMinikura']); ?>

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
                  <input type="checkbox" class="cb" id="terms" value="1">
                  <span class="icon-cb">
                    当社の<div data-toggle="collapse" data-parent="#accordion" href="#collapseOne" style="color:red; display: inline; text-decoration: underline; cursor: pointer;">個人情報保護方針</div>に同意する
                  </span>
                  <br>
                  <div id="collapseOne" class="panel-collapse collapse panel panel-default">
                    <div class="panel-body">
                      <p>寺田倉庫株式会社（以下「当社」という。）は、個人情報保護方針に従い、お客様および従業者の個人情報を適法かつ適切に取扱います。</p>
                      <ol>
                        <li>個人情報の利用目的</li>
                        <p>当社は、お問合わせに関するお客様および従業者の個人情報を法令に基づく場合を除きお客様および従業者が同意した利用目的の達成に必要な範囲内において適法かつ適切に取り扱います。</p>
                        <ol>
                          <li>お問合わせに対するご回答･ご連絡のため</li>
                        </ol>
                        <br>
                        <li>個人情報に関するお問い合わせ・ご請求の手続き</li>
                        <p>当社の個人情報の取り扱いに関するお問合わせ、当社が保有する個人情報の利用目的の通知・開示、訂正、追加、削除、利用停止等を希望される場合は、以下の個人情報苦情相談対応窓口までご連絡ください。
                        </p>
                        <p>お客様および従業者が、当社に個人情報を提供する場合は、上記の内容につきご承諾のうえ、お客様および従業者のご意思でご提供いただきますようお願い申し上げます。なお、ご承諾いただけない場合は業務を遂行できない可能性があります。</p>
                        <br>
                        <p>＜保有個人データの取扱いに関する苦情の申し出先＞</p>
                          <ul style="list-style:none;">
                            <li>寺田倉庫株式会社      個人情報苦情相談対応窓口</li>
                            <li>電話番号  ：  03-5479-1608    ：  privacy_policy@terrada.co.jp</li>
                            <li>受付付時間  ： 平日９：００～１７：００    土日祝祭日・年末年始を除く</li>
                          </ul>
                      </ol>
                    </div>
                  </div>
                </div>
                <span class="col-lg-12 col-md-12 col-xs-12">
                  <button type="button" class="btn btn-danger btn-lg btn-block" id="execute">確認する</button>
                </span>
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
