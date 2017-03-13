<?php echo $this->element('FirstOrder/first'); ?>
<title>ボックス選択 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>

<!-- LINEUP -->
<?php $kit_select_type = CakeSession::read('kit_select_type'); ?>

<form method="post" action="/first_order/confirm_order" novalidate>
<section id="lineup" class="fix">
  <div class="wrapper">
    <?php if ($kit_select_type === 'sneaker') : ?>
      <!-- CLEANING -->
      <div class="lineup-box">
        <h3>クリーニングパック</h3>
        <p class="price">6ヶ月保管＋クリーニング料セット
        </p>
        <p class="price">ボックス代金<span>12,000円</span>
        </p>
        <p class="box-caption">大切な衣類をしっかり保管したい方に！クリーニング付き衣類専用保管パック。
        </p>
        <p class="select-number" id="select_sneaker"><?php if (CakeSession::read('Order.sneaker.sneaker') > 0) : ?><span><?php echo h(CakeSession::read('Order.cleaning.cleaning')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
        <div class="box-sneaker"><img src="/first_order_file/images/box_sneaker@1x.png" srcset="/first_order_file/images/box_sneaker@1x.png 1x, /first_order_file/images/box_sneaker@2x.png 2x" alt="minikuraクリーニングパック"> </div>
        <a href="#" class="btn-select" data-remodal-target="modal-sneaker"><i class="fa fa-chevron-circle-down"></i> 個数を選ぶ</a>
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
<div class="remodal items" data-remodal-id="modal-sneaker" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
  <div class="box">
    <div class="pict-box"><img src="/first_order_file/images/box_sneaker@1x.png" srcset="/first_order_file/images/box_sneaker@1x.png 1x, /first_order_file/images/box_sneaker@2x.png 2x" alt="クリーニングパック">
    </div>
    <div class="select-box">
      <h3>クリーニングパック</h3>
      <select class="item-number js-item-number js-item-sneaker" data-name="sneaker" data-box_type="sneaker">
        <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
        <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.sneaker.sneaker') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
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
