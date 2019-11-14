<?php
$actionName = '追加';
if ($action === 'customer_edit') {
    $actionName = '変更';
} else if ($action === 'customer_delete') {
    $actionName = '削除';
}
?>
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-truck"></i>お届け先<?php echo $actionName; ?></h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <?php echo $this->Form->create('CustomerAddress', ['url' => ['controller' => 'address', 'action' => $action, 'step' => 'complete', '?' => ['return' => Hash::get($this->request->query, 'return')]]]); ?>
        <div class="row">
          <div class="col-lg-12">
            <h2>お届け先<?php echo $actionName; ?></h2>
            <p class="form-control-static col-lg-12">以下の内容でお届け先情報を<?php echo $actionName; ?>します。</p>
            <div class="form-group col-lg-12">
              <label>郵便番号</label>
              <p>
                  <?php echo h($this->Form->data['CustomerAddress']['postal']); ?>
              </p>
            </div>
            <div class="form-group col-lg-12">
              <label>都道府県市区郡</label>
              <p>
                  <?php echo h($this->Form->data['CustomerAddress']['pref'] . $this->Form->data['CustomerAddress']['address1']); ?>
              </p>
            </div>
            <div class="form-group col-lg-12">
              <label>町域以降</label>
              <p>
                  <?php echo h($this->Form->data['CustomerAddress']['address2']); ?>
              </p>
            </div>
            <div class="form-group col-lg-12">
              <label>建物名</label>
              <p>
                  <?php echo h($this->Form->data['CustomerAddress']['address3']); ?>
              </p>
            </div>
            <div class="form-group col-lg-12">
              <label>電話番号</label>
              <p>
                  <?php echo $this->Form->data['CustomerAddress']['tel1']; ?>
              </p>
            </div>
            <div class="form-group col-lg-12">
              <label>名前</label>
              <p>
                  <?php echo h("{$this->Form->data['CustomerAddress']['lastname']}　{$this->Form->data['CustomerAddress']['firstname']}"); ?>
              </p>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
                <?php if ($action === 'customer_edit') : ?>
                    <a class="btn btn-primary btn-lg btn-block" href="/customer/address/edit?back=true"> 戻る </a>
                <?php elseif ($action === 'customer_add') : ?>
                    <?php echo $this->Html->link('戻る', ['controller' => 'address', 'action' => $action, '?' => ['back' => 'true', 'return' => Hash::get($this->request->query, 'return')]], ['class' => 'btn btn-primary btn-lg btn-block']); ?>
                <?php else : ?>
                    <a class="btn btn-primary btn-lg btn-block" href="/customer/address/"> 戻る </a>
                <?php endif; ?>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
                <button type="submit" class="btn btn-danger btn-lg btn-block"><?php echo $actionName; ?>する</button>
            </span>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
