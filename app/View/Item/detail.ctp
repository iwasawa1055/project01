<!-- TODO snsのjavascript消したことでヤフオクに影響が出ていないかを確認すること -->

<div id="page-wrapper">
  <h1 class="page-header"><i class="fa fa-diamond"></i> アイテム</h1>
  <ul class="ls-detail-item">
    <li class="l-detail-img">
      <?php if (!empty(Hash::get($item, 'image_first.image_url'))) : ?>
      <img src="<?php echo Hash::get($item, 'image_first.image_url'); ?>">
      <?php endif; ?>
    </li>
    <li class="l-detail-desc">
      <ul class="ls-detail-info">
        <li class="l-detail-info">
          <label class="txt-hline">アイテム名</label>
          <h2 class="ttl-item"><?php echo h($item['item_name']); ?></h2>
        </li>
        <li class="l-detail-info">
          <label class="txt-hline">備考</label>
          <p class="txt-desc"><?php echo h($item['item_note']); ?></p>
        </li>
        <li class="l-detail-info">
          <ul class="ls-status-item">
            <li class="l-status">
              <label class="txt-hline">ステータス</label>
              <p class="txt-status"><?php echo h(BOX_STATUS_LIST[$item['item_status']]); ?></p>
            </li>
            <li class="l-status">
              <label class="txt-hline">収納ボックスID</label>
              <p class="txt-status"><?php echo h($item['box_id']); ?></p>
            </li>
            <li class="l-status">
              <label class="txt-hline">アイテムID</label>
              <p class="txt-status"><?php echo h($item['item_id']); ?></p>
            </li>
            <li class="l-status">
              <label class="txt-hline">入庫日</label>
              <p class="txt-status"><?php echo $this->Html->formatYmdKanji($item['box']['inbound_date']); ?></p>
            </li>
          </ul>
          <ul class="ls-action-item">
            <?php /*ヤフオク*/ ?>
            <?php if (!empty($linkToAuction)): ?>
            <li class="l-action">
              <?php if (empty($denyOutboundList) && empty($sales)) : ?>
              <a class="btn-yellow" href="<?php echo $linkToAuction; ?>" target="_blank">ヤフオク!に出品</a>
              <?php else:?>
              <button class="btn-yellow" disabled="disabled">ヤフオク!に出品</button>
              <p class="error-message"><?php echo __('can_not_yahoo_auction'); ?></p>
              <?php endif;?>
            </li>
            <?php endif; ?>
            <?php /*クリーニング*/ ?>
            <?php if (!empty($linkToCleaning)): ?>
            <li class="l-action">
              <?php if ($flg_cleaning) :?>
              <a class="btn-blue" href="/cleaning-plus/input.php">クリーニングを申し込む</a>
              <?php else : ?>
              <a class="btn btn-info btn-md btn-block btn-detail btn-regist">クリーニングを申し込む</a>
              <p class="error-message">販売アイテムは選択できません。</p>
              <?php endif ?>
            </li>
            <?php endif; ?>
            <?php /*アイテム情報編集*/ ?>
            <li class="l-action"><a class="btn-red-line" href="/item/detail/<?php echo $item['item_id'] ?>/edit">アイテム情報を編集する</a></li>
            <?php /*取出し*/ ?>
            <?php if ($item['box']['product_cd'] == PRODUCT_CD_LIBRARY) : ?>
              <?php if ($item['item_status'] == BOXITEM_STATUS_INBOUND_DONE) : ?>
              <li class="l-action"><a class="btn-red" href="/outbound/library_select_item?item_id=<?php echo h($item['item_id']); ?>">取り出しリスト登録1</a></li>
              <?php else : ?>
              <li class="l-action"><a class="btn-red" href="javascript:void(0)">取り出しリスト登録2</a></li>
              <?php endif; ?>
            <?php elseif ($item['box']['product_cd'] == PRODUCT_CD_CLOSET) : ?>
              <?php if ($item['item_status'] == BOXITEM_STATUS_INBOUND_DONE) : ?>
                <li class="l-action"><a class="btn-red" href="/outbound/closet_select_item?item_id=<?php echo h($item['item_id']); ?>">取り出しリスト登録3</a></li>
              <?php else : ?>
                <li class="l-action"><a class="btn-red" href="javascript:void(0)">取り出しリスト登録4</a></li>
              <?php endif; ?>
            <?php else : ?>
              <?php if (empty($denyOutboundList)) : ?>
                <li class="l-action">
                <?php echo $this->Form->create(false, ['url' => '/outbound/item', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                <?php echo $this->Form->hidden("item_id.${item['item_id']}", ['value' => '1']); ?>
                <button type="submit" class="btn-red">取り出しリスト登録5</button>
                <?php echo $this->Form->end(); ?>
                </li>
              <?php else : ?>
                <li class="l-action"><a class="btn-red" href="javascript:void(0)">取り出しリスト登録6</a></li>
              <?php endif; ?>
            <?php endif; ?>
          </ul>
        </li>
      </ul>
    </li>
  </ul>
</div>
<div class="nav-fixed">
  <ul>
    <li><a class="btn-d-gray" href="/item/">一覧に戻る</a></li>







<!--    TODO 一度確認をする-->
    <li><button class="btn-d-gray" onclick="location.href='/box/detail.php'">このボックスを見る</button></li>







  </ul>
</div>
