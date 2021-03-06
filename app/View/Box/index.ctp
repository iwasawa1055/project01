<?php $this->Html->script('minikura/box', ['block' => 'scriptMinikura']); ?>
<div id="page-wrapper" class="wrapper outbound">
  <h1 class="page-header"><i class="fa fa-cube"></i> ボックス</h1>
  <div class="l-search-group">
    <?php echo $this->Form->create('BoxSearch', ['type' => 'get','id' => 'box-search', 'url' => ['controller' => 'box', 'action' => 'index'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
    <ul class="l-word-search">
      <li>
        <?php echo $this->Form->text("keyword", ['class' => 'search', 'error' => false, 'placeholder' => '検索する', 'value' => $keyword]); ?>
        <?php echo $this->Form->text('product', ['type' => 'hidden', 'value' => $product]);?>
        <?php echo $this->Form->text('hide_outbound', ['type' => 'hidden', 'value' => $hide_outbound]);?>
      </li>
      <li>
        <button type="submit" class="btn-submit" value="search">検索</button>
      </li>
    </ul>
    <?php echo $this->Form->end(); ?>
    <?php echo $this->Form->create('BoxSort', ['type' => 'get','id' => 'box-sort', 'url' => ['controller' => 'box', 'action' => 'index'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
    <?php echo $this->Form->text('product', ['type' => 'hidden', 'value' => $product]);?>
    <?php echo $this->Form->text('hide_outbound', ['type' => 'hidden', 'value' => $hide_outbound]);?>
    <?php echo $this->Form->text('keyword', ['type' => 'hidden', 'value' => $keyword]);?>
    <ul class="l-option-group">
      <li>
        <label class="l-view-option">
          <input type="checkbox" class="cb-circle" name="dev-view-sort" <?php if (!empty($order) || !empty($direction)) : ?>checked="checked"<?php endif; ?>>
          <span class="icon"></span>
          <span class="txt-option">検索オプションを表示</span>
        </label>
      </li>
      <li>
        <label class="l-view-option">
          <input id="hideOutboundUrl" type="hidden" value="<?php echo $hideOutboundSwitchUrl; ?>">
          <input type="checkbox" class="cb-circle dev-outbound-flag" name="view-takenout" <?php if ($hideOutbound): ?>checked="checked"<?php endif; ?>>
          <span class="icon"></span>
          <span class="txt-option">取り出し済みを表示</span>
        </label>
      </li>
    </ul>
    <ul class="l-sort-item" id="dev-sort-item" <?php if (empty($order) && empty($direction)) : ?>style="display: none;"<?php endif; ?>>
      <li class="l-sort-date">
        <?php echo $this->Form->select('order', SORT_ORDER['box'], ['empty' => false, 'error' => false, 'value' => $order]); ?>
      </li>
      <li class="l-sort-az">
        <?php echo $this->Form->select('direction', SORT_DIRECTION, ['empty' => false, 'error' => false, 'value' => $direction]); ?>
      </li>
      <li class="l-sort-view">
        <button type="submit" class="btn-submit" value="sort">表示</button>
      </li>
      <li class="l-sort-clear">
        <a href="/box?product=<?php echo $product; ?>" class="btn-clear">クリア</a>
      </li>
    </ul>
    <?php echo $this->Form->end(); ?>
  </div>

  <div class="item-content dev-item-content">
    <?php if (!empty($boxList)) : ?>
    <ul class="grid grid-md">
      <!--loop-->
      <?php foreach ($boxList as $box): ?>
      <li class="l-item-dtl">
        <a href="/box/detail/<?php echo $box['box_id'];?>" class="link-dtl" ontouchstart></a>
        <div class="l-item-info box-info">
          <?php if (!empty($box['kit_cd']) && in_array($box['kit_cd'], array_keys(KIT_IMAGE))) : ?>
          <img src="<?php echo KIT_IMAGE[$box['kit_cd']]; ?>" alt="<?php echo KIT_NAME[$box['kit_cd']]; ?>" class="img-item">
          <?php elseif (!empty($box['product_cd']) && in_array($box['product_cd'], array_keys(PRODUCT_IMAGE))) : ?>
          <img src="<?php echo PRODUCT_IMAGE[$box['product_cd']]; ?>" alt="<?php echo PRODUCT_NAME[$box['product_cd']]; ?>" class="img-item">
          <?php else : ?>
          <img src="/images/box-other.png" alt="その他の画像" class="img-item">
          <?php endif; ?>
          <p class="l-box-id">
            <span class="txt-box-id"><?php echo $box['box_id']; ?></span>
            <?php if ($box['box_status'] >= BOXITEM_STATUS_INBOUND_DONE) : ?>
            <span class="txt-free-limit">入庫日<span class="date"><?php echo $this->Html->formatYmdKanji($box['last_inbound_date']); ?></span></span>
            <?php endif; ?>
          </p>
          <p class="box-status">ステータス<span class="value"><?php echo h(BOX_STATUS_LIST[$box['box_status']]); ?></span></p>
          <?php if (!empty($box['kit_cd'])) : ?>
          <p class="box-type"><?php echo KIT_NAME[$box['kit_cd']] ?></p>
          <?php else : ?>
            <p class="box-type"><?php echo PRODUCT_NAME[$box['product_cd']] ?></p>
          <?php endif; ?>
          <p class="box-name"><?php echo $box['box_name']; ?></p>
        </div>
      </li>
      <?php endforeach; ?>
      <!--loop end-->
    </ul>
    <?php echo $this->element('paginator_new'); ?>
    <?php else : ?>
      <h2 class="dev-none-item">対象のボックスは存在いたしません。</h2>
    <?php endif; ?>
  </div>
</div>