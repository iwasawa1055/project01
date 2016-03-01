<?php
  $url = '/box/detail/' . $box['box_id'];
  $checked = $default;
  if (array_key_exists('outbound_list', $box)) {
      $checked = $box['outbound_list'];
  }
  if (empty($class)) {
      $class = 'outbound_select_checkbox';
  }
?>
<div class="panel-body <?php echo $this->MyPage->boxClassName($box); ?>">
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12">
      <h3><a href="<?php echo $url ?>"><?php echo $box['box_name']; ?></a>
      </h3>
    </div>
    <div class="col-lg-4 col-md-4 col-xs-12 <?php echo $class; ?>">
        <?php echo $this->Form->checkbox("box_id.${box['box_id']}", ['checked' => $checked, 'hiddenField' => true]); ?>
        <button class="btn btn-danger btn-md btn-block btn-detail"></button>
    </div>
  </div>
  <?php echo $this->Form->error("box_id.${box['box_id']}", null, ['wrap' => 'p']) ?>
</div>
