<section id="form">
  <div class="container">
    <div>
      <h2>配送情報を選択（2/4）</h2>
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
          <div class="btn-orrection">
            <a href="/email/edit.html" class="animsition-link btn">メールアドレスを変更する</a>
          </div>
          <h3>お届け先情報</h3>
            <div class="form-group">
              <label>お届け先</label>
              <select class="form-control">
                <option>以下からお選びください</option>
                <option>〒000-0000 東京都品川区東品川2-2-33 Nビル 5階　市川　倫之介</option>
                <option>〒000-0000 東京都品川区東品川2-2-33 Nビル 5階　市川　倫之介</option>
                <option>お届先を追加する</option>
              </select>
            </div>
            <div class="form-group">
              <label>お届け希望日</label>
              <select class="form-control">
                <option>00月00日</option>
                <option>00月00日</option>
                <option>00月00日</option>
                <option>00月00日</option>
                <option>00月00日</option>
              </select>
            </div>
            <div class="form-group">
              <label>お届け希望時間</label>
              <select class="form-control">
                <option>午前中</option>
                <option>12時〜</option>
                <option>14時〜</option>
                <option>16時〜</option>
                <option>18時〜</option>
              </select>
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
          <div class="form-group ">
            <input class="form-control" placeholder="セキュリティコード">
            <p class="help-block">カード裏面に記載された３〜4桁の番号をご入力ください。</p>
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="link">※セキュリティコードとは？</a>
            <div id="collapseOne" class="panel-collapse collapse panel panel-default">
              <div class="panel-body">
                <p>セキュリティコードとは、クレジットカード番号とは異なる、3桁または4桁の番号で、第三者がお客様のクレジットカードを不正利用出来ないようにする役割があります。</p>
                <p>カードの種類によってセキュリティコードの記載位置が異なりますので、下記をご覧ください。</p>
                <h4>Visa、Mastercard等の場合</h4>
                <p>カードの裏面の署名欄に記入されている3桁の番号です。</p>
                <p>カード番号の下3桁か、その後に記載されています。</p>
                <p><img src="images/cvv2visa.gif" alt="" /></p>
                <h4>American Expressの場合</h4>
                <p>カードの表面に記入されている4桁の番号です。</p>
                <p>カード番号の下4桁か、その後に記載されています。</p>
                <p><img src="images/cvv2amex.gif" alt="" /></p>
              </div>
            </div>
          </div>
          <div class="btn-orrection">
            <a href="/credit_card/edit.html" class="animsition-link btn">クレジットカード情報を修正する</a>
          </div>
        </div>
      </div>
      <div class="row">
      <?php echo $this->Form->create('Purchase', ['url' => '/purchase/'. '9999' . '/confirm', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
        <div class="text-center btn-commit">
          <a href="/purchase/9999/confirm" class="animsition-link btn">この内容で確認する（3/4）</a>
          <!-- <button type="submit" class="btn">この内容で購入する（5/5）</button> -->
        </div>
      <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
</section>
