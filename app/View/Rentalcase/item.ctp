<?php
if (!empty($errorList)) {
    $this->Form->validationErrors['item_id'] = $errorList;
}
?>
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-suitcase"></i> レンタルケースで取り出す</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <?php echo $this->Form->create('RentalcaseBox', ['url' => '/rentalcase/item', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
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
                  <?php echo $this->element('List/item_body_rentalcase_checkbox', ['item' => $item, 'default' => false]); ?>
                  <?php echo $this->element('List/item_footer', ['item' => $item]); ?>
                </div>
              </div>
              <!--loop end-->
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <span class="col-lg-6 col-md-6 col-xs-12">
          <a class="btn btn-primary btn-lg btn-block" href="/rentalcase/mono">ボックス一覧に戻る</a>
        </span>
        <span class="col-lg-6 col-md-6 col-xs-12">
            <button type="submit" class="btn btn-danger btn-lg btn-block">取り出しリストを確認する</button>
        </span>
      </div>
      <?php echo $this->Form->end(); ?>
    </div>
  </div>
</div>
