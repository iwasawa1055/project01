    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-truck"></i> お客さま情報変更</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
            <?php echo $this->Form->create('CustomerInfo', ['url' => ['controller' => 'info', 'action' => $action, 'step' => 'complete']]); ?>
              <div class="col-lg-12">
                <h2>お客さま情報変更</h2>
                <p class="form-control-static col-lg-12">以下の内容でお客さま情報を保存します。</p>
                <div class="form-group col-lg-12">
                  <label>郵便番号</label>
                  <p><?php echo $this->Form->data['CustomerInfo']['postal'] ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>住所</label>
                  <p><?php echo $this->CustomerInfo->setPrefAddress1($this->Form->data['CustomerInfo']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>番地</label>
                  <p><?php echo $this->Form->data['CustomerInfo']['address2']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>建物名</label>
                  <p><?php echo $this->Form->data['CustomerInfo']['address3']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>電話番号</label>
                  <p><?php echo $this->Form->data['CustomerInfo']['tel1']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>名前</label>
                  <p><?php echo $this->CustomerInfo->setName($this->Form->data['CustomerInfo']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>性別</label>
                  <p><?php echo CUSTOMER_GENDER[$this->Form->data['CustomerInfo']['gender']] ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>生年月日（西暦）</label>
                  <p><?php echo $this->CustomerInfo->setBirth($this->Form->data['CustomerInfo']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>ニュースレターの配信</label>
                  <p><?php echo CUSTOMER_NEWSLETTER[$this->Form->data['CustomerInfo']['newsletter']] ?></p>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <?php echo $this->Html->link('戻る', ['controller' => 'info', 'action' => $action, '?' => ['back' => 'true']], ['class' => 'btn btn-primary btn-lg btn-block']); ?>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">保存する</button>
                </span>
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
