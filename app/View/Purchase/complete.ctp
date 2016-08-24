<section id="form">
  <div class="container narrow">
    <div>
      <h2>購入完了（4/4）</h2>
    </div>
    <div class="row">
      <div class="info">
        <div class="photo">
          <img src="<?php echo $sales['item_image']['0']['image_url']; ?>" alt="" />
        </div>
        <div class="caption">
          <h3><?php echo h($sales['sales_title']); ?></h3>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form">
        <div class="address">
          <h3>購入が完了しました。</h3>
        </div>
      </div>
      <div class="row">
        <div class="text-center btn-commit">
          <a href="<?php echo Configure::read('site.static_content_url'); ?>/market/<?php echo $sales_id; ?>" class="animsition-link btn">この商品のページに戻る</a>
        </div>
      </div>
    </div>
  </div>
</section>
