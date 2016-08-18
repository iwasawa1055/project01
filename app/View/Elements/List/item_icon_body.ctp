<?php $url = '/item/detail/' . $item['item_id']; ?>
<a href="<?php echo $url; ?>">
  <?php if (!empty(Hash::get($item, 'image_first.image_url'))) : ?>
  <?php /* test 販売中アイコン */ ?>
  <p class="sale-icon"><i class="fa fa-shopping-basket"></i></p>
  <img src="<?php echo Hash::get($item, 'image_first.image_url'); ?>" alt="<?php echo $item['item_id'] ?>" class="item">
  <?php endif; ?>
</a>
