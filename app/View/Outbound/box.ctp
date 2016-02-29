<?php
if (!empty($errorList)) {
    $this->Form->validationErrors['box_id'] = $errorList;
}
?>
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> ボックスを取り出す</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <?php echo $this->Form->create('Outbound', ['url' => '/outbound/box/', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <h2>取り出すボックスを選択</h2>
          <?php if (empty($boxList)) : ?>
            <?php echo $this->element('List/empty'); ?>
          <?php else: ?>
            <p class="form-control-static col-lg-12">お預かり済みの専用ボックスの一覧です。<br />
              「取り出しリストに登録」にチェックを入れて「取り出すボックスの確認」にすすんでください。</p>
          <?php endif; ?>
            <div class="row box-list">
              <?php foreach ($boxList as $box): ?>
              <?php
              $i = $box['box_id'];
              $url = '/box/detail/' . $box['box_id'];
              ?>
              <!--loop-->
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <?php echo $this->element('List/box_body_outbound', ['box' => $box, 'default' => false]); ?>
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
            <button type="submit" class="btn btn-danger btn-lg btn-block">取り出しリストを確認する</button>
        </span>
      <?php endif; ?>
      </div>
      <?php echo $this->Form->end(); ?>
    </div>
  </div>
</div>
