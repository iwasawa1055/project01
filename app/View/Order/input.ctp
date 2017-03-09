<?php $this->Html->script('order/input', ['block' => 'scriptMinikura']); ?>
<!-- 暫定的にFirstOrderのcssを読み込み -->
<?php $this->Html->css('/first_order_file/css/app.css', ['block' => 'css']); ?>
<?php $this->Html->css('/first_order_file/css/app_dev.css', ['block' => 'css']); ?>
<?php $this->validationErrors['OrderKit'] = $validErrors; ?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>
      </div>
    </div>
    <div class="row">
      <form method="post" class="select-add-address-form" action="/order/confirm" novalidate>
        <section id="lineup" class="fix">
          <div class="wrapper">
            <!-- HAKO -->
            <div class="lineup-box">
              <h3>minikuraHAKO</h3>
              <p class="price">月額保管料<span>200円</span>
              </p>
              <p class="price">ボックス代金<span>200円</span>
              </p>
              <p class="box-caption">収納・片付けするならHAKOがオススメ！ボックスに詰めて送るだけでお手軽管理。
              </p>
              <p class="select-number" id="select_hako"><?php if (CakeSession::read('OrderTotal.hako_num') > 0) : ?><span><?php echo h(CakeSession::read('OrderTotal.hako_num')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
              <div class="box-hako"> <img src="/images/order/box_hako@1x.png" srcset="/images/order/box_hako@1x.png 1x, /images/order/box_hako@2x.png 2x" alt="minikuraHAKO"> </div>
              <a href="#" class="btn-select" data-remodal-target="modal-hako"><i class="fa fa-chevron-circle-down"></i> 種類と個数を選ぶ</a>
              <div class="form">
                <?php echo $this->Flash->render('select_oreder_hako'); ?>
              </div>
            </div>
            <!-- MONO -->
            <div class="lineup-box">
              <h3>minikuraMONO</h3>
              <p class="price">月額保管料<span>250円</span>
              </p>
              <p class="price">ボックス代金<span>250円</span>
              </p>
              <p class="box-caption">便利な1点ごとのアイテム管理！アイテム毎の取り出しやオプション機能充実。
              </p>
              <p class="select-number" id="select_mono"><?php if (CakeSession::read('OrderTotal.mono_num') > 0) : ?><span><?php echo h(CakeSession::read('OrderTotal.mono_num')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
              <div class="box-mono"><img src="/images/order/box_mono@1x.png" srcset="/images/order/box_mono@1x.png 1x, /images/order/box_mono@2x.png 2x" alt="minikuraMONO">
              </div>
              <a href="#" class="btn-select" data-remodal-target="modal-mono"><i class="fa fa-chevron-circle-down"></i> 種類と個数を選ぶ</a>
              <div class="form">
                <?php echo $this->Flash->render('select_oreder_mono'); ?>
              </div>
            </div>
            <!-- CLEANING -->
            <div class="lineup-box">
              <h3>クリーニングパック</h3>
              <p class="price">6ヶ月保管＋クリーニング料セット
              </p>
              <p class="price">ボックス代金<span>12,000円</span>
              </p>
              <p class="box-caption">大切な衣類をしっかり保管したい方に！クリーニング付き衣類専用保管パック。
              </p>
              <p class="select-number" id="select_cleaning"><?php if (CakeSession::read('Order.cleaning.cleaning') > 0) : ?><span><?php echo h(CakeSession::read('Order.cleaning.cleaning')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
              <div class="box-cleaning"><img src="/images/order/box_cleaning@1x.png" srcset="/images/order/box_cleaning@1x.png 1x, /images/order/box_cleaning@2x.png 2x" alt="minikuraクリーニングパック"> </div>
              <a href="#" class="btn-select" data-remodal-target="modal-cleaning"><i class="fa fa-chevron-circle-down"></i> 個数を選ぶ</a>
              <div class="form">
                <?php echo $this->Flash->render('select_oreder_cleaning'); ?>
              </div>
            </div>
          </div>
        </section>
        <section id="adress">
          <div class="wrapper">
            <?php if (CakeSession::read('isCredit')) { ?>
              <div class="form">
                <label>クレジットカード番号<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
                <input type="tel" class="name focused" name="card_no" disabled="disabled" value="<?php echo CakeSession::read('OrderKit.card_data.card_no') . ' ' . CakeSession::read('OrderKit.card_data.holder_name');?>">
                <?php echo $this->Flash->render('card_no');?>
              </div>

              <div class="form">
                <label>セキュリティコード<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
                <input type="tel" class="postal focused" name="security_cd" placeholder="0123" size="6" maxlength="6" value="">
                <?php echo $this->Flash->render('security_cd');?>
              </div>
            <?php } ?>
            <?php if (empty(CakeSession::read('OrderKit.card_data'))) { ?>
            <!-- カード登録 -->
            <div class="form">
              <label>クレジットカード番号<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
              <input type="tel" class="name focused" name="card_no" placeholder="0000-0000-0000-0000" size="20" maxlength="20" value="<?php echo CakeSession::read('Credit.card_no');?>">
              <?php echo $this->Flash->render('card_no');?>
            </div>

            <div class="form">
              <label>セキュリティコード<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
              <input type="tel" class="postal focused" name="security_cd" placeholder="0123" size="6" maxlength="6" value="">
              <?php echo $this->Flash->render('security_cd');?>
            </div>

            <?php } ?>
            <div class="form">
              <label>お届け先</label>
              <select name="address_id" id="address_id" class="select-delivery focused select-add-address">
                <option value="">以下からお選びください</option>
                <?php foreach ( CakeSession::read('OrderKit.address_list') as $key => $value ) {?>
                <option value="<?php echo $key;?>"<?php if ( $key === (int)CakeSession::read('OrderKit.address_id') ) echo " selected";?>><?php echo $value;?></option>
                <?php } ?>
              </select>
              <?php echo $this->Flash->render('address_id');?>
              <?php echo $this->Flash->render('format_address');?>
            </div>
            <div class="form">
              <label>お届け希望日<span class="required ">※</span></label>
              <select name="datetime_cd" id="datetime_cd" class="select-delivery focused">
                <?php foreach ( CakeSession::read('OrderKit.select_delivery_list') as $key => $value ) {?>
                <option value="<?php echo $value->datetime_cd;?>"<?php if ( $value->datetime_cd === CakeSession::read('OrderKit.datetime_cd') ) echo " selected";?>><?php echo $value->text;?></option>
                <?php } ?>
              </select>
              <?php echo $this->Flash->render('datetime_cd');?>
              <input type="hidden" name="select_delivery" id="select_delivery" value="<?php if (!empty(CakeSession::read('OrderKit.select_delivery'))) : ?><?php echo h(CakeSession::read('OrderKit.select_delivery'))?><?php else : ?><?php endif; ?>">
            </div>
          </div>
        </section>

        <section class="nextback fix">
          <button class="btn-next-full" type="submit" formnovalidate>確認へ <i class="fa fa-chevron-circle-right"></i></button>
        </section>
        <input type="hidden" name="mono"          value="<?php echo h(CakeSession::read('Order.mono.mono')); ?>" />
        <input type="hidden" name="mono_apparel"  value="<?php echo h(CakeSession::read('Order.mono.mono_apparel')); ?>" />
        <input type="hidden" name="mono_book"     value="<?php echo h(CakeSession::read('Order.mono.mono_book')); ?>" />
        <input type="hidden" name="hako"          value="<?php echo h(CakeSession::read('Order.hako.hako')); ?>" />
        <input type="hidden" name="hako_apparel"  value="<?php echo h(CakeSession::read('Order.hako.hako_apparel')); ?>" />
        <input type="hidden" name="hako_book"     value="<?php echo h(CakeSession::read('Order.hako.hako_book')); ?>" />
        <input type="hidden" name="cleaning"      value="<?php echo h(CakeSession::read('Order.cleaning.cleaning')); ?>" />
      </form>
    </div>

    <!--MONO modal-->
    <div class="remodal items" data-remodal-id="modal-mono" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
      <div class="box">
        <div class="pict-box"><img src="/images/order/box_regular@1x.png" srcset="/images/order/box_regular@1x.png 1x, /images/order/box_regular@2x.png 2x" alt="">
        </div>
        <div class="select-box">
          <h3>レギュラーボックス</h3>
          <select class="item-number js-item-number js-item-mono" data-name="mono" data-box_type="mono">
            <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++) : ?>
            <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.mono.mono') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
            <?php endfor;?>
          </select>
        </div>
        <p class="size">W38cm×H38cm×D38cm</p>
        <p class="caption">縦・横・高さが同じ長さで様々なアイテムにオールマイティに対応できるボックスです。</p>
      </div>
      <div class="box">
        <div class="pict-box"><img src="/images/order/box_apparel@1x.png" srcset="/images/order/box_apparel@1x.png 1x, /images/order/box_apparel@2x.png 2x" alt="">
        </div>
        <div class="select-box">
          <h3>アパレルボックス</h3>
          <select class="item-number js-item-number js-item-mono" data-name="mono_apparel" data-box_type="mono">
            <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
            <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.mono.mono_apparel') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
            <?php endfor;?>
          </select>
        </div>
        <p class="size">W60cm×H20cm×D38cm</p>
        <p class="caption">薄手のジャケット約10着収納できるアパレル専用ボックスです。</p>
      </div>
      <div class="box">
        <div class="pict-box"><img src="/images/order/box_book@1x.png" srcset="/images/order/box_book@1x.png 1x, /images/order/box_book@2x.png 2x" alt="">
        </div>
        <div class="select-box">
          <h3>ブックボックス</h3>
          <select class="item-number js-item-number js-item-mono" data-name="mono_book" data-box_type="mono">
            <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
            <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.mono.mono_book') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
            <?php endfor;?>
          </select>
        </div>
        <p class="size">W42cm×H29cm×D33cm</p>
        <p class="caption">文庫本で約100冊、A4サイズの書類で約30枚収納できる、底が2重になり耐荷重に優れたボックスです。</p>
      </div>
      <a class="btn-return" data-remodal-action="close" class="" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
      <a class="btn-submit">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>
    </div>
    <!--HAKO modal-->
    <div class="remodal items" data-remodal-id="modal-hako" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
      <div class="box">
        <div class="pict-box"><img src="/images/order/box_regular@1x.png" srcset="/images/order/box_regular@1x.png 1x, /images/order/box_regular@2x.png 2x" alt="">
        </div>
        <div class="select-box">
          <h3>レギュラーボックス</h3>
          <select class="item-number js-item-number js-item-hako" data-name="hako" data-box_type="hako">
            <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
            <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.hako.hako') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
            <?php endfor;?>
          </select>
        </div>
        <p class="size">W38cm×H38cm×D38cm</p>
        <p class="caption">縦・横・高さが同じ長さで様々なアイテムにオールマイティに対応できるボックスです。</p>
      </div>
      <div class="box">
        <div class="pict-box"><img src="/images/order/box_apparel@1x.png" srcset="/images/order/box_apparel@1x.png 1x, /images/order/box_apparel@2x.png 2x" alt="">
        </div>
        <div class="select-box">
          <h3>アパレルボックス</h3>
          <select class="item-number js-item-number js-item-hako" data-name="hako_apparel" data-box_type="hako">
            <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
            <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.hako.hako_apparel') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
            <?php endfor;?>
          </select>
        </div>
        <p class="size">W60cm×H20cm×D38cm</p>
        <p class="caption">薄手のジャケット約10着収納できるアパレル専用ボックスです。</p>
      </div>
      <div class="box">
        <div class="pict-box"><img src="/images/order/box_book@1x.png" srcset="/images/order/box_book@1x.png 1x, /images/order/box_book@2x.png 2x" alt="">
        </div>
        <div class="select-box">
          <h3>ブックボックス</h3>
          <select class="item-number js-item-number js-item-hako" data-name="hako_book" data-box_type="hako">
            <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
            <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.hako.hako_book') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
            <?php endfor;?>
          </select>
        </div>
        <p class="size">W42cm×H29cm×D33cm</p>
        <p class="caption">文庫本で約100冊、A4サイズの書類で約30枚収納できる、底が2重になり耐荷重に優れたボックスです。</p>
      </div>
      <a class="btn-return" data-remodal-action="close" class="" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
      <a class="btn-submit">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>
    </div>
    <!--Cleaning modal-->
    <div class="remodal items" data-remodal-id="modal-cleaning" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
      <div class="box">
        <div class="pict-box"><img src="/images/order/box_cleaning@1x.png" srcset="/images/order/box_cleaning@1x.png 1x, /images/order/box_cleaning@2x.png 2x" alt="クリーニングパック">
        </div>
        <div class="select-box">
          <h3>クリーニングパック</h3>
          <select class="item-number js-item-number js-item-cleaning" data-name="cleaning" data-box_type="cleaning">
            <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
            <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.cleaning.cleaning') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
            <?php endfor;?>
          </select>
        </div>
        <p class="size">W40cm×H40cm×D40cm</p>
        <p class="caption">そのまま宅配便で送れる40cm四方の大容量なチャック付き不織布専用バッグです。</p>
      </div>
      <a class="btn-return" data-remodal-action="close" class="" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
      <a class="btn-submit">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>
    </div>
