    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-credit-card"></i> ユーザー登録[sneakers]</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
        <?php echo $this->Form->create('CustomerEntry', ['url' => ['controller' => 'register', 'action' => 'customer_complete_sneakers', '?' => ['code' => $code]], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <div class="panel-body">
            <div class="col-lg-12 col-md-12 none-title">
              <div class="form-group">
                <label>メールアドレス</label>
                <p><?php echo h($this->Form->data['CustomerEntry']['email']); ?></p>
              </div>
              <div class="form-group">
                <label>パスワード</label>
                <p>入力されたパスワード</p>
              </div>
              <div class="form-group">
                <label>紹介コード</label>
                <p><?php echo h($this->Form->data['CustomerEntry']['alliance_cd']); ?></p>
              </div>
              <div class="form-group">
                <label>お知らせメール</label>
                <p><?php echo CUSTOMER_NEWSLETTER[$this->Form->data['CustomerEntry']['newsletter']] ?></p>
              </div>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <?php echo $this->Html->link('戻る', ['controller' => 'register', 'action' => 'customer_add_sneakers', '?' => ['code' => $code, 'back' => 'true']], ['class' => 'btn btn-primary btn-lg btn-block']); ?>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <button type="submit" class="btn btn-danger btn-lg btn-block">この内容で登録する</button>
            </span>
          </div>
        <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
