<?php
if (!empty($errorList)) {
    $this->Form->validationErrors['box_id'] = $errorList;
}
?>

<?php echo $this->Form->create('Outbound', ['url' => '/outbound/box/', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
<div id="page-wrapper" class="wrapper">
  <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> ボックス取り出し</h1>
  <ul class="pagenation">
    <li class="on"><span class="number">1</span><span class="txt">取り出し<br>選択</span>
    </li>
    <li><span class="number">2</span><span class="txt">配送情報<br>入力</span>
    </li>
    <li><span class="number">3</span><span class="txt">確認</span>
    </li>
    <li><span class="number">4</span><span class="txt">完了</span> </li>
  </ul>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
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
                <!--loop-->
                <div class="col-lg-12">
                  <div class="panel panel-default">
                    <?php echo $this->element('List/box_body_outbound_checkbox', ['box' => $box, 'default' => false]); ?>
                    <?php echo $this->element('List/box_footer', ['box' => $box]); ?>
                  </div>
                </div>
                <!--loop end-->
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if (!empty($boxList)) : ?>
<div class="nav-fixed">
  <ul>
    <li><button type="submit" class="btn-red">配送先を設定する</button>
    </li>
  </ul>
</div>
<?php endif;?>
<?php echo $this->Form->end(); ?>
