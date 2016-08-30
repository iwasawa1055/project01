    <div class="row">
      <div class="info">
        <div class="photo">
          <img src="<?php echo $sales['item_image']['0']['image_url']; ?>" alt="" />
        </div>
        <div class="caption">
          <h3><?php echo h($sales['sales_title']); ?></h3>
          <p class="price">価格：<?php echo h(number_format(floor($sales['price'])));?>円</p>
        </div>
      </div>
    </div>
