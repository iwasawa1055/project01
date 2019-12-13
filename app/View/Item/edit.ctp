<?php echo $this->Form->create('Item', ['url' => '/item/detail/'.$item['item_id'].'/edit', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
<div id="page-wrapper">
  <h1 class="page-header"><i class="fa fa-diamond"></i> アイテム</h1>
  <ul class="ls-detail-item">
    <li class="l-detail-img">
        <?php if (!empty(Hash::get($item, 'image_first.image_url'))) : ?>
          <img src="<?php echo Hash::get($item, 'image_first.image_url'); ?>">
        <?php endif; ?>
    </li>
    <li class="l-detail-desc">
      <ul class="ls-detail-info">
        <li class="l-detail-info">
          <label class="txt-hline">アイテム名</label>
          <?php echo $this->Form->textarea('Item.item_name', ['class' => "h7", 'error' => false]); ?>
          <?php echo $this->Form->error('Item.item_name', null, ['wrap' => 'p']) ?>
        </li>
        <li class="l-detail-info">
          <label class="txt-hline">備考</label>
            <?php echo $this->Form->textarea('Item.item_note', ['class' => "h10", 'error' => false]); ?>
            <?php echo $this->Form->error('Item.item_note', null, ['wrap' => 'p']) ?>
        </li>
        <li class="l-detail-info">
          <ul class="ls-status-item">
            <li class="l-status">
              <label class="txt-hline">ステータス</label>
              <p class="txt-status"><?php echo h(BOX_STATUS_LIST[$item['item_status']]); ?></p>
            </li>
            <li class="l-status">
              <label class="txt-hline">収納ボックスID</label>
              <p class="txt-status"><?php echo $item['box_id']; ?></p>
            </li>
            <li class="l-status">
              <label class="txt-hline">アイテムID</label>
              <p class="txt-status"><?php echo $item['item_id']; ?></p>
            </li>
            <li class="l-status">
              <label class="txt-hline">入庫日</label>
              <p class="txt-status"><?php echo $this->Html->formatYmdKanji($item['box']['last_inbound_date']); ?></p>
            </li>
          </ul>
        </li>
      </ul>
    </li>
  </ul>
</div>
<div class="nav-fixed">
  <ul>
    <li><a class="btn-d-gray" href="/item/detail/<?php echo $item['item_id'] ?>">詳細に戻る</a></li>
    <li><button type="submit" class="btn-red">情報を保存する</button></li>
  </ul>
</div>
<?php echo $this->Form->hidden('Item.item_id'); ?>
<?php echo $this->Form->end(); ?>