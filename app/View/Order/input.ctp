<?php $this->Html->script('minikura/input', ['block' => 'scriptMinikura']); ?>
<?php $this->validationErrors['OrderKit'] = $validErrors; ?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <?php echo $this->Form->create('OrderKit', ['url' => ['controller' => 'order', 'action' => 'confirm'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true, 'class' => 'select-add-address-form']); ?>
          <div class="panel-body">
            <div class="row col-lg-12 none-title">
              <div class="col-lg-12 col-md-12">
                <div class="panel panel-default">
                  <div class="panel-heading mono-box">
                    <h3>minikuraMONO<a class="remodal-open" data-remodal-target="modal-mono" href="#"><i class="fa fa-question"></i></a>
                    </h3>
                    <p class="help-block">月額保管料：250円/月</p>
                    <p class="help-block">ボックス代金：250円</p>
                  </div>
                  <div class="panel-body">
                    <div class="col-lg-4 col-md-12">
                      <div class="panel panel-default">
                        <div class="panel-body">
                          <div class="form-group">
                            <label>レギュラーボックス<a class="remodal-open" href="#" data-remodal-target="modal-regular"><i class="fa fa-question"></i></a>
                            </label>
                            <?php echo $this->Form->select('OrderKit.mono_num', $this->Order->kitOrderNum(), ['class' => 'form-control', 'empty' => '選択してください', 'error' => false]); ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                      <div class="panel panel-default">
                        <div class="panel-body">
                          <div class="form-group">
                            <label>アパレルボックス<a class="remodal-open" href="#" data-remodal-target="modal-apparel"><i class="fa fa-question"></i></a>
                            </label>
                            <?php echo $this->Form->select('OrderKit.mono_appa_num', $this->Order->kitOrderNum(), ['class' => 'form-control', 'empty' => '選択してください', 'error' => false]); ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                      <div class="panel panel-default">
                        <div class="panel-body">
                          <div class="form-group">
                            <label>ブックボックス<a class="remodal-open" href="#" data-remodal-target="modal-book"><i class="fa fa-question"></i></a>
                            </label>
                            <?php echo $this->Form->select('OrderKit.mono_book_num', $this->Order->kitOrderNum(), ['class' => 'form-control', 'empty' => '選択してください', 'error' => false]); ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12 col-md-12">
                <div class="panel panel-default">
                  <div class="panel-heading hako-box">
                    <h3>minikuraHAKO<a class="remodal-open" href="#" data-remodal-target="modal-hako"><i class="fa fa-question"></i></a>
                    </h3>
                    <p class="help-block">月額保管料：200円/月</p>
                    <p class="help-block">ボックス代金：200円</p>
                  </div>
                  <div class="panel-body">
                    <div class="col-lg-4 col-md-12">
                      <div class="panel panel-default">
                        <div class="panel-body">
                          <div class="form-group">
                            <label>レギュラーボックス<a class="remodal-open" href="#" data-remodal-target="modal-regular"><i class="fa fa-question"></i></a>
                            </label>
                            <?php echo $this->Form->select('OrderKit.hako_num', $this->Order->kitOrderNum(), ['class' => 'form-control', 'empty' => '選択してください', 'error' => false]); ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                      <div class="panel panel-default">
                        <div class="panel-body">
                          <div class="form-group">
                            <label>アパレルボックス<a class="remodal-open" href="#" data-remodal-target="modal-apparel"><i class="fa fa-question"></i></a>
                            </label>
                            <?php echo $this->Form->select('OrderKit.hako_appa_num', $this->Order->kitOrderNum(), ['class' => 'form-control', 'empty' => '選択してください', 'error' => false]); ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                      <div class="panel panel-default">
                        <div class="panel-body">
                          <div class="form-group">
                            <label>ブックボックス<a class="remodal-open" href="#" data-remodal-target="modal-book"><i class="fa fa-question"></i></a>
                            </label>
                            <?php echo $this->Form->select('OrderKit.hako_book_num', $this->Order->kitOrderNum(), ['class' => 'form-control', 'empty' => '選択してください', 'error' => false]); ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-12 col-md-12">
                <div class="panel panel-default">
                  <div class="panel-heading cleaning-box">
                    <h3>クリーニングパック<a class="remodal-open" href="#" data-remodal-target="modal-cleaning"><i class="fa fa-question"></i></a>
                    </h3>
                    <p class="help-block">半年間保管料：12,000円</p>
                    <p class="help-block">ボックス代金：0円</p>
                  </div>
                  <div class="panel-body">
                    <div class="col-lg-4 col-md-12">
                      <div class="panel panel-default">
                        <div class="panel-body">
                          <div class="form-group">
                            <label>オーダー数</label>
                            <?php echo $this->Form->select('OrderKit.cleaning_num', $this->Order->kitOrderNum(), ['class' => 'form-control', 'empty' => '選択してください', 'error' => false]); ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-12 col-md-12">
              <?php echo $this->Form->error('OrderKit.mono_num', null, ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('OrderKit.hako_num', null, ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('OrderKit.cleaning_num', null, ['wrap' => 'p']) ?>
            </div>
          <?php if (!$customer->isEntry() && !$customer->isCustomerCreditCardUnregist() && !$customer->isCorprateCreditCardUnregist()) : ?>
            <?php if ($customer->isPrivateCustomer() || !$customer->getCorporatePayment()) : ?>
            <div class="form-group col-lg-12">
              <label>カード情報</label>
              <?php echo $this->Form->select('OrderKit.card_seq', $this->Order->setDefalutPayment($default_payment), ['class' => 'form-control', 'empty' => null, 'error' => false]); ?>
              <?php echo $this->Form->error('OrderKit.card_seq', null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12">
              <label>セキュリティコード</label>
              <?php echo $this->Form->input('OrderKit.security_cd', ['class' => "form-control", 'placeholder'=>'セキュリティコードを入力してください', 'maxlength' => 4, 'error' => false]); ?>
              <?php echo $this->Form->error('OrderKit.security_cd', null, ['wrap' => 'p']) ?>
              <p class="help-block">カード裏面に記載された 3〜4桁の番号をご入力ください。</p>
              <p class="security_code"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">※セキュリティコードとは？</a>
              </p>
              <div id="collapseOne" class="panel-collapse collapse panel panel-default">
                <div class="panel-body">
                  <p>セキュリティコードとは、クレジットカード番号とは異なる、3桁または4桁の番号で、第三者がお客様のクレジットカードを不正利用出来ないようにする役割があります。</p>
                  <p>カードの種類によってセキュリティコードの記載位置が異なりますので、下記をご覧ください。</p>
                  <h4>Visa、Mastercard等の場合</h4>
                  <p>カードの裏面の署名欄に記入されている3桁の番号です。</p>
                  <p>カード番号の下3桁か、その後に記載されています。</p>
                  <p><img src="/images/cvv2visa.gif" alt="" /></p>
                  <h4>American Expressの場合</h4>
                  <p>カードの表面に記入されている4桁の番号です。</p>
                  <p>カード番号の下4桁か、その後に記載されています。</p>
                  <p><img src="/images/cvv2amex.gif" alt="" /></p>
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
                    <p>株式会社日本カードネットワーク及びGMOペイメントゲートウェイ<br />
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
            <?php endif; ?>
            <div class="form-group col-lg-12">
              <label>お届け先</label>
              <?php echo $this->Form->select('OrderKit.address_id', $this->Order->setAddress($address), ['class' => 'form-control select-add-address', 'empty' => '選択してください', 'error' => false]); ?>
              <?php echo $this->Form->error('OrderKit.address_id', null, ['wrap' => 'p']) ?>
              <?php if (!$this->Form->isFieldError('OrderKit.address_id')) : ?>
              <?php echo $this->Form->error('OrderKit.lastname', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('OrderKit.lastname_kana', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('OrderKit.firstname', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('OrderKit.firstname_kana', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('OrderKit.name', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('OrderKit.tel1', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('OrderKit.postal', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('OrderKit.pref', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('OrderKit.address', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('OrderKit.address1', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('OrderKit.address2', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('OrderKit.address3', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php endif; ?>
            </div>
            <div class="form-group col-lg-12">
              <label>お届け希望日時</label>
                <select name="datetime_cd" id="datetime_cd" class="select-delivery focused">
                  <option value="">以下からお選びください</option>
                  <?php foreach ( CakeSession::read('Order.select_delivery_list') as $key => $value ) {?>
                   <option value="<?php echo $value->datetime_cd;?>"<?php if ( $value->datetime_cd === CakeSession::read('Address.datetime_cd') ) echo " selected";?>><?php echo $value->text;?></option>
                  <?php } ?>
                </select>
            </div>
          <?php endif; ?>
            <span class="col-lg-12 col-md-12 col-xs-12">
              <button type="submit" class="btn btn-danger btn-lg btn-block">注文内容を確認する</button>
            </span>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
  <!--modal MONO-->
  <div class="remodal" data-remodal-id="modal-mono" data-remodal-options="hashTracking: false"  role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div>
      <h3>minikuraMONO</h3>
      <div><img src="/images/box_mono.png"></div>
      <p> ボックスを開封して30点までのアイテム撮影。<br />
        Webでアイテムを1点ごとに管理。</p>
      <p>月額保管料：250円/月 </p>
      <p>ボックス代金：250円 </p>
      <a class="remodal-link btn-xs" href="https://minikura.com/lineup/mono.html" target="_blank">もっと詳しく</a>
    </div>
  </div>
  <!--modal HAKO-->
  <div class="remodal" data-remodal-id="modal-hako" data-remodal-options="hashTracking: false" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div>
      <h3>minikuraHAKO</h3>
      <div><img src="/images/box_hako.png"></div>
      <p> 撮影なしのプライベート保管。<br />
        まとめて押入れの延長用に。</p>
      <p>月額保管料：200円/月 </p>
      <p>ボックス代金：200円 </p>
      <a class="remodal-link btn-xs" href="https://minikura.com/lineup/hako.html" target="_blank">もっと詳しく</a>
    </div>
  </div>

  <!--modal Cleaning-->
  <div class="remodal" data-remodal-id="modal-cleaning" data-remodal-options="hashTracking: false" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div>
      <h3>クリーニングパック</h3>
      <div><img src="/images/box_cleaning.png"></div>
      <p> 高品質クリーニング＋保管<br />
        1パック上限10点で12,000円でクリーニングと保管、送料も含まれます！</p>
      <p>送料＋クリーニング10点＋6ヶ月保管 </p>
      <a class="remodal-link btn-xs" href="https://minikura.com/lineup/cleaning.html" target="_blank">もっと詳しく</a>
    </div>
  </div>
  <!--modal Regular-->
  <div class="remodal" data-remodal-id="modal-regular" data-remodal-options="hashTracking: false" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div>
      <h3>レギュラーボックス</h3>
      <div><img src="/images/box_regular.png"></div>
      <p>縦38cm×横38cm×奥行き38cm</p>
      <p> <span>専用ボックスは宅配便の120サイズ（衣装ケース1箱分）の大きさです。<br />
        重量は最大20kgでアイテムは最大30点までつめられます。</span></p>
    </div>
  </div>
  <!--modal Apparel-->
  <div class="remodal" data-remodal-id="modal-apparel" data-remodal-options="hashTracking: false" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div>
      <h3>アパレルボックス</h3>
      <div><img src="/images/box_apparel.png"></div>
      <p>縦20cm×横60cm×奥行き38cm</p>
     <p> <span>専用ボックスは宅配便の120サイズ（衣装ケース1箱分）の大きさです。<br />
        重量は最大20kgでアイテムは最大30点までつめられます。</span></p>
    </div>
  </div>
  <!--modal Book-->
  <div class="remodal" data-remodal-id="modal-book" data-remodal-options="hashTracking: false" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
    <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div>
      <h3>ブックボックス</h3>
      <div><img src="/images/box_book.png"></div>
      <p>縦29cm×横42cm×奥行き33cm</p>
<p> <span>専用ボックスは宅配便の120サイズ（衣装ケース1箱分）の大きさです。<br />
        重量は最大20kgでアイテムは最大30点までつめられます。</span></p>
    </div>
  </div>
