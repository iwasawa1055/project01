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
      <h3>
        <a href="<?php echo $url; ?>"><?php echo h($box['box_name']); ?></a>
      </h3>
    </div>
    <?php if (Hash::get($box, 'outbound_list_deny_box', $default)) : ?>
    <div class="col-lg-4 col-md-4 col-xs-12">
        <?php echo $this->Form->checkbox("box_id.${box['box_id']}", ['checked' => false, 'hiddenField' => true, 'style' => 'display:none;']); ?>
      <p class="error-message">このボックスは追加できません。<br>アイテムとして既に取り出しリストに追加されています。</p>
    </div>
    <?php elseif (Hash::get($box, 'outbound_list_deny_item', $default)) : ?>
    <div class="col-lg-4 col-md-4 col-xs-12">
      <?php echo $this->Form->checkbox("box_id.${box['box_id']}", ['checked' => false, 'hiddenField' => true, 'style' => 'display:none;']); ?>
      <p class="error-message">このボックス内のアイテムは追加できません。<br>ボックスとして既に取り出しリストに追加されています。</p>
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
