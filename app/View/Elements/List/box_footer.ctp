<div class="panel-footer">
    <div class="row">
      <div class="col-lg-10 col-md-10 col-sm-12">
        <?php if (!empty($box['product_name'])) : ?>
        <p class="box-list-caption"><span>商品名</span><?php echo h($box['product_name']); ?></p>
        <?php endif;?>
        <?php if (!empty($box['box_id'])) : ?>
        <p class="box-list-caption"><span>ボックスID</span><?php echo $box['box_id']; ?></p>
        <?php endif;?>
        <?php if ($box['wrapping_type'] == '0' || !empty($box['wrapping_type'])) : ?>
        <p class="box-list-caption"><span>外装管理</span><?php echo BOX_WRAPPING_TYPE_LIST[$box['wrapping_type']];?></p>
        <?php endif;?>
        <?php if (!empty($box['keeping_type'])) : ?>
        <p class="box-list-caption"><span>保管方法</span><?php echo BOX_KEEPING_TYPE_LIST[$box['keeping_type']];?></p>
        <?php endif;?>
        <p class="box-list-caption"><span>ステータス</span><?php echo BOX_STATUS_LIST[$box['box_status']];?></p>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-12">
        <?php if (!empty($box['last_inbound_date'])) : ?>
        <p class="box-list-caption"><span>入庫日</span><?php echo $this->Html->formatYmdKanji($box['last_inbound_date']); ?></p>
        <?php endif;?>
        <?php if (!empty($box['outbound_date']) && $box['box_status'] == BOXITEM_STATUS_OUTBOUND_DONE) : ?>
        <p class="box-list-caption"><span>出庫日</span><?php echo $this->Html->formatYmdKanji($box['outbound_date']); ?></p>
        <?php endif;?>
      </div>
    </div>
</div>
