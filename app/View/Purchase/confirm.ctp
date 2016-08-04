  <div class="container">
    <div class="row">
      <div>
        <h2>購入確認</h2>
      </div>
    </div>
    
    <div class="row">
      <div class="step">
        <div>
          <img src="/images/xxx_xxxx.jpg" alt="" />
        </div>
        <div class="caption">
          <h3>商品名商品名商品名商品名商品名商品名商品名商品名商品名商品名</h3>
          <p>商品説明商品説明商品説明商品説明商品説明商品説明商品説明商品説明商品説明商品説明商品説明商品説明商品説明商品説明</p>
          <p>000,000,000,000,000円</p>
        </div>
      </div>
    </div>
    <div class="row">


      <?php echo $this->Form->create('Purchase', ['url' => '/purchase/'. '9999' . '/complete', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
      <div class="form-group col-lg-12">
        <label>カード情報</label>
        <p class="form-control-static">1234-5678-1234-5678 TEST TEST<?php // echo h($default_payment_text); ?></p>
      </div>
      <div class="form-group col-lg-12">
        <label>お届け先</label>
        <p class="form-control-static">〒154-0001 東京都渋谷区恵比寿123456びるビル　せいせい　めいめい<?php // echo h($default_payment_text); ?></p>
      </div>
      <div class="form-group col-lg-12">
        <label>お届け希望日時</label>
        <p class="form-control-static">2016/08/06 (土) １４～１６時<?php // echo h($default_payment_text); ?></p>
      </div>

      <div class="text-center btn-buy">
        <span class="col-lg-6 col-md-6 col-xs-12">
          <a class="btn btn-primary btn-lg btn-block" href="/purchase/9999/input?back=true">戻る</a>
        </span>
        <span class="col-lg-6 col-md-6 col-xs-12">
          <button type="submit" class="btn btn-danger btn-lg btn-block">購入を確定する</button>
        </span>
      </div>
      <?php echo $this->Form->end(); ?>


    </div>
  </div>
