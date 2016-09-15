<?php $url = '/item/detail/' . $item['item_id']; ?>
<a href="<?php echo $url; ?>">
  <?php if (!empty(Hash::get($item, 'image_first.image_url'))) : ?>
    <?php 
      /*  販売中〜送金処理中アイコン */
      $sales_icon_flag = false;
      if (!empty($item['sales'])) {
          foreach($item['sales'] as $sales) {
              if($sales['sales_status'] >= SALES_STATUS_ON_SALE && $sales['sales_status'] <= SALES_STATUS_REMITTANCE_COMPLETED ) {
                  $sales_icon_flag = true;
                  break;
              }
          }
      }
    ?>
    <?php if ($sales_icon_flag === true):?>
  <p class="sale-icon"><i class="fa fa-shopping-basket"></i></p>
    <?php endif;?>
  <img src="<?php echo Hash::get($item, 'image_first.image_url'); ?>" alt="<?php echo $item['item_id'] ?>" class="item">
  <?php endif; ?>
</a>
