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
<?php if ($is_logined) : ?>
  <form method="post" action="/FirstOrder/confirm_order_authed">
<?php else : ?>
  <form method="post" action="/FirstOrder/confirm_order">
<?php endif; ?>
<section id="lineup">
  <div class="wrapper">
    <?php if ($is_logined) : ?>
      <!-- MONO -->
      <div class="lineup-box">
        <h3>minikuraMONO</h3>
        <p class="price">ボックス代金<span>250円</span>
        </p>
        <p class="price">月額保管料<span>250円</span>
        </p>
        <p class="box-caption">最大30カットの写真撮影でマイページでアイテム管理ができる クラウドストレージ。
        </p>
        <p class="select-number"><span>0,000個選択済み</span>/未選択</p>
        <div class="box-mono"><img src="/first_order/images/box_mono@1x.png" srcset="/first_order/images/box_mono@1x.png 1x, /first_order/images/box_mono@2x.png 2x" alt="minikuraMONO">
        </div>
        <a href="#" class="btn-select" data-remodal-target="modal-mono"><i class="fa fa-chevron-circle-down"></i> 種類と個数を選ぶ</a>
      </div>
      <!-- HAKO -->
      <div class="lineup-box">
        <h3>minikuraHAKO</h3>
        <p class="price">ボックス代金<span>200円</span>
        </p>
        <p class="price">月額保管料<span>200円</span>
        </p>
        <p class="box-caption">箱につめて送るだけで、ボックス単位で管理できるお手軽クラウドストレージ。
        </p>
        <p class="select-number"><span>0,000個選択済み</span>/未選択</p>
        <div class="box-hako"> <img src="/first_order/images/box_hako@1x.png" srcset="/first_order/images/box_hako@1x.png 1x, /first_order/images/box_hako@2x.png 2x" alt="minikuraHAKO"> </div>
        <a href="#" class="btn-select" data-remodal-target="modal-hako"><i class="fa fa-chevron-circle-down"></i> 種類と個数を選ぶ</a>
      </div>
      <!-- CLEANING -->
      <div class="lineup-box">
        <h3>クリーニングパック</h3>
        <p class="price">ボックス代金<span>0円</span>
        </p>
        <p class="price">半年保管料<span>12,000円</span>
        </p>
        <p class="box-caption">10点までの高品質クリーニングと 6ヶ月保管がセットになった 衣類専用クラウドストレージ。
        </p>
        <p class="select-number"><span>0,000個選択済み</span>/未選択</p>
        <div class="box-cleaning"><img src="/first_order/images/box_cleaning@1x.png" srcset="/first_order/images/box_cleaning@1x.png 1x, /first_order/images/box_cleaning@2x.png 2x" alt="minikuraクリーニングパック"> </div>
        <a href="#" class="btn-select" data-remodal-target="modal-cleaning"><i class="fa fa-chevron-circle-down"></i> 個数を選ぶ</a>
      </div>
    <?php else : ?>
      <!-- STARTER -->
      <div class="lineup-box">
        <h3>スターターキット</h3>
        <p class="price">minikuraMONO3箱セット</p>
        <p class="price">ボックス代金<span>250円</span>
        </p>
        <p class="box-caption">最大30カットの写真撮影でマイページでアイテム管理ができるクラウドストレージ。
        </p>
        <p class="select-number"><?php if ($select_starter_kit) : ?><span>1セット選択済み</span><?php else : ?>未選択<?php endif; ?></p>
        <div class="box-starter"> <img src="/first_order/images/box_starter@1x.png" srcset="/first_order/images/box_starter@1x.png 1x, /first_order/images/box_starter@2x.png 2x" alt="minikuraスターターキット"> </div>
        <a class="btn-starter"><i class="fa fa-play-circle-o <?php if ($select_starter_kit) : ?> active <?php endif; ?>"></i> このボックスを選ぶ</a>
        <input id="select_starter_kit" name="select_starter_kit" type="hidden" value="0"/>
        <?php echo $this->Flash->render('select_starter_kit'); ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<section class="nextback">
  <a href="adress.php" class="btn-next-full">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>
  <button class="btn-next-full" type="submit">お届け先を入力<i class="fa fa-chevron-circle-right"></i></button>

</section>
</form>

<!--MONO modal-->
<div class="remodal items" data-remodal-id="modal-mono" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
  <div class="box">
    <div class="pict-box"><img src="/first_order/images/box_regular@1x.png" srcset="/first_order/images/box_regular@1x.png 1x, /first_order/images/box_regular@2x.png 2x" alt="">
    </div>
    <div class="select-box">
      <h3>レギュラーボックス</h3>
      <select class="item-number">
        <option value="">0箱</option>
        <option value="">1箱</option>
        <option value="">2箱</option>
        <option value="">3箱</option>
        <option value="">4箱</option>
        <option value="">5箱</option>
        <option value="">6箱</option>
        <option value="">7箱</option>
        <option value="">8箱</option>
        <option value="">9箱</option>
      </select>
    </div>
    <p class="size">W38cm×H38cm×D38cm</p>
    <p class="caption">縦・横・高さが同じ長さで様々なアイテムにオールマイティに対応できるボックスです。</p>
  </div>
  <div class="box">
    <div class="pict-box"><img src="/first_order/images/box_apparel@1x.png" srcset="/first_order/images/box_apparel@1x.png 1x, /first_order/images/box_apparel@2x.png 2x" alt="">
    </div>
    <div class="select-box">
      <h3>アパレルボックス</h3>
      <select class="item-number">
        <option value="">0箱</option>
        <option value="">1箱</option>
        <option value="">2箱</option>
        <option value="">3箱</option>
        <option value="">4箱</option>
        <option value="">5箱</option>
        <option value="">6箱</option>
        <option value="">7箱</option>
        <option value="">8箱</option>
        <option value="">9箱</option>
      </select>
    </div>
    <p class="size">W60cm×H20cm×D38cm</p>
    <p class="caption">男性用スーツ・ジャケットをハンガーに掛けた状態で2つ折りで約xxx着収納できるボックスです。</p>
  </div>
  <div class="box">
    <div class="pict-box"><img src="/first_order/images/box_book@1x.png" srcset="/first_order/images/box_book@1x.png 1x, /first_order/images/box_book@2x.png 2x" alt="">
    </div>
    <div class="select-box">
      <h3>ブックボックス</h3>
      <select class="item-number">
        <option value="">0箱</option>
        <option value="">1箱</option>
        <option value="">2箱</option>
        <option value="">3箱</option>
        <option value="">4箱</option>
        <option value="">5箱</option>
        <option value="">6箱</option>
        <option value="">7箱</option>
        <option value="">8箱</option>
        <option value="">9箱</option>
      </select>
    </div>
    <p class="size">W42cm×H29cm×D33cm</p>
    <p class="caption">文庫本で約xxx冊、A4サイズの書類で約xxx枚収納できる、底が2重になり耐荷重に優れたボックスです。</p>
  </div>
  <a class="btn-return" data-remodal-action="close" class="" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
  <a class="btn-submit" href="adress.php">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>
</div>
<!--HAKO modal-->
<div class="remodal items" data-remodal-id="modal-hako" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
  <div class="box">
    <div class="pict-box"><img src="/first_order/images/box_regular@1x.png" srcset="/first_order/images/box_regular@1x.png 1x, /first_order/images/box_regular@2x.png 2x" alt="">
    </div>
    <div class="select-box">
      <h3>レギュラーボックス</h3>
      <select class="item-number">
        <option value="">0箱</option>
        <option value="">1箱</option>
        <option value="">2箱</option>
        <option value="">3箱</option>
        <option value="">4箱</option>
        <option value="">5箱</option>
        <option value="">6箱</option>
        <option value="">7箱</option>
        <option value="">8箱</option>
        <option value="">9箱</option>
      </select>
    </div>
    <p class="size">W38cm×H38cm×D38cm</p>
    <p class="caption">縦・横・高さが同じ長さで様々なアイテムにオールマイティに対応できるボックスです。</p>
  </div>
  <div class="box">
    <div class="pict-box"><img src="/first_order/images/box_apparel@1x.png" srcset="/first_order/images/box_apparel@1x.png 1x, /first_order/images/box_apparel@2x.png 2x" alt="">
    </div>
    <div class="select-box">
      <h3>アパレルボックス</h3>
      <select class="item-number">
        <option value="">0箱</option>
        <option value="">1箱</option>
        <option value="">2箱</option>
        <option value="">3箱</option>
        <option value="">4箱</option>
        <option value="">5箱</option>
        <option value="">6箱</option>
        <option value="">7箱</option>
        <option value="">8箱</option>
        <option value="">9箱</option>
      </select>
    </div>
    <p class="size">W60cm×H20cm×D38cm</p>
    <p class="caption">男性用スーツ・ジャケットをハンガーに掛けた状態で2つ折りで約xxx着収納できるボックスです。</p>
  </div>
  <div class="box">
    <div class="pict-box"><img src="/first_order/images/box_book@1x.png" srcset="/first_order/images/box_book@1x.png 1x, /first_order/images/box_book@2x.png 2x" alt="">
    </div>
    <div class="select-box">
      <h3>ブックボックス</h3>
      <select class="item-number">
        <option value="">0箱</option>
        <option value="">1箱</option>
        <option value="">2箱</option>
        <option value="">3箱</option>
        <option value="">4箱</option>
        <option value="">5箱</option>
        <option value="">6箱</option>
        <option value="">7箱</option>
        <option value="">8箱</option>
        <option value="">9箱</option>
      </select>
    </div>
    <p class="size">W42cm×H29cm×D33cm</p>
    <p class="caption">文庫本で約xxx冊、A4サイズの書類で約xxx枚収納できる、底が2重になり耐荷重に優れたボックスです。</p>
  </div>
  <a class="btn-return" data-remodal-action="close" class="" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
  <a class="btn-submit" href="adress.php">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>
</div>
<!--Cleaning modal-->
<div class="remodal items" data-remodal-id="modal-cleaning" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
  <div class="box">
    <div class="pict-box"><img src="/first_order/images/box_cleaning@1x.png" srcset="/first_order/images/box_cleaning@1x.png 1x, /first_order/images/box_cleaning@2x.png 2x" alt="クリーニングパック">
    </div>
    <div class="select-box">
      <h3>クリーニングパック</h3>
      <select class="item-number">
        <option value="">0箱</option>
        <option value="">1箱</option>
        <option value="">2箱</option>
        <option value="">3箱</option>
        <option value="">4箱</option>
        <option value="">5箱</option>
        <option value="">6箱</option>
        <option value="">7箱</option>
        <option value="">8箱</option>
        <option value="">9箱</option>
      </select>
    </div>
    <p class="size">W40cm×H40cm×D40cm</p>
    <p class="caption">そのまま宅配便で送れる40cm四方の大容量なチャック付き不織布専用バッグです。</p>
  </div>
  <a class="btn-return" data-remodal-action="close" class="" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
  <a class="btn-submit" href="adress.php">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>
  <button class="btn-submit" type="submit" disabled>お届け先を入力</button>>
</div>