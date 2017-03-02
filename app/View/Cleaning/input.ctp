<?php $this->Html->css('cleaning/app', ['block' => 'css']); ?>
<?php $this->Html->css('cleaning/app_dev', ['block' => 'css']); ?>

<?php $this->Html->script('remodal.min', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('cleaning/jquery.infinitescroll.min', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('cleaning/app', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('cleaning/app_dev', ['block' => 'scriptMinikura']); ?>
  <h1 class="page-header"><i class="fa icon-cleaning"></i> minikuraCLEANING</h1>
  <h2 class="page-caption"><a href="#">minikuraクリーニング <i class="fa fa-external-link-square"></i></a> に申し込むアイテムを選択してください。</h2>
  <div id="cleaning-wrapper">
    <div class="nav-cleaning">
      <ul>
        <li><i class="fa fa-calculator"></i><span class="block_selected_item">0</span>点<span class="block_selected_price">0</span>円</li>
        <li><button type="submit" class="btn-next-full item_confirm">確認する <i class="fa fa-chevron-circle-right"></i></button></li>
      </ul>
    </div>
    <form action="input" id="item-search" novalidate="novalidate" method="get" accept-charset="utf-8">
    <div class="item-search">
      <a class="btn-option"><i class="fa fa-cog"></i><span> OPTION</span></a>
        <input type="search" placeholder="&#xF002; SEARCH" id="ItemSearchKeyword" name="keyword" />
    </div>
    <div class="item-sort">
      <select name="order" id="ItemSortOrder" class="data-sort">
      <?php foreach ( SORT_ORDER['item'] as $value=>$name ) : ?>
        <option value="<?php echo $value;?>"<?php if( $order === $value ) echo " selected"; ?>><?php echo $name;?></option>
      <?php endforeach ?>
      </select>
      <select name="direction" id="ItemSortDirection" class="az-sort">
      <?php foreach ( SORT_DIRECTION as $value=>$name ) : ?>
        <option value="<?php echo $value;?>"<?php if( $direction === $value ) echo " selected"; ?>><?php echo $name;?></option>
      <?php endforeach ?>
      </select>
      <input type="hidden" name="reset" value="true">
      <button type="submit" class="btn-view">表示する</button>
      <button type="button" id="ClearSelected" class="btn-check active"><i class="fa fa-check-circle"></i><span> クリア</span></button>
    </div>
    </form>
    <div class="grid">
      <form action="confirm" id="itemlist" method="post">
      <input type="hidden" id="ItemSelected" value="<?php echo $selected_id;?>">
      <ul>
        <!--loop-->
        <?php foreach ($itemList as $item): ?>
        <li class="item">
          <div class="item-select" data-page="<?php echo $page;?>">
            <label>
              <input type="checkbox" name="selected[]" class="checkbox" value="<?php echo $item['item_id'].",".$item['item_group_cd'].",".$item['box_id'].",".$item['box']['product_cd'].",".$item['image_first']['image_url'];?>" data-itemid="<?php echo $item['item_id'];?>" data-price="<?php echo $price[$item['item_group_cd']];?>"<?php if ( $item['item_id'] === $selected_id ) echo " checked";?>><span class="check-icon"></span>
              <img src="<?php echo $item['image_first']['image_url'];?>" alt="<?php echo $item['item_id'];?>" class="item_img">
            </label>
            <a href="#" class="item-search" data-remodal-target="<?php echo $item['item_id'];?>"><i class="fa fa-search-plus"></i></a>
          </div>
          <div class="item-caption">
            <p class="item-id"><?php echo $item['item_id'];?></p>
            <p class="item-price"><?php echo number_format($price[$item['item_group_cd']]);?>円</p>
          </div>
          <!--Item modal-->
          <div class="remodal items" data-remodal-id="<?php echo $item['item_id'];?>" role="dialog" aria-labelledby="<?php echo $item['item_name'];?>" aria-describedby="<?php echo $item['item_note'];?>" data-remodal-options="hashTracking:false">
            <div class="pict-box">
              <img src="<?php echo $item['image_first']['image_url'];?>" alt="<?php echo $item['item_id'];?>">
            </div>
            <div class="title-box">
              <p class="item-id"><?php echo $item['item_id'];?></p>
              <h3><?php echo $item['item_name'];?></h3>
              <p class="item-caption"><?php echo $item['item_note'];?></p>
            </div>
            <a class="btn-close" data-remodal-action="close" class="" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
          </div>
        </li>
        <?php endforeach; ?>
        <!--loop end-->
      </ul>
      </form>
      <?php echo $this->element('paginator'); ?>
    </div>
  </div>
<div id="sp-cleaning-wrapper">
  <div class="sp-nav-cleaning">
    <ul>
      <li class="price"><i class="fa fa-calculator"></i><span class="block_selected_item">0</span>点<span class="block_selected_price">0</span>円</li>
      <li><button type="submit" class="btn-next-full item_confirm">確認する <i class="fa fa-chevron-circle-right"></i></button>
      </li>
    </ul>
  </div>
</div>