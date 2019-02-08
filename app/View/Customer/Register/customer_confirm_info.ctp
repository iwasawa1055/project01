    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-credit-card"></i> ユーザー登録</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
        <?php echo $this->Form->create('CustomerRegistInfo', ['url' => ['controller' => 'register', 'action' => 'customer_complete_info', '?' => ['code' => $code]], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <div class="panel-body">
            <div class="col-lg-12 col-md-12 none-title">
              <div class="form-group">
                <label>郵便番号</label>
                <p><?php echo $this->Form->data['CustomerRegistInfo']['postal'] ?></p>
              </div>
              <div class="form-group">
                <label>住所</label>
                <p><?php echo $this->CustomerInfo->setPrefAddress1($this->Form->data['CustomerRegistInfo']); ?></p>
              </div>
              <div class="form-group">
                <label>番地</label>
                <p><?php echo h($this->Form->data['CustomerRegistInfo']['address2']); ?></p>
              </div>
              <div class="form-group">
                <label>建物名</label>
                <p><?php echo h($this->Form->data['CustomerRegistInfo']['address3']); ?></p>
              </div>
              <div class="form-group">
                <label>部屋番号</label>
                <p><?php echo h($this->Form->data['CustomerRegistInfo']['room']); ?></p>
              </div>
              <div class="form-group">
                <label>名前</label>
                <p><?php echo $this->CustomerInfo->setName($this->Form->data['CustomerRegistInfo']); ?></p>
              </div>
              <div class="form-group">
                <label>生年月日（西暦）</label>
                <p><?php echo $this->CustomerInfo->setBirth($this->Form->data['CustomerRegistInfo']); ?></p>
              </div>
              <div class="form-group">
                <label>電話番号</label>
                <p><?php echo $this->Form->data['CustomerRegistInfo']['tel1']; ?></p>
              </div>
              <div class="form-group">
                <label>メールアドレス</label>
                <p><?php echo h($this->Form->data['CustomerRegistInfo']['email']); ?></p>
              </div>
              <div class="form-group">
                <label>パスワード</label>
                <p>入力されたパスワード</p>
              </div>
              <div class="form-group">
                <label>紹介コード</label>
                <p><?php echo h($this->Form->data['CustomerRegistInfo']['alliance_cd']); ?></p>
              </div>
              <div class="form-group">
                <label>ニュースレターの配信</label>
                <p><?php echo CUSTOMER_NEWSLETTER[$this->Form->data['CustomerRegistInfo']['newsletter']] ?></p>
              </div>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <?php echo $this->Html->link('戻る', ['controller' => 'register', 'action' => 'customer_add_info', '?' => ['code' => $code, 'back' => 'true']], ['class' => 'btn btn-primary btn-lg btn-block']); ?>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <button type="submit" class="btn btn-danger btn-lg btn-block">この内容で登録する</button>
            </span>
          </div>
        <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
