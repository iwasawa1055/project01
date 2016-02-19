<?php
  $url = '/item/detail/' . $item['item_id'];
  $checked = $default;
  if (array_key_exists('outbound_list', $item)) {
      $checked = $item['outbound_list'];
  }
  if (empty($class)) {
      $class = 'outbound_select_checkbox';
  }
?>

<div class="panel-body">
  <div class="row">
    <div class="col-lg-2 col-md-2 col-sm-12">
      <a href="<?php echo $url ?>">
          <img src="<?php echo $item['images_item']['image_url'] ?>" alt="<?php echo $item['item_id'] ?>" width="100px" height="100px" class="item">
      </a>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
      <h3><a href="<?php echo $url ?>"><?php echo $item['item_name']; ?></a>
      </h3>
    </div>
    <div class="col-lg-4 col-md-4 col-xs-12 <?php echo $class; ?>">
        <input type="checkbox">
        <?php echo $this->Form->checkbox("item_id.${item['item_id']}", ['checked' => $checked, 'hiddenField' => true]); ?>
        <button class="btn btn-danger btn-md btn-block btn-detail"></button>
    </div>
  </div>
  <?php echo $this->Form->error("item_id.${item['item_id']}", null, ['wrap' => 'div']) ?>
</div>
