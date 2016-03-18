<?php
$url = '/item/detail/' . $item['item_id'];
$box = $item['box'];
?>
<a name="<?php echo $item['item_id']; ?>">
<div class="panel-body <?php echo $this->MyPage->boxClassName($box); ?>">
  <div class="row">
    <div class="col-lg-2 col-md-2 col-sm-12">
      <a href="<?php echo $url; ?>">
        <?php if (!empty(Hash::get($item, 'image_first.image_url'))) : ?>
        <img src="<?php echo Hash::get($item, 'image_first.image_url'); ?>" alt="<?php echo $item['item_id']; ?>" width="100px" height="100px" class="item">
        <?php endif; ?>
      </a>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12">
      <h3>
          <a href="<?php echo $url; ?>"><?php echo h($item['item_name']); ?></a>
      </h3>
    </div>
    <div class="col-lg-4 col-md-4 col-xs-12">
    <?php echo $this->Form->create(false, ['url' => '/outbound/item', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
    <?php echo $this->Form->hidden("item_id.${item['item_id']}", ['value' => '0']); ?>
    <span class="col-xs-12 col-lg-12">
        <button type="submit" class="btn btn-warning btn-md btn-block btn-detail">取り出しリストから削除</button>
    </span>
    <?php echo $this->Form->end(); ?>
    </div>
  </div>
</div>
