<section id="form">
  <div class="container">
    <div>
      <h2>購入完了（5/5）</h2>
    </div>
    <div class="row">
      <div class="info">
        <div class="photo">
          <img src="<?php echo $sale_image; ?>" alt="" />
        </div>
        <div class="caption">
          <h3><?php echo h($sales_title); ?></h3>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form">
        <div class="address">
          <h3>購入が完了しました。</h3>
          <p>メールアドレス：<?php echo h($email); ?></p>
          <p>パスワード：設定したパスワード</p>
          <p>でログインしてお買い物ができます。</p>
          <p>また購入情報はminikuraの<a href="<?php echo Configure::read('site.mypage.url'); ?>" class="link">マイページ</a>
            で確認することができます</p>
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
