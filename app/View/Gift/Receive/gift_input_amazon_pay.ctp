<?php $this->Html->script('gift/receive/input_amazon_pay.js?'.time(), ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('https://maps.google.com/maps/api/js?key=' . Configure::read('app.googlemap.api.key') . '&libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/address', ['block' => 'scriptMinikura']); ?>

<?php $this->Html->css('/css/order/dsn-purchase.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/dsn-amazon-pay.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/order/input_amazon_pay_dev.css', ['block' => 'css']); ?>

  <?php echo $this->Form->create('ReceiveGift', ['url' => ['controller' => 'receive', 'action' => 'input_amazon_pay'], 'novalidate' => true]); ?>

    <div id="page-wrapper" class="l-detail-gift wrapper">
      <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ギフトをもらう</h1>
      <ul class="pagenation">
        <li class="on"><span class="number">1</span><span class="txt">コード<br>入力</span>
        </li>
        <li><span class="number">2</span><span class="txt">確認</span>
        </li>
        <li><span class="number">3</span><span class="txt">完了</span>
        </li>
      </ul>

      <div class="head_validation">
        <?php echo $this->Flash->render('customer_amazon_pay_info');?>
      </div>

      <div class="l-method">
        <h3 class="title-method">ギフトの受け取り〜ご利用方法</h3>
        <ul class="l-method-step">
          <li>
            <picture>
              <source media="(min-width: 750px)" srcset="/images/method-step1-pc@1x.png 1x, /images/method-step1-pc@2x.png 2x">
              <source media="(min-width: 300px)" srcset="/images/method-step1-sp@1x.png 1x, /images/method-step1-sp@2x.png 2x">
              <img src="/images/method-step1-pc@1x.png" alt="ギフトコードを入力してボックスを郵送します">
            </picture>
            <p>1.ギフトコードを入力して<br class="pc">
              ボックスを郵送します</p>
          </li>
          <li>
            <picture>
              <source media="(min-width: 750px)" srcset="/images/method-step2-pc@1x.png 1x, /images/method-step2-pc@2x.png 2x">
              <source media="(min-width: 300px)" srcset="/images/method-step2-sp@1x.png 1x, /images/method-step2-sp@2x.png 2x">
              <img src="/images/method-step2-pc@1x.png" alt="届いたボックスに預けたいものを入れます">
            </picture>
            <p>2.届いたボックスに<br class="pc">
              預けたいものを入れます</p>
          </li>
          <li>
            <picture>
              <source media="(min-width: 750px)" srcset="/images/method-step3-pc@1x.png 1x, /images/method-step3-pc@2x.png 2x">
              <source media="(min-width: 300px)" srcset="/images/method-step3-sp@1x.png 1x, /images/method-step3-sp@2x.png 2x">
              <img src="/images/method-step3-pc@1x.png" alt="マイページの「預け入れ」から集荷を申し込みます">
            </picture>
            <p>3.マイページの「預け入れ」から<br class="pc">
              集荷を申し込みます</p>
          </li>
          <li>
            <picture>
              <source media="(min-width: 750px)" srcset="/images/method-step4-pc@1x.png 1x, /images/method-step4-pc@2x.png 2x">
              <source media="(min-width: 300px)" srcset="/images/method-step4-sp@1x.png 1x, /images/method-step4-sp@2x.png 2x">
              <img src="/images/method-step4-pc@1x.png" alt="マイページにて、画像が反映されます">
            </picture>
            <p>4.マイページにて、<br class="pc">
              画像が反映されます<br>
              ※サービスにより、<br class="pc">
              表示されないものもあります</p>
          </li>
        </ul>
      </div>
      <ul class="input-info">
        <li class="input_gift_cd_area">
          <h4>ご利用になるギフトコードを入力してください。</h4>
          <ul class="l-input-cord">
            <li>
              <?php echo $this->Form->input('ReceiveGift.gift_cd', ['id' => 'gift_cd', 'class' => "cb-square", 'placeholder'=>'例：0123', 'size' => '6', 'autocomplete' => "cc-csc", 'error' => false, 'label' => false, 'div' => false]); ?>
            </li>
            <li><a id="check_gift_cd" class="btn-red" href="javascript:void(0)">確認する</a>
          </ul>
          <?php echo $this->Form->error('ReceiveGift.gift_cd', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
        </li>
        <li class="gift-info">
          <ul class="items">
            <li id="kit_75" class="item" style="display:none">
              <div class="l-image-lineup">
                <picture>
                  <img src="/images/order/photo-cleaning@1x.jpg" srcset="/images/order/photo-cleaning@1x.jpg 1x, /images/order/photo-cleaning@2x.jpg 2x" alt="クリーニングパック">
                </picture>
              </div>
              <div class="l-title-lineup">
                <h3 class="title-item">衣類保管5点まで無料<span>クリーニングパック</span></h3>
                <p class="text-description">6ヶ月保管+クリーニング料セット</p>
                <p class="text-number">個数<span></span></p>
              </div>
              <div class="l-action-lineup">
                <ul class="l-feature">
                  <li>
                    <img src="/images/icon-cleaning1.svg">
                    <p>1点ずつ<br>
                      写真管理</p>
                  </li>
                  <li>
                    <img src="/images/icon-cleaning2.svg">
                    <p>クリーニング<br>
                      対応</p>
                  </li>
                  <li>
                    <img src="/images/icon-cleaning3.svg">
                    <p>ハンガー保管<br>
                      可能</p>
                  </li>
                </ul>
                <p class="text-size">W40cm×H40cm×D40cm</p>
                <p class="text-caption">そのまま宅配便で送れる40cm四方の大容量なチャック付き不織布専用バッグです。</p>
                <p class="text-note">ギフトでは5着までが無料、それ以降の衣類には別途保管料がつきます。</p>
              </div>
            </li>
          </ul>
        </li>
        <li>
          <h4>お客様情報</h4>
        </li>
        <!-- AmazonPayment wedget表示処理 -->
        <li id="dsn-amazon-pay">
          <div class="dsn-adress">
            <div id="addressBookWidgetDiv">
            </div>
          </div>
        </li>
        <div class="dsn-divider"></div>
        <li class="caution-box">
          <p class="title">お届け日時は選べません</p>
          <div class="content">
            <label class="input-check">
              <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">ネコポスでの配送となりお客さまのポストに直接投函・配達します。<br>
                                注文内容にお間違いないか再度ご確認の上、「確認」にお進みください。</span>
            </label>
          </div>
        </li>
      </ul>
    </div>
    <div class="nav-fixed">
      <ul>
        <li>
          <button id="execute" class="btn-red js-btn-submit" type="button">確認</button>
        </li>
      </ul>
    </div>
  <?php echo $this->Form->end(); ?>
