<div id="page-wrapper" style="min-height: 483px;">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row col-lg-12 none-title">
              <div class="col-lg-4 col-md-12">
                <div class="panel panel-default">
                  <div class="panel-heading mono-box">
                    <h3>minikuraMONO</h3>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <label>オーダー数</label>
                      <?php echo $this->Form->select('Order.mono_num', $this->order->kitOrderNum(), ['class' => 'form-control', 'empty' => null, 'error' => false]); ?>
                    </div>
                  </div>
                  <div class="panel-footer">
                    <p class="help-block">月額保管料：250円/月</p>
                    <p class="help-block">ボックス代金：250円</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-12">
                <div class="panel panel-default">
                  <div class="panel-heading hako-box">
                    <h3>minikurHAKO</h3>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <label>オーダー数</label>
                      <?php echo $this->Form->select('Order.hako_num', $this->order->kitOrderNum(), ['class' => 'form-control', 'empty' => null, 'error' => false]); ?>
                    </div>
                  </div>
                  <div class="panel-footer">
                    <p class="help-block">月額保管料：200円/月</p>
                    <p class="help-block">ボックス代金：200円</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-12">
                <div class="panel panel-default">
                  <div class="panel-heading cleaning-box">
                    <h3>クリーニングパック</h3>
                  </div>
                  <div class="panel-body">
                    <div class="form-group">
                      <label>オーダー数</label>
                      <?php echo $this->Form->select('Order.cleaning_num', $this->order->kitOrderNum(), ['class' => 'form-control', 'empty' => null, 'error' => false]); ?>
                    </div>
                  </div>
                  <div class="panel-footer">
                    <p class="help-block">半年間保管料：9,000円</p>
                    <p class="help-block">ボックス代金：0円</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group col-lg-12">
              <label>カード情報</label>
              <select class="form-control">
                <option>以下からお選びください</option>
                <option>xxxx-xxxx-xxxx-0000　TOMONOSUKE ICHIKAWA</option>
                <option>xxxx-xxxx-xxxx-0000　TOMONOSUKE ICHIKAWA</option>
              </select>
            </div>
            <div class="form-group col-lg-12">
              <label>セキュリティコード</label>
              <input class="form-control" placeholder="セキュリティコードを入力してください">
              <p class="help-block">カード裏面に記載された３〜4桁の番号をご入力ください。</p>
              <p class="security_code"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">※セキュリティコードとは？</a>
              </p>
              <div id="collapseOne" class="panel-collapse collapse panel panel-default">
                <div class="panel-body">
                  <p>セキュリティコードとは、クレジットカード番号とは異なる、3桁または4桁の番号で、第三者がお客様のクレジットカードを不正利用出来ないようにする役割があります。</p>
                  <p>カードの種類によってセキュリティコードの記載位置が異なりますので、下記をご覧ください。</p>
                  <h4>Visa、Mastercard等の場合</h4>
                  <p>カードの裏面の署名欄に記入されている3桁の番号です。</p>
                  <p>カード番号の下3桁か、その後に記載されています。</p>
                  <p><img src="../images/cvv2visa.gif" alt=""></p>
                  <h4>American Expressの場合</h4>
                  <p>カードの表面に記入されている4桁の番号です。</p>
                  <p>カード番号の下4桁か、その後に記載されています。</p>
                  <p><img src="../images/cvv2amex.gif" alt=""></p>
                </div>
              </div>
            </div>
            <div class="form-group col-lg-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title">
                    <span class="credit-notice"><a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">クレジットカード情報の取扱について</a>
                    </span>
                  </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse">
                  <div class="panel-body">
                    <p>クレジットカード情報をご本人様より直接ご提供いただく事に関し、以下の項目を明示いたします。
                      ご同意いただける場合は注文手続きへお進みください。</p>
                    <label>クレジットカード情報の利用目的</label>
                    <p>当社サービスのご利用にクレジットカード決済を希望するお客様のサービス代金決済処理のため、および同決済に関するお問い合わせに対応するため</p>
                    <label>取得者名</label>
                    <p>寺田倉庫株式会社</p>
                    <label>提供先名</label>
                    <p>株式会社日本カードネットワーク及びGMOペイメントゲートウェイ<br>
                      （以下「決済代行会社」といいます）</p>
                    <label>保存期間</label>
                    <p>当社サービスのご利用にかかる契約・利用目的の終了時およびこれに付随する業務の終了時から７年間また、クレジットカード情報を決済代行会社に提供することについて以下の項目を明示いたします。</p>
                    <label>決済代行会社に提供する目的</label>
                    <p>当社サービスのご利用にクレジットカード決済を希望するお客様のサービス代金決済処理のため、および同決済に関するお問い合わせに対応するため</p>
                    <label>決済代行会社に提供する個人情報の項目</label>
                    <p>クレジットカード契約者名、クレジットカード番号、有効期限、セキュリティコード（CVV）</p>
                    <label>クレジットカード情報提供の手段または方法</label>
                    <p>WebサイトからのSSL通信による伝送</p>
                    <label>クレジットカード情報の提供を受ける者または提供を受ける者の組織の種類および属性</label>
                    <p>クレジットカード決済代行会社</p>
                    <label>当社と決済代行会社との間の個人情報の取り扱いに関する契約</label>
                    <p>有り</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group col-lg-12">
              <label>お届け先</label>
              <select class="form-control">
                <option>以下からお選びください</option>
                <option>〒000-0000 東京都品川区東品川2-2-33 Nビル 5階　市川　倫之介</option>
                <option>〒000-0000 東京都品川区東品川2-2-33 Nビル 5階　市川　倫之介</option>
                <option>お届先を追加する</option>
              </select>
            </div>
            <div class="form-group col-lg-12">
              <label>お届け希望日</label>
              <select class="form-control">
                <option>00月00日</option>
                <option>00月00日</option>
                <option>00月00日</option>
                <option>00月00日</option>
                <option>00月00日</option>
              </select>
            </div>
            <div class="form-group col-lg-12">
              <label>お届け希望時間</label>
              <select class="form-control">
                <option>午前中</option>
                <option>12時〜</option>
                <option>14時〜</option>
                <option>16時〜</option>
                <option>18時〜</option>
              </select>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
            <a class="btn btn-primary btn-lg btn-block animsition-link" href="#">クリア</a>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
            <a class="btn btn-danger btn-lg btn-block animsition-link" href="../order/confirm.html">注文内容の確認</a>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>