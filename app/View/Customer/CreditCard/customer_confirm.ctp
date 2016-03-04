<?php
$actionName = '登録';
if ($action === 'customer_edit') {
    $actionName = '変更';
}
?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-credit-card"></i> クレジットカード<?php echo $actionName; ?></h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>クレジットカード<?php echo $actionName; ?></h2>
              <?php //echo $this->Form->create(false, ['url' => ['action' => 'complete']]); ?>
              <?php echo $this->Form->create(false, ['url' => ['controller' => 'credit_card', 'action' => $action, 'step' => 'complete']]); ?>
                <div class="form-group col-lg-12">
                  <label>クレジットカード番号</label>
                  <p><?php echo $security_card['card_no']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>セキュリティコード</label>
                  <p><?php echo $security_card['security_cd']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>有効期限</label>
                  <p><?php echo $security_card['expire_month']; ?>月/<?php echo $security_card['expire_year_disp']; ?>年</p>
                </div>
                <div class="form-group col-lg-12">
                  <label>クレジットカード名義</label>
                  <p><?php echo $security_card['holder_name']; ?></p>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <!-- <a class="btn btn-primary btn-lg btn-block" href="/customer/credit_card/edit?back=true">戻る</a> -->
                  <?php echo $this->Html->link('戻る', ['controller' => 'credit_card', 'action' => $action, '?' => ['back' => 'true']], ['class' => 'btn btn-primary btn-lg btn-block']); ?>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block"><?php echo $actionName; ?>する</button>
                </span>
              <?php echo $this->Form->end(); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
