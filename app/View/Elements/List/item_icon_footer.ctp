<div class="panel-footer box-item-name">
  <?php if (!empty($item['item_name'])) : ?>
  <p class="box-list-caption">
    <span><?php echo __('item_name'); ?></span>
    <?php echo h($item['item_name']); ?>
  </p>
  <?php endif; ?>
  <?php if (!empty($item['item_id'])) : ?>
  <p class="box-list-caption">
    <span><?php echo __('item_id'); ?></span><?php echo $item['item_id']; ?>
  </p>
  <?php endif; ?>
</div>
