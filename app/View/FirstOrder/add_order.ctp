<?php echo $this->element('FirstOrder/first'); ?>
<title>ボックス選択 - minikura</title>
<link href="/first_order_file/css/dsn-register.css" rel="stylesheet">
<link href="/css/dsn-amazon-pay.css" rel="stylesheet">
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>
<?php echo $this->element('FirstOrder/breadcrumb_list'); ?>

<!-- LINEUP -->
<?php $kit_select_type = CakeSession::read('kit_select_type'); ?>

<form method="post" action="/first_order/confirm_order" novalidate>
<section id="dsn-lineup">
  <div class="dsn-wrapper">
    <?php if ($kit_select_type === 'starter_kit') : ?>
    <!-- STARTER -->
    <div class="lineup-box">
      <h3>MONOスターターキット</h3>
      <div class="box-starter"> <img src="/first_order_file/images/box_starter@1x.png" srcset="/first_order_file/images/box_starter@1x.png 1x, /first_order_file/images/box_starter@2x.png 2x" alt="minikuraスターターキット"> </div>
      <div class="caption">
        <p class="price">月額保管料<span>250円</span>（1箱につき）</p>
        <p class="price">スターターキット(3箱)<span class="starter">250円</span></p>
        <p class="box-caption">初回限定！MONOボックス3種類がセットになったお得なキット。</p>
        <p class="select-number js-select-starter"><span>1セット選択済み</span></p>
        <input id="select_starter_kit" name="starter" type="hidden" value="1"/>
      </div>
      <div class="form">
        <?php echo $this->Flash->render('select_starter_kit'); ?>
      </div>
    </div>
    <?php endif; ?>
	<?php // HAKO 5 パック 有効期限 ?>
	<?php if ('2017053119' > date('YmdH')): ?>
    <!-- RECOMMEND -->
    <?php if (($kit_select_type === 'all') || ($kit_select_type === 'hako_limited_ver1')) : ?>
    <div id="dsn-recommend" class="dsn-lineup-box">
      <div class="dsn-medal">
        <picture>
          <source srcset="/first_order_file/images/h5-caption@1x.png 1x, /first_order_file/images/h5-caption@2x.png 2x">
          <img src="/first_order_file/images/h5-caption@1x.png" alt="限定">
        </picture>
      </div>
      <h3 class="dev-medal-margin">HAKOお片付けパック</h3>
      <div class="dsn-box-recommend"> <img src="/first_order_file/images/box_hako5@1x.png" srcset="/first_order_file/images/box_hako5@1x.png 1x, /first_order_file/images/box_hako5@2x.png 2x" alt="minikuraHAKO">
      </div>
      <div class="dsn-caption">
        <p class="dsn-price">月額保管料<span>200円/1箱</span></p>
        <p class="dsn-price">ボックス代金<span>500円/5箱</span></p>
        <p class="dsn-box-caption">HAKOレギュラー５箱がセットになった大変お得な限定パック！</p>
        <p class="dsn-select-number" id="select_hako_limited_ver1"><?php if (CakeSession::read('Order.hako_limited_ver1.hako_limited_ver1') > 0) : ?><span><?php echo h(CakeSession::read('Order.hako_limited_ver1.hako_limited_ver1')) ?>パック選択済み</span><?php else : ?>未選択<?php endif; ?></p>
      </div>
      <a href="#" class="dsn-btn-select" data-remodal-target="modal-recommend"><i class="fa fa-chevron-circle-down"></i> 種類と個数を選ぶ</a>
      <div class="form">
        <?php echo $this->Flash->render('select_oreder_hako_limited_ver1'); ?>
      </div>
    </div>
    <?php endif; ?>
	<?php // endif HAKO 5 パック有効期限 ?>
	<?php endif; ?>
    <?php if (($kit_select_type === 'all') || ($kit_select_type === 'hako') || ($kit_select_type === 'code')) : ?>
    <!-- HAKO -->
    <div id="dsn-hako" class="dsn-lineup-box">
      <h3><span>箱を開けないタイプ</span>minikuraHAKO</h3>
      <div class="dsn-box-hako"> <img src="/first_order_file/images/box_hako@1x.png" srcset="/first_order_file/images/box_hako@1x.png 1x, /first_order_file/images/box_hako@2x.png 2x" alt="minikuraHAKO"> </div>
      <div class="dsn-caption">
        <p class="dsn-price">月額保管料<span>200円</span></p>
        <p class="dsn-price">ボックス代金<span>200円</span></p>
        <?php //<p class="dsn-box-caption">保管するならHAKOがオススメ！</p> ?>
        <p class="dsn-select-number" id="select_hako"><?php if (CakeSession::read('OrderTotal.hako_num') > 0) : ?><span><?php echo h(CakeSession::read('OrderTotal.hako_num')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
      </div>
      <a href="#" class="dsn-btn-select" data-remodal-target="modal-hako"><i class="fa fa-chevron-circle-down"></i> 種類と個数を選ぶ</a>
      <div class="form">
        <?php echo $this->Flash->render('select_oreder_hako'); ?>
      </div>
    </div>
    <?php endif; ?>
    <?php if (($kit_select_type === 'all') || ($kit_select_type === 'mono') || ($kit_select_type === 'code')) : ?>
    <!-- MONO -->
    <div id="dsn-mono" class="dsn-lineup-box">
        <h3><span>1 点ごとのアイテム管理</span>minikuraMONO</h3>
        <div class="dsn-box-mono"><img src="/first_order_file/images/box_mono@1x.png" srcset="/first_order_file/images/box_mono@1x.png 1x, /first_order_file/images/box_mono@2x.png 2x" alt="minikuraMONO"></div>
        <div class="dsn-caption">
          <p class="dsn-price">月額保管料<span>250円</span></p>
          <p class="dsn-price">ボックス代金<span>250円</span></p>
          <p class="dsn-select-number" id="select_mono"><?php if (CakeSession::read('OrderTotal.mono_num') > 0) : ?><span><?php echo h(CakeSession::read('OrderTotal.mono_num')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
        </div>
        <a href="#" class="btn-select" data-remodal-target="modal-mono"><i class="fa fa-chevron-circle-down"></i> 種類と個数を選ぶ</a>
        <div class="form">
          <?php echo $this->Flash->render('select_oreder_mono'); ?>
        </div>
      </div>
    <?php endif; ?>
    <?php if (($kit_select_type === 'all') || ($kit_select_type === 'cleaning') || ($kit_select_type === 'code')) : ?>
      <!-- CLEANING -->
      <div id="dsn-cleaning" class="dsn-lineup-box">
        <h3><span>衣類 10 点</span>クリーニングパック</h3>
        <div class="dsn-box-cleaning"><img src="/first_order_file/images/box_cleaning@1x.png" srcset="/first_order_file/images/box_cleaning@1x.png 1x, /first_order_file/images/box_cleaning@2x.png 2x" alt="minikuraクリーニングパック"> </div>
        <div class="dsn-caption">
          <p class="dsn-price">6ヶ月保管＋クリーニング料セット</p>
          <p class="dsn-price">ボックス代金<span>12,000円</span></p>
          <p class="dsn-select-number" id="select_cleaning"><?php if (CakeSession::read('Order.cleaning.cleaning') > 0) : ?><span><?php echo h(CakeSession::read('Order.cleaning.cleaning')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
        </div>
        <a href="#" class="dsn-btn-select" data-remodal-target="modal-cleaning"><i class="fa fa-chevron-circle-down"></i> 個数を選ぶ</a>
        <div class="form">
          <?php echo $this->Flash->render('select_oreder_cleaning'); ?>
        </div>
      </div>
    <?php endif; ?>
    <?php if (($kit_select_type === 'all') || ($kit_select_type === 'code')) : ?>
    <!-- minikuraダイレクト -->
    <div id="dsn-mybox" class="dsn-lineup-box">
      <div class="dsn-medal">
        <picture>
          <source srcset="/first_order_file/images/md-caption@1x.png 1x, /first_order_file/images/md-caption@2x.png 2x">
          <img src="/first_order_file/images/md-caption@1x.png" alt="すぐに送れる！">
        </picture>
      </div>
      <h3><span>片付けたらそのまま</span>minikuraダイレクト</h3>
      <div class="dsn-box-cleaning"><img src="/first_order_file/images/box_mybox@1x.png" srcset="/first_order_file/images/box_mybox@1x.png 1x, /first_order_file/images/box_mybox@2x.png 2x" alt="minikuraダイレクト"> </div>
      <div class="dsn-caption">
        <p class="dsn-price">月額保管料<span>250円</p>
        <p class="dsn-price">ボックス代金<span>0円</span>
        </p>
      </div>
      <a href="/first_order_direct_inbound/add_address" class="dsn-btn-mybox"><i class="fa fa-chevron-circle-right"></i> 預け入れに進む</a>
    </div>
    <?php endif; ?>
  </div>
</section>
<section class="select-login">
  <div class="login-minikura">
    <p>新しくminikuraアカウントを取得して<br>ボックスを購入します。</p>
    <button class="btn-next-full" type="submit" formnovalidate>お届け先を入力 <i class="fa fa-chevron-circle-right"></i></button>
  </div>
  <div class="login-amazon">
    <p>お持ちのAmazonアカウントで<br>支払うことができます。</p>
    <div id="AmazonPayButton">
    </div>
    <a id="Logout" >Logout</a>
  </div>
</section>
<input type="hidden" name="mono"          value="<?php echo h(CakeSession::read('Order.mono.mono')); ?>" />
<input type="hidden" name="mono_apparel"  value="<?php echo h(CakeSession::read('Order.mono.mono_apparel')); ?>" />
<input type="hidden" name="mono_book"     value="<?php echo h(CakeSession::read('Order.mono.mono_book')); ?>" />
<input type="hidden" name="hako"          value="<?php echo h(CakeSession::read('Order.hako.hako')); ?>" />
<input type="hidden" name="hako_apparel"  value="<?php echo h(CakeSession::read('Order.hako.hako_apparel')); ?>" />
<input type="hidden" name="hako_book"     value="<?php echo h(CakeSession::read('Order.hako.hako_book')); ?>" />
<input type="hidden" name="cleaning"      value="<?php echo h(CakeSession::read('Order.cleaning.cleaning')); ?>" />
<input type="hidden" name="hako_limited_ver1"      value="<?php echo h(CakeSession::read('Order.hako_limited_ver1.hako_limited_ver1')); ?>" />
</form>
<!--MONO modal-->
<div class="remodal items" data-remodal-id="modal-mono" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
  <div class="box">
    <div class="pict-box"><img src="/first_order_file/images/box_regular@1x.png" srcset="/first_order_file/images/box_regular@1x.png 1x, /first_order_file/images/box_regular@2x.png 2x" alt="">
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
    <div class="pict-box"><img src="/first_order_file/images/box_apparel@1x.png" srcset="/first_order_file/images/box_apparel@1x.png 1x, /first_order_file/images/box_apparel@2x.png 2x" alt="">
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
    <div class="pict-box"><img src="/first_order_file/images/box_book@1x.png" srcset="/first_order_file/images/box_book@1x.png 1x, /first_order_file/images/box_book@2x.png 2x" alt="">
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
  <a class="btn-submit js-btn-submit">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>
</div>
<!--HAKO modal-->
<div class="remodal items" data-remodal-id="modal-hako" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
  <div class="box">
    <div class="pict-box"><img src="/first_order_file/images/box_regular@1x.png" srcset="/first_order_file/images/box_regular@1x.png 1x, /first_order_file/images/box_regular@2x.png 2x" alt="">
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
    <div class="pict-box"><img src="/first_order_file/images/box_apparel@1x.png" srcset="/first_order_file/images/box_apparel@1x.png 1x, /first_order_file/images/box_apparel@2x.png 2x" alt="">
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
    <div class="pict-box"><img src="/first_order_file/images/box_book@1x.png" srcset="/first_order_file/images/box_book@1x.png 1x, /first_order_file/images/box_book@2x.png 2x" alt="">
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
  <a class="btn-submit js-btn-submit">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>
</div>
<!--Cleaning modal-->
<div class="remodal items" data-remodal-id="modal-cleaning" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
  <div class="box">
    <div class="pict-box"><img src="/first_order_file/images/box_cleaning@1x.png" srcset="/first_order_file/images/box_cleaning@1x.png 1x, /first_order_file/images/box_cleaning@2x.png 2x" alt="クリーニングパック">
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
  <a class="btn-submit js-btn-submit">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>
</div>
<!--Recommend modal-->
<div class="remodal items" data-remodal-id="modal-recommend" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
  <div class="box">
    <div class="pict-box"><img src="/first_order_file/images/box_regular5@1x.png" srcset="/first_order_file/images/box_regular5@1x.png 1x, /first_order_file/images/box_regular5@2x.png 2x" alt="HAKOお片付けパック">
    </div>
    <div class="select-box">
      <h3>HAKOお片付けパック</h3>
      <select class="item-number js-item-number js-item-hako_limited_ver1" data-name="hako_limited_ver1" data-box_type="hako_limited_ver1">
        <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
        <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.hako_limited_ver1.hako_limited_ver1') == $i ? ' selected' : '' ;?>><?php echo h($i);?>パック</option>
        <?php endfor;?>
      </select>
    </div>
    <p class="size">W38cm×H38cm×D38cm</p>
    <p class="caption">引っ越し、片付けに最適！お得な５枚パック！</p>
  </div>

  <a class="btn-return" data-remodal-action="close" class="" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
  <a class="btn-submit js-btn-submit">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>

</div>

<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<script src="/first_order_file/js/first_order/add_order.js"></script>
<script type="text/javascript">
  function showButton() {
    var authRequest;
    var host = location.protocol + '//' + location.hostname;
    OffAmazonPayments.Button("AmazonPayButton", AppAmazonPaymentLogin.SELLER_ID, {
      type: "PwA",
      color: "Gold",
      size: "medium",
      authorization: function () {
        loginOptions = {scope: "profile payments:widget", popup: "true"};
        authRequest = amazon.Login.authorize(loginOptions, host + "/first_order/input_amazon_profile");
      }
    });
  }
</script>

<script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>" ></script>
<?php echo $this->element('FirstOrder/last'); ?>
