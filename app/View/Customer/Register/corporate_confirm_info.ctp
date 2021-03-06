    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-credit-card"></i> 法人ユーザー登録</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
        <?php echo $this->Form->create('CorporateRegistInfo', ['url' => ['controller' => 'register', 'action' => 'complete_info', '?' => ['code' => $code]], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <div class="panel-body">
            <div class="col-lg-12 col-md-12 none-title">
              <div class="form-group">
                <label>郵便番号</label>
                <p><?php echo $this->Form->data['CorporateRegistInfo']['postal'] ?></p>
              </div>
              <div class="form-group">
                <label>住所</label>
                <p><?php echo $this->CustomerInfo->setPrefAddress1($this->Form->data['CorporateRegistInfo']); ?></p>
              </div>
              <div class="form-group">
                <label>都道府県市区郡</label>
                <p><?php echo h($this->Form->data['CorporateRegistInfo']['address2']); ?></p>
              </div>
              <div class="form-group">
                <label>町域以降</label>
                <p><?php echo h($this->Form->data['CorporateRegistInfo']['address3']); ?></p>
              </div>
              <div class="form-group">
                <label>電話番号</label>
                <p><?php echo $this->Form->data['CorporateRegistInfo']['tel1']; ?></p>
              </div>
              <div class="form-group">
                <label>会社名（漢字）</label>
                <p><?php echo h($this->Form->data['CorporateRegistInfo']['company_name']); ?></p>
              </div>
              <div class="form-group">
                <label>会社名（カタカナ）</label>
                <p><?php echo h($this->Form->data['CorporateRegistInfo']['company_name_kana']); ?></p>
              </div>
              <div class="form-group">
                <label>担当者名（漢字）</label>
                <p><?php echo h($this->Form->data['CorporateRegistInfo']['staff_name']); ?></p>
              </div>
              <div class="form-group">
                <label>担当者名（カタカナ）</label>
                <p><?php echo h($this->Form->data['CorporateRegistInfo']['staff_name_kana']); ?></p>
              </div>
              <div class="form-group">
                <label>メールアドレス</label>
                <p><?php echo h($this->Form->data['CorporateRegistInfo']['email']); ?></p>
              </div>
              <div class="form-group">
                <label>紹介コード</label>
                <p><?php echo h($this->Form->data['CorporateRegistInfo']['alliance_cd']); ?></p>
              </div>
              <div class="form-group">
                <label>支払方法</label>
                <p><?php echo PAYMENT_METHOD[$this->Form->data['CorporateRegistInfo']['payment_method']] ?></p>
              </div>
              <div class="form-group">
                <label>お知らせメール</label>
                <p><?php echo CUSTOMER_NEWSLETTER[$this->Form->data['CorporateRegistInfo']['newsletter']] ?></p>
              </div>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <?php echo $this->Html->link('戻る', ['controller' => 'register', 'action' => 'add_info', '?' => ['code' => $code, 'back' => 'true']], ['class' => 'btn btn-primary btn-lg btn-block']); ?>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <button type="submit" class="btn btn-danger btn-lg btn-block">この内容で登録する</button>
            </span>
          </div>
        <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
