<section id="form">
  <div class="container narrow">
    <div>
      <h2>購入完了（4/4）</h2>
    </div>
    <?php echo $this->element('purchase_item', ['sales' => $sales]); ?>
    <div class="row">
      <div class="form">
        <div class="address">
          <h3>購入が完了しました。</h3>
          <p>購入情報はminikuraの<a href="<?php echo Configure::read('site.mypage.url');?>" class="link">マイページ</a>
            で確認することができます</p>
        </div>
      </div>
      <div class="row">
        <div class="text-center btn-commit">
          <a href="<?php echo Configure::read('site.market.url'); ?><?php echo $sales_id; ?>" class="animsition-link btn">この商品のページに戻る</a>
        </div>
      </div>
    </div>
  </div>
</section>
