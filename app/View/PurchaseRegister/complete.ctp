<section id="form">
  <div class="container">
  <?php  echo $this->Flash->render();?>  
    <div>
      <h2>購入完了（5/5）</h2>
    </div>
    <?php echo $this->element('purchase_item', ['sales' => $sales]); ?>
  <?php if (empty($invalid_CustomerRegistInfo) && empty($invalid_CreditCard) && empty($apierror_CreditCard)) : ?>
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
          <a href="<?php echo Configure::read('site.market.url'); ?><?php echo $sales_id; ?>" class="animsition-link btn">この商品のページに戻る</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <?php if (!empty($invalid_CustomerRegistInfo) && $invalid_CustomerRegistInfo) : ?>
    <div class="row">
      <div class="form">
        <div class="address">
          <h3>購入手続きが完了できませんでした。</h3>
          <p>配送先情報を再入力してください。</p>
        </div>
      </div>
      <div class="row">
        <div class="text-center btn-commit">
          <a href="<?php echo '/purchase/' . $sales_id ?>" class="animsition-link btn">メールアドレス入力ページヘ</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <?php if (!empty($invalid_CreditCard) && $invalid_CreditCard) : ?>
    <div class="row">
      <div class="form">
        <div class="address">
          <h3>購入手続きが完了できませんでした。</h3>
          <p>利用可能なクレジットカードを再入力してください。</p>
        </div>
      </div>
      <div class="row">
        <div class="text-center btn-commit">
          <a href="/purchase/register/credit" class="animsition-link btn">クレジットカード情報入力ページヘ</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
  <?php if (!empty($apierror_CreditCard) && $apierror_CreditCard) : ?>
    <div class="row">
      <div class="form">
        <div class="address">
          <h3>購入手続きが完了できませんでした。</h3>
          <p>利用可能なクレジットカードを再入力してください。</p>
        </div>
      </div>
      <div class="row">
        <div class="text-center btn-commit">
          <a href="/customer/credit_card/add" class="animsition-link btn">クレジットカード情報入力ページヘ</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
  </div>
</section>
