<section id="form">
  <div class="container">
    <div>
      <h2>入力情報を確認（3/4）（4/5）</h2>
    </div>
    <div class="row">
      <div class="info">
        <div class="photo">
          <img src="/market/images/item.jpg" alt="" />
        </div>
        <div class="caption">
          <h3>極美品 NIKE FLYKNIT RACER us9 jp27cm フライニット 007極美品 NIKE FLYKNIT RACER us9 jp27cm フライニット 007</h3>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="form">
        <div class="a-confirm">
          <h3>メールアドレス</h3>
          <div class="form-group">
            <p>email@example.com</p>
          </div>
          <h3>お届け先情報</h3>
          <div class="form-group">
            <label>郵便番号</label>
            <p>000-0000</p>
          </div>
          <div class="form-group">
            <label>住所</label>
            <p>東京都品川区東品川2</p>
          </div>
          <div class="form-group">
            <label>番地</label>
            <p>2-33</p>
          </div>
          <div class="form-group">
            <label>建物名</label>
            <p> Nビル 5階</p>
          </div>
          <div class="form-group">
            <label>電話番号</label>
            <p> 000-0000-0000</p>
          </div>
          <div class="form-group">
            <label>お名前</label>
            <p> イチカワ　トモノスケ</p>
            <p> 市川　倫之介</p>
          </div>
        </div>
        <div class="c-confirm">
          <h3>クレジットカード情報</h3>
          <div class="form-group">
            <label>クレジットカード番号</label>
            <p>xxxx-xxxx-xxxx-0000</p>
          </div>
          <div class="form-group">
            <label>有効期限</label>
            <p>00月/0000年</p>
          </div>
          <div class="form-group">
            <label>クレジットカード名義</label>
            <p>TOMONOSUKE ICHIKAWA</p>
          </div>
        </div>
      </div>
      <div class="row">
      <?php echo $this->Form->create('Purchase', ['url' => '/purchase/'. '9999' . '/complete', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
        <div class="text-center btn-commit">
          <a href="/purchase/9999/complete" class="animsition-link btn">この内容で購入する（5/5）</a>
          <!-- <button type="submit" class="btn">この内容で購入する（5/5）</button> -->
        </div>
        <!-- <div class="text-center btn-commit">
          <a class="btn" href="/purchase/9999/input?back=true">戻る</a>
        </div> -->
      <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
</section>
