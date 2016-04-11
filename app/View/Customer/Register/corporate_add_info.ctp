<?php $this->Html->script('https://maps.google.com/maps/api/js?libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/address', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/corporate_add_info', ['block' => 'scriptMinikura']); ?>
  <?php if (!empty($code)) : ?>
    <div class="row">
      <div class="col-lg-12" align="center">
        <img class="alliance" src="https://minikura.com/contents/image/with/<?php echo $code ?>.gif" />
      </div>
    </div>
  <?php endif; ?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-credit-card"></i> 法人ユーザー登録</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <?php echo $this->Form->create('CorporateRegistInfo', ['url' => ['controller' => 'register', 'action' => 'confirm_info', '?' => ['code' => $code]], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
            <div class="col-lg-12 col-md-12 none-title">
              <div class="form-group">
                <?php echo $this->Form->input('CorporateRegistInfo.postal', ['class' => "form-control search_address_postal", 'maxlength' => 8, 'placeholder'=>'郵便番号（入力すると以下の住所が自動で入力されます）', 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.postal', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CorporateRegistInfo.pref', ['class' => "form-control address_pref", 'maxlength' => 4, 'placeholder'=>'都道府県', 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.pref', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CorporateRegistInfo.address1', ['class' => "form-control address_address1", 'maxlength' => 8, 'placeholder'=>'住所', 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.address1', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CorporateRegistInfo.address2', ['class' => "form-control address_address2", 'maxlength' => 18, 'placeholder'=>'番地', 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.address2', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CorporateRegistInfo.address3', ['class' => "form-control", 'maxlength' => 30, 'placeholder'=>'建物名', 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.address3', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CorporateRegistInfo.tel1', ['class' => "form-control", 'maxlength' => 20, 'placeholder'=>'電話番号', 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.tel1', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CorporateRegistInfo.company_name', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'会社名（漢字）', 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.company_name', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CorporateRegistInfo.company_name_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'会社名（カタカナ）', 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.company_name_kana', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CorporateRegistInfo.staff_name', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'担当者名（漢字）', 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.staff_name', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CorporateRegistInfo.staff_name_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'担当者名（カタカナ）', 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.staff_name_kana', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <label>メールアドレス</label>
                <?php echo $this->Form->input('CorporateRegistInfo.email', ['class' => "form-control", 'placeholder'=>'メールアドレス', 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.email', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CorporateRegistInfo.password', ['class' => "form-control", 'maxlength' => 64, 'placeholder'=>'パスワード', 'type' => 'password', 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.password', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group">
                <?php echo $this->Form->input('CorporateRegistInfo.password_confirm', ['class' => "form-control", 'maxlength' => 64, 'placeholder'=>'パスワード（確認用）', 'type' => 'password', 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.password_confirm', null, ['wrap' => 'p']) ?>
              </div>
              <?php if (empty($code)) : ?>
              <div class="form-group">
              <label>紹介コード</label>
                <?php echo $this->Form->input('CorporateRegistInfo.alliance_cd', ['class' => "form-control", 'placeholder'=>'紹介コードをお持ちの方はこちらにご入力ください', 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.alliance_cd', null, ['wrap' => 'p']) ?>
              </div>
              <?php endif; ?>
              <div class="form-group">
                <label>支払方法</label>
                  <div class="panel-body payment">
                    <div class="panel-group payment-inner" id="accordion">
                      <div class="panel panel-default">
                        <div class="panel-heading">
                          <h4 class="panel-title">
                            <label><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" onclick="clicked('card')">
                              <?php echo $this->Form->input('CorporateRegistInfo.payment_method', ['type' => 'radio', 'options' => [PAYMENT_METHOD_CREDITCARD => ' コーポレートカード（本日よりご利用いただけます。）'], 'error' => false, 'hiddenField' => false]); ?>
                              </a>
                            </label>
                          </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse">
                          <div class="panel-body">
                            <p>＊キット購入時にコーポレートカード情報をご入力ください。</p>
                          </div>
                        </div>
                      </div>
                      <div class="panel panel-default">
                        <div class="panel-heading">
                          <h4 class="panel-title">
                            <label><a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" onclick="clicked('account')">
                              <?php echo $this->Form->input('CorporateRegistInfo.payment_method', ['type' => 'radio', 'options' => [PAYMENT_METHOD_ACCOUNTTRANSFER => ' 口座振替（書類のご提出が必要となりますのでお時間をいただきます。）'], 'error' => false, 'hiddenField' => false]); ?>
                              </a>
                            </label>
                          </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse">
                          <div class="panel-body">
                            <p>＊口座振替でのお支払いをご希望の場合は、以下ボタンより口座振替申請書類をダウンロードし、弊社までお送りください。</p>
                            <a class="btn btn-danger btn-md" href="/files/deposit-account-transfer.pdf" target="_blank">口座振替書類ダウンロード</a>
                            <p>尚、口座振替書類が弊社にて確認できるまでは、キットの購入ができません。<br />
                              口座振替書類の確認ができ次第、登録のメールアドレスへご連絡させていただきます。</p>
                            <p>※弊社に書類が到着してから審査が完了するまでお時間がかかる場合がございます。<br />
                              あらかじめご了承ください。</p>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php echo $this->Form->error('CorporateRegistInfo.payment_method', null, ['wrap' => 'p']) ?>
                  </div>
              </div>
              <div class="form-group">
                <label>お知らせメール</label>
                <?php echo $this->Form->select('CorporateRegistInfo.newsletter', CUSTOMER_NEWSLETTER, ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
                <?php echo $this->Form->error('CorporateRegistInfo.newsletter', null, ['wrap' => 'p']) ?>
              </div>
              <a class="btn btn-info btn-xs btn-block" href="<?php echo Configure::read('site.static_content_url'); ?>/use_agreement/" target="_blank">minikura利用規約</a>
              <div class="checkbox">
                <label>
                  <input name="remember" type="checkbox" value="Remember Me" class="agree-before-submit">
                  minikura利用規約に同意する </label>
              </div>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-primary btn-lg btn-block animsition-link" href="/corporate/register/add_info"> クリア </a>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <button type="submit" class="btn btn-danger btn-lg btn-block"> 確認する </button>
            </span>
            <?php echo $this->Form->end(); ?>
          </div>
        </div>
      </div>
    </div>
