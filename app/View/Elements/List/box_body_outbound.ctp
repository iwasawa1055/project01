<?php
  $url = '/box/detail/' . $box['box_id'];
  $checked = true;
  if (array_key_exists('outbound_list', $box)) {
      $checked = $box['outbound_list'];
  }
?>
<div class="panel-body <?php echo $this->MyPage->kitCdToClassName($box['kit_cd']); ?>">
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12">
      <h3><a href="<?php echo $url ?>"><?php echo $box['box_name'] ?></a>
      </h3>
    </div>
    <div class="col-lg-4 col-md-4 col-xs-12 outbound_select_checkbox">
        <input type="checkbox">
        <?php echo $this->Form->checkbox("box_id.${box['box_id']}", ['checked' => $checked, 'hiddenField' => false]); ?>
        <button class="btn btn-danger btn-md btn-block btn-detail"></button>
    </div>
  </div>
</div>
