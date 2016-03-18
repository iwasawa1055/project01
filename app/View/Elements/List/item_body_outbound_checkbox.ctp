<?php
$url = '/item/detail/' . $item['item_id'];
$checked = Hash::get($item, 'outbound_list_cehcked', $default);
if (empty($class)) {
  $class = 'outbound_select_checkbox';
}
?>
<a name="<?php echo $item['item_id']; ?>">
<div class="panel-body">
  <div class="row">
    <div class="col-lg-2 col-md-2 col-sm-12">
      <a href="<?php echo $url; ?>">
          <?php if (!empty(Hash::get($item, 'image_first.image_url'))) : ?>
          <img src="<?php echo Hash::get($item, 'image_first.image_url'); ?>" alt="<?php echo $item['item_id'] ?>" width="100px" height="100px" class="item">
          <?php endif; ?>
      </a>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
      <h3><a href="<?php echo $url; ?>"><?php echo h($item['item_name']); ?></a>
      </h3>
    </div>
    <?php if (Hash::get($item, 'outbound_list_deny_item', $default)) : ?>
    <div class="col-lg-4 col-md-4 col-xs-12">
      <?php echo $this->Form->checkbox("item_id.${item['item_id']}", ['checked' => false, 'hiddenField' => true, 'style' => 'display:none;']); ?>
      <p class="error-message">このアイテムは追加できません。<br>ボックスとして既に取り出しリストに追加されています。</p>
    </div>
    <?php else : ?>
    <div class="col-lg-4 col-md-4 col-xs-12 <?php echo $class; ?>">
        <?php echo $this->Form->checkbox("item_id.${item['item_id']}", ['checked' => $checked, 'hiddenField' => true]); ?>
        <button class="btn btn-danger btn-md btn-block btn-detail"></button>
    </div>
    <?php endif; ?>
  </div>
  <?php echo $this->Form->error("item_id.${item['item_id']}", null, ['wrap' => 'p']) ?>
</div>
