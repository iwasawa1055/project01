<section id="detail">
  <div class="container">
  <?php if (! empty($sales)):?>
    <div>
      <h2><?php echo h($sales['sales_title']);?></h2>
    </div>
    <div class="row">
      <div class="info">
        <div class="photo">
          <img src="<?php echo $sales['item_image'][0]['image_url'] ;?>" alt="" />
        </div>
        <div class="caption">
          <p><?php echo h($sales['sales_note']);?></p>
          <p class="price">価格：<?php echo h(floor($sales['price']));?>円</p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="text-center btn-commit">
        <a href="<?php echo Configure::read('site.mypage.url');?>/purchase/<?php echo h($sales['sales_id']);?>" class="animsition-link btn">この商品を購入する</a>
      </div>
    </div>
    
  <?php else:?>
    <div>
      <h2 class="text-center">該当の商品が存在しません</h2>
    </div>

  <?php endif;?>
  </div>
</section>
