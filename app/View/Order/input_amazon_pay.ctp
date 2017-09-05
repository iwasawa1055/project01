<?php $this->Html->script('/js/order/input_amazon_pay', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('https://maps.google.com/maps/api/js?libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/address', ['block' => 'scriptMinikura']); ?>

<?php $this->Html->css('/css/order/dsn-purchase.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/order/order_dev.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/dsn-amazon-pay.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/order/input_amazon_pay_dev.css', ['block' => 'css']); ?>
<?php $this->validationErrors['OrderKit'] = $validErrors; ?>

    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>
      </div>
    </div>
    <div class="row">
      <form method="post" class="select-add-address-form" action="/order/confirm_amazon_pay" novalidate>
        <div class="col-lg-12">
          <div class="panel panel-default">
            <section id="dsn-pagenation">
                <ul>
                    <li class="dsn-on">
                        <i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
                    </li>
                    <li>
                        <i class="fa fa-check"></i><span>確認</span>
                    </li>
                    <li>
                        <i class="fa fa-truck"></i><span>完了</span>
                    </li>
                </ul>
            </section>

            <!-- LINEUP -->
            <section id="dsn-lineup">
              <div class="dsn-wrapper dev-wrapper">
                <!-- HAKO -->
                <div id="dsn-hako" class="dsn-lineup-box">
                  <h3><span>箱を開けないタイプ</span>minikuraHAKO</h3>
                  <div class="dsn-box-hako dev-box-hako"> <img src="/images/order/box_hako@1x.png" srcset="/images/order/box_hako@1x.png 1x, /images/order/box_hako@2x.png 2x" alt="minikuraHAKO"> </div>
                  <div class="dsn-caption">
                    <p class="dsn-price">月額保管料<span>200円</span>
                    </p>
                    <p class="dsn-price">ボックス代金<span>200円</span>
                    </p>
                    <p class="dsn-select-number" id="select_hako"><?php if (CakeSession::read('OrderTotal.hako_num') > 0) : ?><span><?php echo h(CakeSession::read('OrderTotal.hako_num')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
                  </div>
                  <a href="#" class="dsn-btn-select" data-remodal-target="modal-hako"><i class="fa fa-chevron-circle-down"></i> 種類と個数を選ぶ</a>
                  <div class="dsn-form">
                    <?php echo $this->Flash->render('select_oreder_hako'); ?>
                  </div>
                </div>
                <!-- MONO -->
                <div id="dsn-mono" class="dsn-lineup-box">
                  <h3><span>1 点ごとのアイテム管理</span>minikuraMONO</h3>
                  <div class="dsn-box-mono dev-box-mono"><img src="/images/order/box_mono@1x.png" srcset="/images/order/box_mono@1x.png 1x, /images/order/box_mono@2x.png 2x" alt="minikuraMONO">
                  </div>
                  <div class="dsn-caption">
                    <p class="dsn-price">月額保管料<span>250円</span>
                    </p>
                    <p class="dsn-price">ボックス代金<span>250円</span>
                    </p>
                    <p class="dsn-select-number" id="select_mono"><?php if (CakeSession::read('OrderTotal.mono_num') > 0) : ?><span><?php echo h(CakeSession::read('OrderTotal.mono_num')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
                  </div>
                  <a href="#" class="dsn-btn-select" data-remodal-target="modal-mono"><i class="fa fa-chevron-circle-down"></i> 種類と個数を選ぶ</a>
                  <div class="dsn-form">
                    <?php echo $this->Flash->render('select_oreder_mono'); ?>
                  </div>
                </div>
                <!-- CLEANING -->
                <div id="dsn-cleaning" class="dsn-lineup-box">
                  <h3><span>衣類 10 点</span>クリーニングパック</h3>
                  <div class="dsn-box-cleaning dev-box-cleaning"><img src="/images/order/box_cleaning@1x.png" srcset="/images/order/box_cleaning@1x.png 1x, /images/order/box_cleaning@2x.png 2x" alt="minikuraクリーニングパック"> </div>
                  <div class="dsn-caption">
                    <p class="dsn-price">6ヶ月保管＋クリーニング料セット</p>
                    <p class="dsn-price">ボックス代金<span>12,000円</span>
                    </p>
                    <p class="dsn-select-number" id="select_cleaning"><?php if (CakeSession::read('Order.cleaning.cleaning') > 0) : ?><span><?php echo h(CakeSession::read('Order.cleaning.cleaning')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
                  </div>
                  <a href="#" class="dsn-btn-select" data-remodal-target="modal-cleaning"><i class="fa fa-chevron-circle-down"></i> 個数を選ぶ</a>
                  <div class="dsn-form">
                    <?php echo $this->Flash->render('select_oreder_cleaning'); ?>
                  </div>
                </div>
                <!-- minikuraダイレクト -->
                <div id="dsn-mybox" class="dsn-lineup-box">
                  <div class="dsn-medal">
                    <picture>
                      <source srcset="/images/order/md-caption@1x.png 1x, /images/order/md-caption@2x.png 2x">
                      <img src="/images/order/md-caption@1x.png" alt="すぐに送れる！">
                    </picture>
                  </div>
                  <h3><span>片付けたらそのまま</span>minikura<br>ダイレクト</h3>
                  <div class="dsn-box-cleaning dev-box-cleaning"><img src="/images/order/box_mybox@1x.png" srcset="/images/order/box_mybox@1x.png 1x, /images/order/box_mybox@2x.png 2x" alt="minikuraダイレクト"> </div>
                  <div class="dsn-caption">
                    <p class="dsn-price">月額保管料<span>250円</span></p>
                    <p class="dsn-price">ボックス代金<span>0円</span></p>
                  </div>
                  <a href="/direct_inbound/input" class="dsn-btn-mybox"><i class="fa fa-chevron-circle-right"></i> 預け入れに進む</a>
                </div>
              </div>
            </section>
            <section id="dsn-delivery">
              <div class="dsn-wrapper">
                <table class="accounting">
                  <tr>
                    <th>合計点数</th>
                    <td id="js-item-total">0点</td>
                  </tr>
                </table>
                
                    <!-- AmazonPayment wedget表示処理 -->
                <div id="dsn-amazon-pay" class="form-group col-lg-12">
                  <div class="dsn-address">
                    <div id="addressBookWidgetDiv">
                    </div>
                  </div>
                  <div class="dsn-credit">
                    <div id="walletWidgetDiv">
                    </div>
                  </div>
                </div>
                <div class="dsn-form">
                  <div class="dsn-form">
                    <?php // アマゾンから取得した情報をバリデーション ?>
                    <?php echo $this->Flash->render('postal');?>
                    <?php echo $this->Flash->render('pref');?>
                    <?php echo $this->Flash->render('address1');?>
                    <?php echo $this->Flash->render('address2');?>
                    <?php echo $this->Flash->render('tel1');?>
                  </div>
                </div>

                <div class="dsn-divider"></div>
                <div class="dsn-form">
                  <label>お届け希望日時</label>
                  <select name="datetime_cd" id="datetime_cd" class="dsn-delivery focused">
                    <?php foreach ( CakeSession::read('Address.select_delivery_list') as $key => $value ) {?>
                      <option value="<?php echo $value->datetime_cd;?>"<?php if ( $value->datetime_cd === CakeSession::read('Address.datetime_cd') ) echo " selected";?>><?php echo $value->text;?></option>
                    <?php } ?>
                  </select>
                  <div class="dsn-form">
                    <?php echo $this->Flash->render('datetime_cd');?>
                  </div>
                  <input type="hidden" name="select_delivery" id="select_delivery" value="<?php if (!empty(CakeSession::read('Address.select_delivery'))) : ?><?php echo h(CakeSession::read('Address.select_delivery'))?><?php else : ?><?php endif; ?>">
                </div>
              </div>
            </section>
          </div>
        </div>
        <section class="dsn-nextback">
          <button type="submit" class="dsn-btn-next-full js-btn-submit">確認へ <i class="fa fa-chevron-circle-right"></i></button>
        </section>      

        <input type="hidden" name="mono"          value="<?php echo h(CakeSession::read('Order.mono.mono')); ?>" />
        <input type="hidden" name="mono_apparel"  value="<?php echo h(CakeSession::read('Order.mono.mono_apparel')); ?>" />
        <input type="hidden" name="mono_book"     value="<?php echo h(CakeSession::read('Order.mono.mono_book')); ?>" />
        <input type="hidden" name="hako"          value="<?php echo h(CakeSession::read('Order.hako.hako')); ?>" />
        <input type="hidden" name="hako_apparel"  value="<?php echo h(CakeSession::read('Order.hako.hako_apparel')); ?>" />
        <input type="hidden" name="hako_book"     value="<?php echo h(CakeSession::read('Order.hako.hako_book')); ?>" />
        <input type="hidden" name="cleaning"      value="<?php echo h(CakeSession::read('Order.cleaning.cleaning')); ?>" />
      </form>
      <!--MONO modal-->
      <div class="remodal dsn-items" data-remodal-id="modal-mono" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
        <div class="dsn-box">
          <div class="dsn-pict-box">
            <img src="/images/order/box_regular@1x.png" srcset="/images/order/box_regular@1x.png 1x, /images/order/box_regular@2x.png 2x" alt="">
          </div>
          <div class="dsn-select-box">
            <h3>レギュラーボックス</h3>
            <select class="dsn-item-number js-item-number js-item-mono" data-name="mono" data-box_type="mono">
              <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++) : ?>
              <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.mono.mono') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
              <?php endfor;?>
            </select>
          </div>
          <p class="dsn-size">W38cm×H38cm×D38cm</p>
          <p class="dsn-caption">縦・横・高さが同じ長さで様々なアイテムにオールマイティに対応できるボックスです。</p>
        </div>
        <div class="dsn-box">
          <div class="dsn-pict-box">
            <img src="/images/order/box_apparel@1x.png" srcset="/images/order/box_apparel@1x.png 1x, /images/order/box_apparel@2x.png 2x" alt="">
          </div>
          <div class="dsn-select-box">
            <h3>アパレルボックス</h3>
            <select class="dsn-item-number js-item-number js-item-mono" data-name="mono_apparel" data-box_type="mono">
              <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
              <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.mono.mono_apparel') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
              <?php endfor;?>
            </select>
          </div>
          <p class="dsn-size">W60cm×H20cm×D38cm</p>
          <p class="dsn-caption">薄手のジャケット約10着収納できるアパレル専用ボックスです。</p>
        </div>
        <div class="dsn-box">
          <div class="dsn-pict-box">
            <img src="/images/order/box_book@1x.png" srcset="/images/order/box_book@1x.png 1x, /images/order/box_book@2x.png 2x" alt="">
          </div>
          <div class="dsn-select-box">
            <h3>ブックボックス</h3>
            <select class="dsn-item-number js-item-number js-item-mono" data-name="mono_book" data-box_type="mono">
              <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
              <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.mono.mono_book') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
              <?php endfor;?>
            </select>
          </div>
          <p class="dsn-size">W42cm×H29cm×D33cm</p>
          <p class="dsn-caption">文庫本で約100冊、A4サイズの書類で約30枚収納できる、底が2重になり耐荷重に優れたボックスです。</p>
        </div>
        <a class="dsn-btn-return" data-remodal-action="close" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
      </div>
      <!--HAKO modal-->
      <div class="remodal dsn-items" data-remodal-id="modal-hako" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
        <div class="dsn-box">
          <div class="dsn-pict-box"><img src="/images/order/box_regular@1x.png" srcset="/images/order/box_regular@1x.png 1x, /images/order/box_regular@2x.png 2x" alt="">
          </div>
          <div class="dsn-select-box">
            <h3>レギュラーボックス</h3>
            <select class="dsn-item-number js-item-number js-item-hako" data-name="hako" data-box_type="hako">
              <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
              <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.hako.hako') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
              <?php endfor;?>
            </select>
          </div>
          <p class="dsn-size">W38cm×H38cm×D38cm</p>
          <p class="dsn-caption">縦・横・高さが同じ長さで様々なアイテムにオールマイティに対応できるボックスです。</p>
        </div>
        <div class="dsn-box">
          <div class="dsn-pict-box"><img src="/images/order/box_apparel@1x.png" srcset="/images/order/box_apparel@1x.png 1x, /images/order/box_apparel@2x.png 2x" alt="">
          </div>
          <div class="dsn-select-box">
            <h3>アパレルボックス</h3>
            <select class="dsn-item-number js-item-number js-item-hako" data-name="hako_apparel" data-box_type="hako">
              <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
              <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.hako.hako_apparel') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
              <?php endfor;?>
            </select>
            </div>
          <p class="dsn-size">W60cm×H20cm×D38cm</p>
          <p class="dsn-caption">薄手のジャケット約10着収納できるアパレル専用ボックスです。</p>
        </div>
        <div class="dsn-box">
          <div class="dsn-pict-box"><img src="/images/order/box_book@1x.png" srcset="/images/order/box_book@1x.png 1x, /images/order/box_book@2x.png 2x" alt="">
          </div>
          <div class="dsn-select-box">
            <h3>ブックボックス</h3>
            <select class="dsn-item-number js-item-number js-item-hako" data-name="hako_book" data-box_type="hako">
              <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
              <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.hako.hako_book') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
              <?php endfor;?>
            </select>
          </div>
          <p class="dsn-size">W42cm×H29cm×D33cm</p>
          <p class="dsn-caption">文庫本で約100冊、A4サイズの書類で約30枚収納できる、底が2重になり耐荷重に優れたボックスです。</p>
        </div>
        <a class="dsn-btn-return" data-remodal-action="close" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
      </div>
      <!--Cleaning modal-->
      <div class="remodal dsn-items" data-remodal-id="modal-cleaning" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
        <div class="dsn-box">
          <div class="dsn-pict-box"><img src="/images/order/box_cleaning@1x.png" srcset="/images/order/box_cleaning@1x.png 1x, /images/order/box_cleaning@2x.png 2x" alt="minikura sneakers">
          </div>
          <div class="dsn-select-box">
            <h3>minikura sneakers</h3>
            <select class="dsn-item-number js-item-number js-item-cleaning" data-name="cleaning" data-box_type="cleaning">
              <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
              <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.cleaning.cleaning') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
              <?php endfor;?>
            </select>
          </div>
          <p class="dsn-size">W40cm×H40cm×D40cm</p>
          <p class="dsn-caption">そのまま宅配便で送れる40cm四方の大容量なチャック付き不織布専用バッグです。</p>
        </div>
        <a class="dsn-btn-return" data-remodal-action="close" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
      </div>
    </div>

