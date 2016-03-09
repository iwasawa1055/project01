<?php
if (!empty($errorList)) {
    $this->Form->validationErrors['box_id'] = $errorList;
}
?>
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> アイテムを取り出す</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <?php echo $this->Form->create('Outbound', ['url' => '/outbound/mono', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <h2>取り出すアイテムを選択</h2>
          <?php if (empty($boxList)) : ?>
            <?php echo $this->element('List/empty'); ?>
          <?php else: ?>
            <p class="form-control-static col-lg-12">お預かり済みの専用ボックスの一覧です。<br />
              取り出したいアイテムが入っているボックスの「ボックス選択」にチェックを入れて「内容を確認する」にすすんでください。</p>
          <?php endif; ?>
            <div class="row box-list">
                <?php foreach ($boxList as $box): ?>
                <!--loop-->
                <div class="col-lg-12">
                  <div class="panel panel-default">
                    <?php echo $this->element('List/box_body_outbound_checkbox', ['box' => $box, 'default' => false, 'class' => 'box_select_checkbox']); ?>
                    <?php echo $this->element('List/box_footer', ['box' => $box]); ?>
                  </div>
                </div>
                <!--loop end-->
                <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php if (!empty($boxList)) : ?>
        <span class="col-lg-12 col-md-12 col-xs-12">
            <button type="submit" class="btn btn-danger btn-lg btn-block">内容を確認する</button>
        </span>
      <?php endif; ?>
      </div>
      <?php echo $this->Form->end(); ?>
    </div>
  </div>
</div>
