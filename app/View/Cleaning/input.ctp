<?php $this->Html->css('cleaning/app_dev', ['block' => 'css']); ?>
<?php $this->Html->script('/lib/jquery/js/jquery.infinitescroll.min', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('/lib/cookie/js/docCookies', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('remodal.min', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('cleaning/app', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('cleaning/app_dev', ['block' => 'scriptMinikura']); ?>

<div id="page-wrapper" class="wrapper outbound">
  <h1 class="page-header"><i class="fa icon-cleaning"></i> minikuraCLEANING＋</h1>
  <p class="page-caption"><a class="link" href="https://minikura.com/lineup/cleaning-plus.html" target="_blank">minikuraCLEANING＋ <i class="fa fa-external-link-square"></i></a> に申し込むアイテムを選択してください。</p>
  <ul class="pagenation">
    <li class="on"><span class="number">1</span><span class="txt">アイテム<br>選択</span>
    </li>
    <li><span class="number">2</span><span class="txt">確認</span>
    </li>
    <li><span class="number">3</span><span class="txt">完了</span>
    </li>
  </ul>
  <?php if(!empty($item_list)): ?>
  <?php echo $this->Flash->render('cleaning_post');?>
  <?php echo $this->Flash->render('point_post');?>
  <?php echo $this->Flash->render('point_get');?>
  <?php echo $this->Flash->render('invalid_data');?>
  <?php echo $this->Flash->render('selected_item');?>
  <?php echo $this->Form->create('Cleaning', ['id' => 'itemlist', 'url' => ['controller' => 'cleaning', 'action' => 'input'], 'novalidate' => true]); ?>
  <div class="l-search-group">
    <ul class="l-word-search">
      <li>
        <?php echo $this->Form->input('keyword', ['type' => 'search', 'id' => 'keyword', 'class' => 'search', 'error' => false, 'label' => false, 'div' => false]); ?>
      </li>
      <li>
        <?php echo $this->Form->button('検索',['id' => 'search', 'class' => 'btn-submit', 'type' => 'button']);?>
      </li>
    </ul>
    <ul class="l-sort-item">
      <li class="l-sort-date">
        <?php echo $this->Form->select('order', SORT_ORDER['item_grid'], ['id' => 'order', 'empty' => false, 'label' => false, 'error' => false, 'div' => false]); ?>
      </li>
      <li class="l-sort-az">
        <?php echo $this->Form->select('direction', SORT_DIRECTION, ['id' => 'direction', 'empty' => false, 'label' => false, 'error' => false, 'div' => false]); ?>
      </li>
    </ul>
  </div>
  <div class="item-content">
    <ul class="l-slct-item">
      <li>
        <label class="l-slct-all">
          <input type="checkbox" id="check_all" class="cb-circle">
          <span class="icon"></span>
          <span class="txt-slct-all">すべて選択</span>
        </label>
      </li>
    </ul>
    <?php echo $this->Form->error('Cleaning.item', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
    <ul class="grid l-ipt">
      <?php foreach ($item_list as $item): ?>
      <li class="item_list">
        <label>
          <?php
            echo $this->Form->input(
              "Cleaning.selected_item_id_list.{$item['item_id']}",
              [
                'class'       => 'cb-circle check_item',
                'label'       => false,
                'error'       => false,
                'type'        => 'checkbox',
                'div'         => false,
                'hiddenField' => false,
              ]
            );
          ?>
          <span class="icon"></span>
          <span class="img-item">
              <img src="<?php echo $item['image_first']['image_url'];?>" alt="<?php echo $item['item_id'];?>" class="img-item">
          </span>
          <a href="#" class="link-item-detail" data-remodal-target="<?php echo $item['item_id'];?>"><span class="icon"></span></a>
        </label>
        <div class="l-item-desc">
          <p class="txt-itm-price"><?php echo number_format($price[$item['item_group_cd']]);?>円</p>
          <ul class="l-itm-dtl">
            <li class="col-l">
              <label>アイテムID</label>
              <p><?php echo $item['item_id'];?></p>
            </li>
            <li class="col-r">
            </li>
          </ul>
        </div>
        <div class="remodal modal-items" data-remodal-id="<?php echo $item['item_id'];?>" role="dialog" aria-labelledby="<?php echo $item['item_name'];?>" aria-describedby="<?php echo $item['item_note'];?>" data-remodal-options="hashTracking:false">
          <span class="img-item">
            <img src="<?php echo $item['image_first']['image_url'];?>" alt="<?php echo $item['item_id'];?>">
          </span>
          <div class="l-item-desc">
            <p class="txt-item-id"><?php echo $item['item_id'];?></p>
            <h3 class="txt-itm-name"><?php echo $item['item_name'];?></h3>
            <p class="txt-item-desc"></p>
          </div>
          <a class="btn-d-gray" data-remodal-action="close" aria-label="Close">閉じる</a>
        </div>
      </li>
      <?php endforeach;?>
    </ul>
  </div>
  <ul class="input-info">
    <li>
      <section class="l-input-pnt">
        <label class="headline">ポイントのご利用</label>
        <ul class="l-pnt-detail">
          <?php if (!isset($point_error_message)) : ?>
          <li>
            <p class="txt-pnt">お持ちのポイントをご利用料金に割り当てることが出来ます。<br>
              1ポイント1円として100ポイント以上の残高から10ポイント単位でご利用いただけます。</p>
          </li>
          <li>
            <h3 class="title-pnt-sub">現在<span class="val"><?php echo number_format($point_blance);?></span>ポイント保持しています。</h3>
            <p class="txt-pnt">ご利用状況によっては、お申込みされたポイントをご利用できない場合がございます。<br>取り出しのお知らせやオプションのお知らせにはポイント料金調整前の価格が表示されます。ご了承ください。
            </p>
          </li>
          <li>
            <label class="headline">ご利用になるポイントを入力ください</label>
            <?php echo $this->Form->input('PointUse.use_point', ['id' => 'use_point', 'class' => 'use_point', 'type' => 'text', 'placeholder'=>'例：100', 'error' => false, 'label' => false, 'div' => false]); ?>
            <?php echo $this->Form->error('PointUse.use_point', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
          </li>
          <?php else : ?>
            <li>
              <p class="txt-pnt"><?php echo $point_error_message; ?></p>
            </li>
          <?php endif; ?>
        </ul>
      </section>
    </li>
  </ul>
  <?php echo $this->Form->end(); ?>
  <?php else: ?>
  <div class="item-content">
    <ul class="grid grid-lg">
      <p class="form-control-static col-lg-12">
        ただ今、お預かりしているお品物はございません。<br>
        梱包キットをお持ちでない方は、弊社指定の専用キットのサービスをお申し込みください。<br>
        梱包キットをお持ちの方は、預け入れのお手続きにすすんでください。
      </p>
    </ul>
  </div>
  <?php endif;?>
</div>
<ul class="nav-cleaning">
  <li>選択<span id="all_num" class="val">0</span>点</li>
  </li>
  <li>合計<span id="all_price" class="val">0</span>円</li>
  </li>
</ul>
<div class="nav-fixed">
  <ul>
    <li><button type="button" id="execute" class="btn-red">確認する</button>
    </li>
  </ul>
</div>
