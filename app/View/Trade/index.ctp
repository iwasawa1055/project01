<?php $this->Html->css('lightbox.min', ['block' => 'css']); ?>
<?php $this->Html->script('lightbox.min', ['block' => 'scriptMinikura']);?>
<!--
<script src="<?php echo Configure::read('site.mypage.url');?>/js/lightbox.min..js"></script>
-->
<section id="detail">
  <div class="container">
  <?php if (! empty($sales)):?>
    <div>
      <h2><?php echo h($sales['sales_title']);?></h2>
    </div>
    <div class="row">
      <div class="info">
        <div class="photo">
          <a href="<?php echo $sales['item_image'][0]['image_url']; ?>" data-lightbox="item-photo" data-title="<?php echo h($sales['sales_title']); ?>">
          <img src="<?php echo $sales['item_image'][0]['image_url']; ?>" alt="<?php echo $sales['sales_title']; ?>" ></a>
        </div>
        <div class="caption">
          <p><?php echo nl2br( h($sales['sales_note']) );?></p>
          <p class="price">価格：<?php echo number_format(h(floor($sales['price'])));?>円 (税込)</p>
          <?php if ($is_soldout):?>
              <p class="soldout">SOLD OUT</p>
          <?php endif;?>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="text-center">
      <?php if ($sales['sales_status'] === SALES_STATUS_ON_SALE):?>
        <a href="<?php echo Configure::read('site.mypage.url');?>/purchase/<?php echo h($sales['sales_id']);?>" class="animsition-link btn commit">この商品を購入する</a>
      <?php endif;?>
      </div>
    </div>
    
  <?php else:?>
    <div>
      <h2 class="text-center">該当の商品が存在しません</h2>
    </div>

  <?php endif;?>
  </div>
</section>
