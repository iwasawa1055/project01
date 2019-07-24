<?php
if (!empty($errorList)) {
    $this->Form->validationErrors['item_id'] = $errorList;
}
?>
<?php echo $this->Form->create('OutboundBox', ['url' => '/outbound/item', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
<div id="page-wrapper" class="wrapper">
  <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> アイテム取り出し</h1>
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
              <h2>取り出すアイテムを選択</h2>
              <?php if (empty($itemList)) : ?>
              <?php echo $this->element('List/empty_outbound_item'); ?>
              <?php else: ?>
              <p class="form-control-static col-lg-12">選択した専用ボックスに収納されているアイテムの一覧です。<br />
                取り出すアイテムにチェックを入れて「取り出しリストを確認する」にすすんでください。</p>
              <?php endif; ?>
              <div class="row box-list">
                <?php foreach ($itemList as $item): ?>
                <!--loop-->
                <div class="col-lg-12">
                  <div class="panel panel-default">
                    <?php echo $this->element('List/item_body_outbound_checkbox', ['item' => $item, 'default' => false]); ?>
                    <?php echo $this->element('List/item_footer', ['item' => $item]); ?>
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
<div class="nav-fixed">
  <ul>
    <li><a class="btn-d-gray"  href="/outbound/mono">ボックス<br class="sp">選択に戻る</a>
    </li>
    <li><button type="submit" class="btn-red">配送先を設定する</button>
    </li>
  </ul>
</div>
<?php echo $this->Form->end(); ?>