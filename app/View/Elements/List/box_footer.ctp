<div class="panel-footer">
    <div class="row">
      <div class="col-lg-10 col-md-10 col-sm-12">
        <?php if (!empty($box['product_name'])) : ?>
        <p class="box-list-caption"><span>商品名</span><?php echo h($box['product_name']); ?></p>
        <?php endif;?>
        <?php if (!empty($box['box_id'])) : ?>
        <p class="box-list-caption"><span>ボックスID</span><?php echo $box['box_id']; ?></p>
        <?php endif;?>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-12">
        <?php if (!empty($box['inbound_date'])) : ?>
        <p class="box-list-caption"><span>入庫日</span><?php echo $this->app->formatYmdKanji($box['inbound_date']); ?></p>
        <?php endif;?>
        <?php if (!empty($box['outbound_date'])) : ?>
        <p class="box-list-caption"><span>出庫日</span><?php echo $this->app->formatYmdKanji($box['outbound_date']); ?></p>
        <?php endif;?>
      </div>
    </div>
</div>
