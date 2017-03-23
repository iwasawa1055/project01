<?php $this->Html->css('cleaning/app', ['block' => 'css']); ?>
<?php $this->Html->css('cleaning/app_dev', ['block' => 'css']); ?>
<?php $this->Html->script('cleaning/app', ['block' => 'scriptMinikura']); ?>

  <h1 class="page-header"><i class="fa icon-cleaning"></i> minikuraCLEANING＋</h1>
  <h2 class="page-caption">この内容でminikuraCLEANING＋を申し込みます。</h2>
  <p class="alert alert-danger cleaning-attention">
    ※ ご注文いただくアイテムによっては、個別見積りとなり料金が変わる可能性がございます。<br />
    またアイテムの状態により、クリーニングをお断りさせていただく場合もございます。<br />
    上記の場合は別途メールにてご連絡させていただきます。
  </p>
  <div id="cleaning-wrapper">
    <div class="nav-cleaning">
      <ul>
        <li><i class="fa fa-calculator"></i><span><?php echo $selected_count;?></span>点<span><?php echo number_format($selected_total);?></span>円</li>
        <li><a href="input" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a></li>
        <li><a href="complete" class="btn-next">申し込む <i class="fa fa-chevron-circle-right"></i></a>
      </ul>
    </div>
    <div class="grid">
      <ul>
        <!--loop-->
        <?php foreach ($itemList as $items): ?>
        <?php foreach ($items as $item): ?>
        <li>
          <div class="item-select">
            <img src="<?php echo $item['image_url'];?>" alt="<?php echo $item['item_id'];?>">
          </div>
          <div class="item-caption">
            <p class="item-id"><?php echo $item['item_id'];?></p>
            <p class="item-price"><?php echo number_format($item['price']);?>円</p>
          </div>
        </li>
        <?php endforeach; ?>
        <?php endforeach; ?>
        <!--loop end-->
      </ul>
    </div>
  </div>
  <div id="sp-cleaning-wrapper">
    <div class="sp-nav-cleaning">
      <ul>
        <li class="price"><i class="fa fa-calculator"></i><span><?php echo $selected_count;?></span>点<span><?php echo number_format($selected_total);?></span>円</li>
        <li class="nextback"><a href="input" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a>
        </li>
        <li class="nextback"><a href="complete" class="btn-next">申し込む <i class="fa fa-chevron-circle-right"></i></a>
        </li>
      </ul>
    </div>
  </div>
