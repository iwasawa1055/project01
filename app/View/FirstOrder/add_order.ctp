<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>ボックス選択 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>

<section id="pagenation">
  <ul>
    <li class="on"><i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
    </li>
    <li><i class="fa fa-pencil-square-o"></i><span>お届け先<br>登録</span>
    </li>
    <li><i class="fa fa-credit-card"></i><span>カード<br>登録</span>
    </li>
    <li><i class="fa fa-envelope"></i><span>メール<br>登録</span>
    </li>
    <li><i class="fa fa-check"></i><span>確認</span>
    </li>
    <li><i class="fa fa-truck"></i><span>完了</span>
    </li>
  </ul>
</section>

<!-- LINEUP -->
<?php $kit_select_type = CakeSession::read('kit_select_type'); ?>

<form method="post" action="/first_order/confirm_order">
<section id="lineup">
  <div class="wrapper">
    <?php if (($kit_select_type === 'all') || ($kit_select_type === 'mono')) : ?>
      <!-- MONO -->
      <div class="lineup-box">
        <h3>minikuraMONO</h3>
        <p class="price">月額保管料<span>250円</span>
        </p>
        <p class="price">ボックス代金<span>250円</span>
        </p>
        <p class="box-caption">最大30カットの写真撮影でマイページでアイテム管理ができるクラウドストレージ。
        </p>
        <p class="select-number" id="select_mono"><?php if (CakeSession::read('OrderTotal.mono_num') > 0) : ?><span><?php echo h(CakeSession::read('OrderTotal.mono_num')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
        <div class="box-mono"><img src="/first_order_file/images/box_mono@1x.png" srcset="/first_order_file/images/box_mono@1x.png 1x, /first_order_file/images/box_mono@2x.png 2x" alt="minikuraMONO">
        </div>
        <a href="#" class="btn-select" data-remodal-target="modal-mono"><i class="fa fa-chevron-circle-down"></i> 種類と個数を選ぶ</a>
        <div class="form">
          <?php echo $this->Flash->render('select_oreder_mono'); ?>
        </div>
      </div>
    <?php endif; ?>
    <?php if (($kit_select_type === 'all') || ($kit_select_type === 'hako')) : ?>
      <!-- HAKO -->
      <div class="lineup-box">
        <h3>minikuraHAKO</h3>
        <p class="price">月額保管料<span>200円</span>
        </p>
        <p class="price">ボックス代金<span>200円</span>
        </p>
        <p class="box-caption">箱につめて送るだけで、ボックス単位で管理できるお手軽クラウドストレージ。
        </p>
        <p class="select-number" id="select_hako"><?php if (CakeSession::read('OrderTotal.hako_num') > 0) : ?><span><?php echo h(CakeSession::read('OrderTotal.hako_num')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
        <div class="box-hako"> <img src="/first_order_file/images/box_hako@1x.png" srcset="/first_order_file/images/box_hako@1x.png 1x, /first_order_file/images/box_hako@2x.png 2x" alt="minikuraHAKO"> </div>
        <a href="#" class="btn-select" data-remodal-target="modal-hako"><i class="fa fa-chevron-circle-down"></i> 種類と個数を選ぶ</a>
        <div class="form">
          <?php echo $this->Flash->render('select_oreder_hako'); ?>
        </div>
      </div>
    <?php endif; ?>
    <?php if (($kit_select_type === 'all') || ($kit_select_type === 'cleaning')) : ?>
      <!-- CLEANING -->
      <div class="lineup-box">
        <h3>クリーニングパック</h3>
        <p class="price">6ヶ月保管＋クリーニング料セット
        </p>
        <p class="price">ボックス代金<span>12,000円</span>
        </p>
        <p class="box-caption">10点までの高品質クリーニングと 6ヶ月保管がセットになった衣類専用クラウドストレージ。
        </p>
        <p class="select-number" id="select_cleaning"><?php if (CakeSession::read('Order.cleaning.cleaning') > 0) : ?><span><?php echo h(CakeSession::read('Order.cleaning.cleaning')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
        <div class="box-cleaning"><img src="/first_order_file/images/box_cleaning@1x.png" srcset="/first_order_file/images/box_cleaning@1x.png 1x, /first_order_file/images/box_cleaning@2x.png 2x" alt="minikuraクリーニングパック"> </div>
        <a href="#" class="btn-select" data-remodal-target="modal-cleaning"><i class="fa fa-chevron-circle-down"></i> 個数を選ぶ</a>
        <div class="form">
          <?php echo $this->Flash->render('select_oreder_cleaning'); ?>
        </div>
      </div>
    <?php endif; ?>
    <?php if ($kit_select_type === 'starter_kit') : ?>
      <!-- STARTER -->
      <div class="lineup-box">
        <h3>MONOスターターキット</h3>
        <p class="price">月額保管料<span>250円</span>（1箱につき）
        <p class="price">ボックス代金<span class="starter"><s>750円</s><span>今だけ250円</span></span>
        </p>
        <p class="box-caption">minikuraMONOのボックス3種類がセットになった初回登録ユーザー限定のお得なキット。
        </p>
        <p class="select-number js-select-starter"><?php if (CakeSession::read('Order.starter.starter')) : ?><span>1セット選択済み</span><?php else : ?>未選択<?php endif; ?></p>
        <div class="box-starter"> <img src="/first_order_file/images/box_starter@1x.png" srcset="/first_order_file/images/box_starter@1x.png 1x, /first_order_file/images/box_starter@2x.png 2x" alt="minikuraスターターキット"> </div>
        <a class="btn-starter <?php if (CakeSession::read('Order.starter.starter')) : ?> active <?php endif; ?>"><i class="fa fa-play-circle-o "></i> このボックスを選ぶ</a>
        <input id="select_starter_kit" name="starter" type="hidden" value="<?php echo CakeSession::read('Order.starter.starter'); ?>"/>
        <div class="form">
          <?php echo $this->Flash->render('select_starter_kit'); ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
<section class="nextback">
  <button class="btn-next-full" type="submit">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></button>
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
    <p class="caption">男性用スーツ・ジャケットをハンガーに掛けた状態で2つ折りで約xxx着収納できるボックスです。</p>
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
    <p class="caption">文庫本で約xxx冊、A4サイズの書類で約xxx枚収納できる、底が2重になり耐荷重に優れたボックスです。</p>
  </div>
  <a class="btn-return" data-remodal-action="close" class="" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
  <a class="btn-submit">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>
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
    <p class="caption">男性用スーツ・ジャケットをハンガーに掛けた状態で2つ折りで約xxx着収納できるボックスです。</p>
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
    <p class="caption">文庫本で約xxx冊、A4サイズの書類で約xxx枚収納できる、底が2重になり耐荷重に優れたボックスです。</p>
  </div>
  <a class="btn-return" data-remodal-action="close" class="" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
  <a class="btn-submit">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>
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
  <a class="btn-submit">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>
</div>

<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<script src="/first_order_file/js/first_order/add_order.js"></script>
<?php echo $this->element('FirstOrder/last'); ?>
