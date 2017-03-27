<?php echo $this->element('FirstOrder/first_sneaker'); ?>
<title>ボックス選択 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav_sneaker'); ?>
<?php echo $this->element('FirstOrder/breadcrumb_list'); ?>

<!-- LINEUP -->
<?php $kit_select_type = CakeSession::read('kit_select_type'); ?>

<form method="post" action="/first_order/confirm_order" novalidate>
<section id="dsn-lineup" class="fix">
  <div class="dsn-wrapper">
    <?php if ($kit_select_type === 'sneaker') : ?>
      <!-- sneaker -->
      <div class="dsn-lineup-box">
        <h3>minikura sneakers</h3>
        <p class="dsn-price">月額保管料<span>800円</span>
        </p>
        <p class="dsn-price">ボックス代金<span>800円</span>
        </p>
        <p class="dsn-box-caption">「NIKE MY SNKRS」専用の スニーカー保管サービス。あなたのスニーカーを大切に保管します。
        </p>
        <p class="dsn-select-number" id="select_sneaker"><?php if (CakeSession::read('Order.sneaker.sneaker') > 0) : ?><span><?php echo h(CakeSession::read('Order.sneaker.sneaker')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
        <div class="dsn-box-sneaker"><img src="/first_order_file/images/box_sneaker@1x.png" srcset="/first_order_file/images/box_sneaker@1x.png 1x, /first_order_file/images/box_sneaker@2x.png 2x" alt="minikuraクリーニングパック"> </div>
        <a href="#" class="dsn-btn-select" data-remodal-target="modal-sneaker"><i class="fa fa-chevron-circle-down"></i> 個数を選ぶ</a>
        <div class="form">
          <?php echo $this->Flash->render('select_oreder_sneaker'); ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>
<section class="nextback fix">
  <button class="btn-next-full" type="submit" formnovalidate>お届け先を入力 <i class="fa fa-chevron-circle-right"></i></button>
</section>
<input type="hidden" name="sneaker"      value="<?php echo h(CakeSession::read('Order.sneaker.sneaker')); ?>" />
</form>
<!--sneaker modal-->
<div class="remodal dsn-items" data-remodal-id="modal-sneaker" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
  <div class="dsn-box">
    <div class="dsn-pict-box"><img src="/first_order_file/images/box_sneaker@1x.png" srcset="/first_order_file/images/box_sneaker@1x.png 1x, /first_order_file/images/box_sneaker@2x.png 2x" alt="クリーニングパック">
    </div>
    <div class="dsn-select-box">
      <h3>minikura sneakers</h3>
      <select class="dsn-item-number js-item-number js-item-sneaker" data-name="sneaker" data-box_type="sneaker">
        <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
        <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.sneaker.sneaker') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
        <?php endfor;?>
      </select>
    </div>
    <p class="dsn-size">W62cm×H44cm×D44cm</p>
    <p class="dsn-caption">二重構造の頑丈な段ボール「ダブルカートンボックス」を採用。<br>
      1ボックスにつき8足までお預かりできます。</p>
  </div>
  <a class="dsn-btn-return" data-remodal-action="close" class="" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
  <a class="dsn-btn-submit js-btn-submit">お届け先を入力 <i class="fa fa-chevron-circle-right"></i></a>
</div>

<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js_sneaker'); ?>
<script src="/first_order_file/js/first_order/add_order.js"></script>
<?php echo $this->element('FirstOrder/last'); ?>
