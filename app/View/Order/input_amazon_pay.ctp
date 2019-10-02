<?php $this->Html->script('/js/order/input_amazon_pay.js?'.time(), ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('https://maps.google.com/maps/api/js?key=' . Configure::read('app.googlemap.api.key') . '&libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/address', ['block' => 'scriptMinikura']); ?>

<?php $this->Html->css('/css/dsn-amazon-pay.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/order/input_amazon_pay_dev.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/order/order_dev.css', ['block' => 'css']); ?>

  <?php echo $this->Form->create('PaymentAmazonKitAmazonPay', ['url' => ['controller' => 'order', 'action' => 'input_amazon_pay'], 'novalidate' => true]); ?>

    <div id="page-wrapper" class="lineup wrapper">
      <?php echo $this->Flash->render(); ?>

      <h1 class="page-header"><i class="fa fa-shopping-cart"></i> サービスの申し込み</h1>
      <ul class="l-banner">
        <li class="l-banner-dtl">
          <a href="/news/detail/417">
            <picture>
              <source media="(min-width: 768px)" srcset="/images/price-revision-pc@1x.png 1x, /images/price-revision-pc@2x.png 2x">
              <source media="(min-width: 1px)" srcset="/images/price-revision-sp@1x.png 1x, /images/price-revision-sp@2x.png 2x">
              <img src="/images/price-revision-pc@1x.png" alt="2019年10月1日よりご利用料金が変更になります 詳しくはこちら">
            </picture>
          </a>
        </li>
        <li class="l-banner-dtl">
          <a href="/news/detail/414">
            <picture>
              <source media="(min-width: 768px)" srcset="/images/free-box-pc@1x.png 1x, /images/free-box-pc@2x.png 2x">
              <source media="(min-width: 1px)" srcset="/images/free-box-sp@1x.png 1x, /images/free-box-sp@2x.png 2x">
              <img src="/images/free-box-pc@1x.png" alt="ボックス代金が無料になりました 詳しくはこちら">
            </picture>
          </a>
        </li>
        <li class="l-banner-dtl">
          <a href="/inbound/box/add">
            <picture>
              <source media="(min-width: 768px)" srcset="/images/collected-day-pc@1x.png 1x, /images/collected-day-pc@2x.png 2x">
              <source media="(min-width: 1px)" srcset="/images/collected-day-sp@1x.png 1x, /images/collected-day-sp@2x.png 2x">
              <img src="/images/collected-day-pc@1x.png" alt="13時までのお預け入れ申込で当日にお荷物の受取に伺います">
            </picture>
          </a>
        </li>
      </ul>

      <?php echo $this->element('Order/breadcrumb_list'); ?>

      <div class="head_validation">
        <?php echo $this->Flash->render('customer_amazon_pay_info');?>
      </div>

      <div class="dsn-wrapper dev-wrapper"></div>

      <ul class="items">
        <li id="hako" class="item type_other">
          <p class="rib classic"></p>
          <h3><span>箱を開けないタイプ</span>minikuraHAKO</h3>
          <div class="lineup-pict">
            <picture>
              <img src="/images/order/photo-hako@1x.jpg" srcset="/images/order/photo-hako@1x.jpg 1x, /images/order/photo-hako@2x.jpg 2x" alt="minikuraHAKO">
            </picture>
          </div>
          <div class="lineup-caption">
            <ul class="lineup-price">
              <li>
                <p class="price">月額保管料<span class="price-hs">200</span>円(税抜)/箱</p>
                <p class="price">初期費用<span class="price-lb">0</span>円/箱</p>
                <a class="application">※初期費用の無料期間とは<img src="/images/question.svg"></a>
                <p class="captions">
                  サービス申し込みから翌々月の最終営業日までにボックスのお預け入れが完了すると、サービスのお申し込みの初期費用が無料になります。
                </p>
              </li>
              <li class="option">
              </li>
            </ul>
            <ul class="select-item">
              <li>
                <a class="view-caption"><img src="/images/order/question.svg">レギュラー</a>
              </li>
              <li>
                <div class="spinner">
                  <input type="button" name="spinner_down" class="btn-spinner spinner-down">
                  <?php echo $this->Form->input('PaymentAmazonKitAmazonPay.hako_num', ['type' => 'text', 'default' => '0', 'class' => "input-spinner box_type_hako", 'error' => false, 'label' => false, 'div' => false, 'readonly' => 'readonly']); ?>
                  <input type="button" name="spinner_up" class="btn-spinner spinner-up">
                </div>
              </li>
              <li class="captions">
                <p class="size">W38cm×H38cm×D38cm</p>
                <p class="caption">縦・横・高さが同じ長さで様々なアイテムにオールマイティに対応できるボックスです。</p>
              </li>
            </ul>
            <ul class="select-item">
              <li>
                <a class="view-caption"><img src="/images/order/question.svg">ワイド</a>
              </li>
              <li>
                <div class="spinner">
                  <input type="button" name="spinner_down" class="btn-spinner spinner-down">
                  <?php echo $this->Form->input('PaymentAmazonKitAmazonPay.hako_appa_num', ['type' => 'text', 'default' => '0', 'class' => "input-spinner box_type_hako", 'error' => false, 'label' => false, 'div' => false, 'readonly' => 'readonly']); ?>
                  <input type="button" name="spinner_up" class="btn-spinner spinner-up">
                </div>
              </li>
              <li class="captions">
                <p class="size">W60cm×H20cm×D38cm</p>
                <p class="caption">薄手のジャケット約10着収納できる横長ワイドボックスです。</p>
              </li>
            </ul>
            <ul class="select-item">
              <li>
                <a class="view-caption"><img src="/images/order/question.svg">ブック</a>
              </li>
              <li>
                <div class="spinner">
                  <input type="button" name="spinner_down" class="btn-spinner spinner-down">
                  <?php echo $this->Form->input('PaymentAmazonKitAmazonPay.hako_book_num', ['type' => 'text', 'default' => '0', 'class' => "input-spinner box_type_hako", 'error' => false, 'label' => false, 'div' => false, 'readonly' => 'readonly']); ?>
                  <input type="button" name="spinner_up" class="btn-spinner spinner-up">
                </div>
              </li>
              <li class="captions">
                <p class="size">W42cm×H29cm×D33cm</p>
                <p class="caption">文庫本で約100冊、A4サイズのファイルで約30冊収納できる、底が2重になり耐荷重に優れたボックスです。</p>
              </li>
            </ul>
            <p class="select-num"><span id="hako_total">0</span>個選択済み</p>
            <?php echo $this->Form->error('PaymentAmazonKitAmazonPay.hako_num', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            <?php echo $this->Form->error('PaymentAmazonKitAmazonPay.hako_appa_num', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            <?php echo $this->Form->error('PaymentAmazonKitAmazonPay.hako_book_num', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
          </div>
        </li>
        <li id="mono" class="item type_other">
          <p class="rib best"></p>
          <h3><span>1点ごとのアイテム管理</span>minikuraMONO</h3>
          <div class="lineup-pict">
            <picture>
              <img src="/images/order/photo-mono@1x.jpg" srcset="/images/order/photo-mono@1x.jpg 1x, /images/order/photo-mono@2x.jpg 2x" alt="minikuraMONO">
            </picture>
          </div>
          <div class="lineup-caption">
            <ul class="lineup-price">
              <li>
                <p class="price">月額保管料<span class="price-ms">250</span>円(税抜)</p>
                <p class="price">初期費用<span class="price-lb">0</span>円/箱</p>
                <a class="application">※初期費用の無料期間とは<img src="/images/question.svg"></a>
                <p class="captions">
                  サービス申し込みから翌々月の最終営業日までにボックスのお預け入れが完了すると、サービスのお申し込みの初期費用が無料になります。
                </p>
              </li>
              <?php if (false): ?>
              <!--TODO DASH実装時に外す-->
              <li class="option">
                <label class="dash">
                  <input type="checkbox" class="cb" name="select-dash">
                  <span class="button">DASHを申込む</span>
                </label>
              </li>
              <div class="dash-caption">
                <h4>DASHとは？</h4>
                <p>文庫本で約100冊、A4サイズのファイルで約30冊収納できる、底が2重になり耐荷重に優れたボックスです。</p>
              </div>
              <?php endif; ?>
            </ul>
            <ul class="select-item">
              <li>
                <a class="view-caption"><img src="/images/order/question.svg">レギュラー<span class="select-dash">DASH</span></a>
              </li>
              <li>
                <div class="spinner">
                  <input type="button" name="spinner_down" class="btn-spinner spinner-down">
                  <?php echo $this->Form->input('PaymentAmazonKitAmazonPay.mono_num', ['type' => 'text', 'default' => '0', 'class' => "input-spinner box_type_mono", 'error' => false, 'label' => false, 'div' => false, 'readonly' => 'readonly']); ?>
                  <input type="button" name="spinner_up" class="btn-spinner spinner-up">
                </div>
              </li>
              <li class="captions">
                <p class="size">W38cm×H38cm×D38cm</p>
                <p class="caption">縦・横・高さが同じ長さで様々なアイテムにオールマイティに対応できるボックスです。</p>
              </li>
            </ul>
            <ul class="select-item">
              <li>
                <a class="view-caption"><img src="/images/order/question.svg">ワイド<span class="select-dash">DASH</span></a>
              </li>
              <li>
                <div class="spinner">
                  <input type="button" name="spinner_down" class="btn-spinner spinner-down">
                  <?php echo $this->Form->input('PaymentAmazonKitAmazonPay.mono_appa_num', ['type' => 'text', 'default' => '0', 'class' => "input-spinner box_type_mono", 'error' => false, 'label' => false, 'div' => false, 'readonly' => 'readonly']); ?>
                  <input type="button" name="spinner_up" class="btn-spinner spinner-up">
                </div>
              </li>
              <li class="captions">
                <p class="size">W60cm×H20cm×D38cm</p>
                <p class="caption">薄手のジャケット約10着収納できる横長ワイドボックスです。</p>
              </li>
            </ul>
            <p class="select-num"><span id="mono_total">0</span>個選択済み<?php if (false): ?><!--TODO DASH実装時に外す-->（DASH）<?php endif; ?></p>
            <?php echo $this->Form->error('PaymentAmazonKitAmazonPay.mono_num', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            <?php echo $this->Form->error('PaymentAmazonKitAmazonPay.mono_appa_num', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            <?php echo $this->Form->error('PaymentAmazonKitAmazonPay.mono_book_num', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
          </div>
        </li>
        <li id="library" class="item type_other">
          <p class="rib popular"></p>
          <h3><span>書籍に最適</span>minikuraLibrary</h3>
          <div class="lineup-pict">
            <picture>
              <img src="/images/order/photo-library@1x.jpg" srcset="/images/order/photo-library@1x.jpg 1x, /images/order/photo-library@2x.jpg 2x" alt="minikuraLibrary">
            </picture>
          </div>
          <div class="lineup-caption">
            <ul class="lineup-price">
              <li>
                <p class="price">月額保管料<span class="price-ls">450</span>円(税抜)</p>
                <p class="price">初期費用<span class="price-lb">0</span>円/箱</p>
                <a class="application">※初期費用の無料期間とは<img src="/images/question.svg"></a>
                <p class="captions">
                  サービス申し込みから翌々月の最終営業日までにボックスのお預け入れが完了すると、サービスのお申し込みの初期費用が無料になります。
                </p>
              </li>
              <li class="option">
              </li>
            </ul>
            <ul class="select-item">
              <li>
                <a class="view-caption"><img src="/images/order/question.svg">専用ボックス</a>
              </li>
              <li>
                <div class="spinner">
                  <input type="button" name="spinner_down" class="btn-spinner spinner-down">
                  <?php echo $this->Form->input('PaymentAmazonKitAmazonPay.library_num', ['type' => 'text', 'default' => '0', 'class' => "input-spinner box_type_library", 'error' => false, 'label' => false, 'div' => false, 'readonly' => 'readonly']); ?>
                  <input type="button" name="spinner_up" class="btn-spinner spinner-up">
                </div>
              </li>
              <li class="captions">
                <p class="size">W42cm×H29cm×D33cm</p>
                <p class="caption">文庫本で約100冊、A4サイズのファイルで約30冊収納できる、底が2重になり耐荷重に優れたボックスです。</p>
              </li>
            </ul>
            <p class="select-num"><span id="library_total">0</span>個選択済み</p>
            <?php echo $this->Form->error('PaymentAmazonKitAmazonPay.library_num', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
          </div>
        </li>
        <li id="closet" class="item type_hanger">
          <p class="rib new"></p>
          <h3><span>ハンガー保管</span>minikuraCloset</h3>
          <div class="lineup-pict">
            <picture>
              <img src="/images/order/photo-closet@1x.jpg" srcset="/images/order/photo-closet@1x.jpg 1x, /images/order/photo-closet@2x.jpg 2x" alt="minikuraCloset">
            </picture>
          </div>
          <div class="lineup-caption">
            <ul class="lineup-price">
              <li>
                <p class="price">月額保管料<span class="price-hs">450</span>円(税抜)</p>
                <p class="price">初期費用<span class="price-lb">0</span>円/箱</p>
                <a class="application">※初期費用の無料期間とは<img src="/images/question.svg"></a>
                <p class="captions">
                  サービス申し込みから翌々月の最終営業日までにボックスのお預け入れが完了すると、サービスのお申し込みの初期費用が無料になります。
                </p>
              </li>
              <li class="option">
              </li>
            </ul>
            <ul class="select-item">
              <li>
                <a class="view-caption"><img src="/images/order/question.svg">専用ボックス</a>
              </li>
              <li>
                <div class="spinner">
                  <input type="button" name="spinner_down" class="btn-spinner spinner-down">
                  <?php echo $this->Form->input('PaymentAmazonKitAmazonPay.hanger_num', ['type' => 'text', 'default' => '0', 'class' => "input-spinner box_type_hanger", 'error' => false, 'label' => false, 'div' => false, 'readonly' => 'readonly']); ?>
                  <input type="button" name="spinner_up" class="btn-spinner spinner-up">
                </div>
              </li>
              <li class="captions">
                <p class="size">W40cm×H40cm×D40cm</p>
                <p class="caption">そのまま宅配便で送れる40cm四方の大容量なチャック付き不織布専用バッグです。</p>
              </li>
            </ul>
            <p class="select-num"><span id="hanger_total">0</span>個選択済み</p>
            <?php echo $this->Form->error('PaymentNekoposKitAmazonPay.hanger_num', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
          </div>
        </li>
        <li id="cleaning" class="item type_other">
          <!--p class="rib spring"></p-->
          <h3><span>衣類10点</span>クリーニングパック</h3>
          <div class="lineup-pict">
            <picture>
              <img src="/images/order/photo-cleaning@1x.jpg" srcset="/images/order/photo-cleaning@1x.jpg 1x, /images/order/photo-cleaning@2x.jpg 2x" alt="minikuraクリーニングパック">
            </picture>
          </div>
          <div class="lineup-caption">
            <ul class="lineup-price">
              <li>
                <p class="price">6ヶ月保管＋クリーニング料セット</p>
                <p class="price">サービス申し込み料<span class="price-lb">11,000</span>円(税抜)</p>
                <a class="application">※サービス申し込み料とは<img src="/images/question.svg"></a>
                <p class="captions">
                  専用ボックス、預け入れ送料、半年間の保管料金、10点クリーニング、ボックスごとの取り出し料金が含まれます。
                </p>
              </li>
              <li class="option">
              </li>
            </ul>
            <ul class="select-item">
              <li>
                <a class="view-caption"><img src="/images/order/question.svg">専用ボックス</a>
              </li>
              <li>
                <div class="spinner">
                  <input type="button" name="spinner_down" class="btn-spinner spinner-down">
                  <?php echo $this->Form->input('PaymentAmazonKitAmazonPay.cleaning_num', ['type' => 'text', 'default' => '0', 'class' => "input-spinner box_type_cleaning", 'error' => false, 'label' => false, 'div' => false, 'readonly' => 'readonly']); ?>
                  <input type="button" name="spinner_up" class="btn-spinner spinner-up">
                </div>
              </li>
              <li class="captions">
                <p class="size">W40cm×H40cm×D40cm</p>
                <p class="caption">そのまま宅配便で送れる40cm四方の大容量なチャック付き不織布専用バッグです。</p>
              </li>
            </ul>
            <p class="select-num"><span id="cleaning_total">0</span>個選択済み</p>
            <?php echo $this->Form->error('PaymentAmazonKitAmazonPay.cleaning_num', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
          </div>
        </li>
      </ul>
      <div class="lead-time">
        <div class="wrapper">
          <h4>倉庫に到着したお荷物の預け入れ完了までの目安</h4>
          <ul>
            <li>minikuraHAKO：<?php echo EXPECTED_STORING_COMPLETE_DATE_HAKO; ?>営業日</li>
            <li>minikuraMONO：<?php echo EXPECTED_STORING_COMPLETE_DATE_MONO; ?>営業日</li>
            <?php if (false): ?>
            <!--TODO DASH実装時に外す-->
            <li>minikuraMONO DASH：<?php echo EXPECTED_STORING_COMPLETE_DATE_MONO_DASH; ?>営業日</li>
            <?php endif; ?>
            <li>minikuraLibrary：<?php echo EXPECTED_STORING_COMPLETE_DATE_LIBRARY; ?>営業日</li>
            <li>minikuraCloset：<?php echo EXPECTED_STORING_COMPLETE_DATE_CLOSET; ?>営業日</li>
            <li>minikuraクリーニングパック：<?php echo EXPECTED_STORING_COMPLETE_DATE_CLEANING; ?>営業日</li>
          </ul>
        </div>
      </div>
      <ul class="input-info">
        <li>
          <label class="headline">お客様情報</label>
        </li>
        <!-- AmazonPayment wedget表示処理 -->
        <li id="dsn-amazon-pay">
          <div class="dsn-adress">
            <div id="addressBookWidgetDiv">
            </div>
          </div>
          <div class="dsn-credit">
            <div id="walletWidgetDiv">
            </div>
          </div>
        </li>
        <div class="dsn-divider"></div>

        <li class="select_other">
          <label class="headline">お届けに上がる日時</label>
          <?php echo $this->Form->select('PaymentAmazonKitAmazonPay.datetime_cd', $delivery_datetime_list, ['id' => 'datetime_cd', 'empty' => false, 'label' => false, 'error' => false, 'div' => false]); ?>
          <?php echo $this->Form->error('PaymentAmazonKitAmazonPay.datetime_cd', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
        </li>
      </ul>
    </div>
    <div class="nav-fixed">
      <ul>
        <li>
          <button class="btn-red js-btn-submit" type="button">ボックスの確認</button>
        </li>
      </ul>
    </div>
  <?php echo $this->Form->end(); ?>
