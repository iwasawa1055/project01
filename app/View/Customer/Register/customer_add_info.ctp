<?php $this->Html->script('https://maps.google.com/maps/api/js?libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/address', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/customer_info', ['block' => 'scriptMinikura']); ?>
  <?php if (!empty($code)) : ?>
    <?php $header_params = @get_headers('https://minikura.com/contents/image/with/' . $code . '.gif'); ?>
    <?php if (strpos($header_params[0], '200') !== false) : ?>
    <div class="row">
      <div class="col-lg-12" align="center">
        <img class="alliance" src="https://minikura.com/contents/image/with/<?php echo $code ?>.gif" />
      </div>
    </div>
    <?php endif; ?>
  <?php endif; ?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-keyboard-o"></i> ユーザー登録</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <?php echo $this->Form->create('CustomerRegistInfo', ['url' => ['controller' => 'register', 'action' => 'customer_confirm_info', '?' => ['code' => $code]], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
            <div class="col-lg-12 col-md-12 none-title">
              <div class="form-group col-lg-12">
                <label>ご住所</label>
                <?php echo $this->Form->input('CustomerRegistInfo.postal', ['class' => "form-control search_address_postal", 'maxlength' => 8, 'placeholder'=>'郵便番号（入力すると以下の住所が自動で入力されます）', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.postal', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerRegistInfo.pref', ['class' => "form-control address_pref", 'maxlength' => 4, 'placeholder'=>'都道府県', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.pref', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerRegistInfo.address1', ['class' => "form-control address_address1", 'maxlength' => 8, 'placeholder'=>'住所', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.address1', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerRegistInfo.address2', ['class' => "form-control address_address2", 'maxlength' => 18, 'placeholder'=>'番地', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.address2', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerRegistInfo.address3', ['class' => "form-control", 'maxlength' => 30, 'placeholder'=>'建物名', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.address3', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerRegistInfo.room', ['class' => "form-control", 'maxlength' => 30, 'placeholder'=>'部屋番号', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.room', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <label>お客さま情報</label>
                <?php echo $this->Form->input('CustomerRegistInfo.lastname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.lastname', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerRegistInfo.lastname_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓（カナ）', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.lastname_kana', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerRegistInfo.firstname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.firstname', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerRegistInfo.firstname_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名（カナ）', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.firstname_kana', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-4">
                <?php echo $this->Form->input('CustomerRegistInfo.birth_year', ['class' => "form-control", 'placeholder'=>'年（西暦）', 'maxlength' => 4, 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.birth_year', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-4">
                <?php echo $this->Form->input('CustomerRegistInfo.birth_month', ['class' => "form-control date_zero_padding", 'placeholder'=>'月', 'maxlength' => 2, 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.birth_month', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-4">
                <?php echo $this->Form->input('CustomerRegistInfo.birth_day', ['class' => "form-control date_zero_padding", 'placeholder'=>'日', 'maxlength' => 2, 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.birth_day', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <?php echo $this->Form->error('CustomerRegistInfo.birth', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <label>性別</label>
                <?php echo $this->Form->select('CustomerRegistInfo.gender', CUSTOMER_GENDER, ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.gender', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <label>ご連絡先</label>
                <?php echo $this->Form->input('CustomerRegistInfo.tel1', ['class' => "form-control", 'maxlength' => 20, 'placeholder'=>'電話番号', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.tel1', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerRegistInfo.email', ['class' => "form-control", 'placeholder'=>'メールアドレス', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.email', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerRegistInfo.password', ['class' => "form-control", 'maxlength' => 64, 'placeholder'=>'パスワード', 'type' => 'password', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.password', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerRegistInfo.password_confirm', ['class' => "form-control", 'maxlength' => 64, 'placeholder'=>'パスワード（確認用）', 'type' => 'password', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.password_confirm', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <label>紹介コード</label>
                <?php echo $this->Form->input('CustomerRegistInfo.alliance_cd', ['class' => "form-control", 'placeholder'=>'紹介コードをお持ちの方はこちらにご入力ください', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.alliance_cd', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <label>ニュースレターの配信</label>
                <?php echo $this->Form->select('CustomerRegistInfo.newsletter', CUSTOMER_NEWSLETTER, ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerRegistInfo.newsletter', null, ['wrap' => 'p']) ?>
              </div>
              <div class="form-group col-lg-12">
                <a class="btn btn-info btn-xs btn-block" href="<?php echo Configure::read('site.static_content_url'); ?>/use_agreement/" target="_blank">minikura 利用規約</a>
                <div class="checkbox">
                  <label>
                    <input name="remember" type="checkbox" value="Remember Me" class="agree-before-submit">
                    <a class="link_privacy" href="<?php echo Configure::read("site.static_content_url"); ?>/privacy/" target="_blank">個人情報について</a>、及びminikura利用規約に同意する </label>
                </div>
                <button type="submit" class="btn btn-danger btn-lg btn-block">利用規約に同意して会員登録する</button>
                <a class="btn btn-primary btn-xs btn-block" href="/login">ログインはこちら</a>
                <?php if (!empty($code)) : ?>
                <a class="btn btn-danger btn-xs btn-block animsition-link" href="/corporate/register/add_info?code=<?php echo $code ?>">法人の方はこちら</a>
                <?php else : ?>
                <a class="btn btn-danger btn-xs btn-block animsition-link" href="/corporate/register/add_info">法人の方はこちら</a>
                <?php endif; ?>
              </div>
            </div>
            <?php echo $this->Form->end(); ?>
          </div>
        </div>
      </div>
    </div>
