<div class="panel-footer">
  <div class="row">
    <div class="col-lg-10 col-md-10 col-sm-12">
      <p class="box-list-caption"><span>収納ボックスID</span><?php echo $item['box_id']; ?></p>
      <p class="box-list-caption"><span>アイテムID</span><?php echo $item['item_id']; ?></p>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-12">
      <?php if (!empty($box)): ?>
      <p class="box-list-caption"><span>入庫日</span><?php echo $box['inbound_date']; ?></p>
      <p class="box-list-caption"><span>出庫日</span><?php echo $box['outbound_date']; ?></p>
      <?php endif; ?>
    </div>
  </div>
</div>
