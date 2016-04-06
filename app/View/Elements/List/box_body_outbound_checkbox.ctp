<?php
$url = '/box/detail/' . $box['box_id'];
$checked = Hash::get($box, 'outbound_list_cehcked', $default);
if (empty($class)) {
    $class = 'outbound_select_checkbox';
}
?>
<a name="<?php echo $box['box_id'] ?>">
<div class="panel-body <?php echo $this->MyPage->boxClassName($box); ?>">
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12">
      <h3 class="boxitem-name">
        <a href="<?php echo $url; ?>"><?php echo h($box['box_name']); ?></a>
      </h3>
    </div>
    <?php if (Hash::get($box, 'outbound_list_deny', $default)) : ?>
    <div class="col-lg-4 col-md-4 col-xs-12">
      <?php echo $this->Form->checkbox("box_id.${box['box_id']}", ['checked' => false, 'hiddenField' => true, 'style' => 'display:none;']); ?>
      <p class="error-message"><?php echo $box['outbound_list_deny']; ?></p>
    </div>
    <?php else : ?>
    <div class="col-lg-4 col-md-4 col-xs-12 <?php echo $class; ?>">
      <?php echo $this->Form->checkbox("box_id.${box['box_id']}", ['checked' => $checked, 'hiddenField' => true]); ?>
      <button class="btn btn-danger btn-md btn-block btn-detail"></button>
    </div>
    <?php endif; ?>
  </div>
  <?php echo $this->Form->error("box_id.${box['box_id']}", null, ['wrap' => 'p']) ?>
</div>
