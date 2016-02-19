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
            <p class="form-control-static col-lg-12">お預かり済みの専用ボックスの一覧です。<br />
              取り出すしたいアイテムが入っているボックスの「ボックス選択」にチェックを入れて「内容を確認する」にすすんでください。</p>
            <div class="row box-list">
                <?php foreach ($boxList as $box): ?>
                <?php
                $i = $box['box_id'];
                $url = '/box/detail/' . $box['box_id'];
                ?>
                <!--loop-->
                <div class="col-lg-12">
                  <div class="panel panel-default">
                    <?php echo $this->element('List/box_body_outbound', ['box' => $box, 'default' => false, 'class' => 'outbound_mono_select_checkbox']); ?>
                    <?php echo $this->element('List/box_footer', ['box' => $box]); ?>
                  </div>
                </div>
                <!--loop end-->
                <?php endforeach; ?>
            </div>
          </div>
        </div>
        <span class="col-lg-12 col-md-12 col-xs-12">
            <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">内容を確認する</button>
        </span>
      </div>
      <?php echo $this->Form->end(); ?>
    </div>
  </div>
</div>
