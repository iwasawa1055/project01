<?php
$actionName = '変更';
if ($action === 'customer_add') {
    $actionName = '登録';
}
?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-truck"></i> お客さま情報<?php echo $actionName; ?></h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
            <?php echo $this->Form->create('CustomerInfo', ['url' => ['controller' => 'info', 'action' => $action, 'step' => 'complete']]); ?>
              <div class="col-lg-12">
                <h2>お客さま情報<?php echo $actionName; ?></h2>
                <p class="form-control-static col-lg-12">以下の内容でお客さま情報を保存します。</p>
                <div class="form-group col-lg-12">
                  <label>郵便番号</label>
                  <p><?php echo $this->Form->data['CustomerInfo']['postal'] ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>都道府県市区郡</label>
                  <p><?php echo $this->CustomerInfo->setPrefAddress1($this->Form->data['CustomerInfo']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>町域以降</label>
                  <p><?php echo h($this->Form->data['CustomerInfo']['address2']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>建物名</label>
                  <p><?php echo h($this->Form->data['CustomerInfo']['address3']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>電話番号</label>
                  <p><?php echo $this->Form->data['CustomerInfo']['tel1']; ?></p>
                </div>
              <?php if ($customer->isPrivateCustomer()) : ?>
                <?php // 個人 ?>
                <div class="form-group col-lg-12">
                  <label>名前</label>
                  <p><?php echo $this->CustomerInfo->setName($this->Form->data['CustomerInfo']); ?></p>
                </div>
                <?php // 値が空じゃなければ、表示する ?>
                <?php if ($this->Form->data['CustomerInfo']['birth_year'] != '' && $this->Form->data['CustomerInfo']['birth_month'] != '' && $this->Form->data['CustomerInfo']['birth_day'] != '') : ?>
                  <div class="form-group col-lg-12">
                    <label>生年月日（西暦）</label>
                    <p><?php echo $this->CustomerInfo->setBirth($this->Form->data['CustomerInfo']); ?></p>
                  </div>
                <?php endif; ?>
              <?php else : ?>
                <?php // 法人 ?>
                <div class="form-group col-lg-12">
                  <label>会社名（漢字）</label>
                  <p><?php echo h($this->Form->data['CustomerInfo']['company_name']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>会社名（カタカナ）</label>
                  <p><?php echo h($this->Form->data['CustomerInfo']['company_name_kana']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>担当者名（漢字）</label>
                  <p><?php echo h($this->Form->data['CustomerInfo']['staff_name']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>担当者名（カタカナ）</label>
                  <p><?php echo h($this->Form->data['CustomerInfo']['staff_name_kana']); ?></p>
                </div>
              <?php endif; ?>
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
