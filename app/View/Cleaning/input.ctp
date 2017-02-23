<?php $this->Html->css('cleaning/app', ['block' => 'css']); ?>
<?php $this->Html->css('cleaning/app_dev', ['block' => 'css']); ?>

<?php $this->Html->script('cleaning/jquery.infinitescroll.min', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('cleaning/app', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('cleaning/app_dev', ['block' => 'scriptMinikura']); ?>



  <h1 class="page-header"><i class="fa icon-cleaning"></i> minikuraCLEANING</h1>
  <h2 class="page-caption"><a href="#">minikuraクリーニング <i class="fa fa-external-link-square"></i></a> に申し込むアイテムを選択してください。</h2>
  <div id="cleaning-wrapper">
    <div class="nav-cleaning">
      <ul>
        <li><i class="fa fa-calculator"></i><span id="block_selected_item">0</span>点<span id="block_selected_price">0</span>円</li>
        <li><button type="submit" id="item_confirm" class="btn-next-full">確認する <i class="fa fa-chevron-circle-right"></i></button></li>
      </ul>
    </div>
    <form action="input" id="item-search" novalidate="novalidate" method="get" accept-charset="utf-8">
    <div class="item-search">
      <a class="btn-option"><i class="fa fa-cog"></i><span> OPTION</span></a>
        <input type="search" placeholder="&#xF002; SEARCH" id="ItemSearchKeyword">
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
      <button type="submit" class="btn-view">表示する</button>
      <a href="#" class="btn-check"><i class="fa fa-check-circle"></i><span> クリア</span></a>
    </div>
    </form>
    <div class="grid">
      <form action="input_confirm" id="itemlist" method="post">
      <ul>
        <!--loop-->
        <?php foreach ($itemList as $item): ?>
        <li class="item">
          <div class="item-select">
            <label>
              <input type="checkbox" name="selected[]" class="checkbox" value="<?php echo $item['item_id'].",".$price[$item['item_group_cd']].",".$item['image_first']['image_url'];?>" data-itemid="<?php echo $item['item_id'];?>" data-price="<?php echo $price[$item['item_group_cd']];?>"<?php if ( $item['item_id'] === $selected_id ) echo " checked";?>><span class="check-icon"></span>
              <img src="<?php echo $item['image_first']['image_url'];?>" alt="<?php echo $item['item_id'];?>" class="item_img">
            </label>
            <a href="/item/detail/<?php echo $item['item_id'];?>" class="item-search"><i class="fa fa-search-plus"></i></a>
          </div>
          <div class="item-caption">
            <p class="item-id"><?php echo $item['item_id'];?></p>
            <p class="item-price"><?php echo number_format($price[$item['item_group_cd']]);?>円</p>
          </div>
        </li>
        <?php endforeach; ?>
        <!--loop end-->
      </ul>
      </form>
    </div>

  </div>
