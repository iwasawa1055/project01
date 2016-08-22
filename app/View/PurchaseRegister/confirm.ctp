<section id="form">
  <div class="container">
    <div>
      <h2>入力情報を確認（4/5）</h2>
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
            <p><?php echo h($cutomerEntry['email']); ?></p>
          </div>
          <div class="form-group">
            <label>ニュースレターの配信</label>
            <p><?php echo CUSTOMER_NEWSLETTER[$cutomerEntry['newsletter']] ?></p>
          </div>
          <div class="btn-orrection">
            <a href="<?php echo '/purchase/' . $sales_id ?>" class="animsition-link btn">メールアドレスを修正する</a>
          </div>
          <h3>お届け先情報</h3>
          <div class="form-group">
            <label>郵便番号</label>
            <p><?php echo h($customerInfo['postal']); ?></p>
          </div>
          <div class="form-group">
            <label>住所</label>
            <p><?php echo $this->CustomerInfo->setPrefAddress1($customerInfo); ?></p>
          </div>
          <div class="form-group">
            <label>番地</label>
            <p><?php echo h($customerInfo['address2']); ?></p>
          </div>
          <div class="form-group">
            <label>建物名</label>
            <p><?php echo h($customerInfo['address3']); ?></p>
          </div>
          <div class="form-group">
            <label>電話番号</label>
            <p><?php echo h($customerInfo['tel1']); ?></p>
          </div>
          <div class="form-group">
            <label>お名前</label>
            <p><?php echo $this->CustomerInfo->setName($customerInfo); ?></p>
          </div>
          <div class="form-group">
            <label>生年月日（西暦）</label>
            <p><?php echo $this->CustomerInfo->setBirth($customerInfo); ?></p>
          </div>
          <div class="form-group">
            <label>性別</label>
            <p><?php echo CUSTOMER_GENDER[$customerInfo['gender']] ?></p>
          </div>
          <div class="form-group">
            <label>お届け希望日時</label>
            <p></p>
          </div>
          <div class="btn-orrection">
            <a href="/purchase/register/address" class="animsition-link btn">お届け先情報を修正する</a>
          </div>
        </div>
        <div class="c-confirm">
          <h3>クレジットカード情報</h3>
          <div class="form-group">
            <label>クレジットカード番号</label>
            <p><?php echo h($paymentCard['card_no']); ?></p>
          </div>
          <div class="form-group">
            <label>有効期限</label>
            <p><?php echo $paymentCard['expire_month']; ?>月/<?php echo $paymentCard['expire_year_disp']; ?>年</p>
          </div>
          <div class="form-group">
            <label>クレジットカード名義</label>
            <p><?php echo h($paymentCard['holder_name']); ?></p>
          </div>
          <div class="btn-orrection">
            <a href="/purchase/register/credit" class="animsition-link btn">クレジットカード情報を修正する</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="text-center btn-commit">
          <div class="btn-orrection">
            <a class="btn btn-info btn-xs animsition-link" href="https://minikura.com/use_agreement/" target="_blank">minikura利用規約</a>
          </div>
          <div class="checkbox">
            <label>
              <input name="remember" type="checkbox" value="Remember Me">
              minikura利用規約に同意する </label>
          </div>
          <a href="/purchase/register/complete" class="animsition-link btn">この内容で購入する（5/5）</a>
        </div>
      </div>
    </div>
  </div>
</section>
