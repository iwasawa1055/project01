<div id="page-wrapper" class="wrapper outbound">
  <h1 class="page-header"><i class="fa icon-cleaning"></i> minikuraCLEANING＋</h1>
  <p class="page-caption"><a class="link" href="https://minikura.com/lineup/cleaning-plus.html" target="_blank">minikuraCLEANING＋ <i class="fa fa-external-link-square"></i></a> に申し込むアイテムを選択してください。</p>
  <ul class="pagenation">
    <li><span class="number">1</span><span class="txt">アイテム<br>選択</span>
    </li>
    <li><span class="number">2</span><span class="txt">確認</span>
    </li>
    <li class="on"><span class="number">3</span><span class="txt">完了</span>
    </li>
  </ul>
  <p class="page-caption">以下の内容でminikuraCLEANING＋の申し込みが完了しました。</p>
  <ul class="l-cfm">
    <li>
      <ul class="l-cfm-item">
        <?php foreach ($cleaning_data['selected_item_list'] as $item): ?>
        <li>
          <p class="l-item-pict"><img src="<?php echo $item['image_first']['image_url'];?>" alt="<?php echo $item['item_id'];?>" class="li-libry-img"></p>
          <p class="txt-item-name"><?php echo $item['item_name'];?><span><?php echo $item['item_id'];?></span></p>
          <p class="txt-item-price"><?php echo number_format($price[$item['item_group_cd']]);?>円</p>
        </li>
        <?php endforeach;?>
        <?php if(!empty($point_data['use_point'])) : ?>
        <li>
          <p class="l-item-pict"></p>
          <p class="txt-item-name">小計</p>
          <p class="txt-item-price"><?php echo number_format($cleaning_data['subtotal']);?>円</p>
        </li>
        <li>
          <p class="l-item-pict"></p>
          <p class="txt-item-name">ポイントご利用</p>
          <p class="txt-item-price">-<?php echo $point_data['use_point'];?>円</p>
        </li>
        <?php endif;?>
        <li>
          <p class="l-item-pict"></p>
          <p class="txt-item-name">総計</p>
          <?php if(!empty($point_data['use_point'])) : ?>
          <p class="txt-item-price"><?php echo number_format($cleaning_data['subtotal'] - $point_data['use_point']);?>円</p>
          <?php else:?>
          <p class="txt-item-price"><?php echo number_format($cleaning_data['subtotal']);?>円</p>
          <?php endif;?>
        </li>
      </ul>
    </li>
    <?php if(!empty($point_data['use_point'])) : ?>
    <li>
      <label class="headline">ご利用になるポイント</label>
      <ul class="li-address">
        <li><?php echo $point_data['use_point'];?>ポイント</li>
      </ul>
    </li>
    <?php endif;?>
  </ul>
</div>
<div class="nav-fixed">
  <ul>
    <li><a class="btn-red" href="/">マイページへ戻る</a>
    </li>
  </ul>
</div>