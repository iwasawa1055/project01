<div id="page-wrapper" class="wrapper outbound">
  <h1 class="page-header"><i class="fa fa-cube"></i> ボックス</h1>
  <ul class="ls-detail-box">
    <li>
      <ul class="l-detail-top">
        <li class="l-detail-img">
          <?php if (!empty($box['kit_cd']) && in_array($box['kit_cd'], array_keys(KIT_IMAGE))) : ?>
          <img src="<?php echo KIT_IMAGE[$box['kit_cd']]; ?>" alt="<?php echo KIT_NAME[$box['kit_cd']]; ?>" class="img-item">
          <?php elseif (!empty($box['product_cd']) && in_array($box['product_cd'], array_keys(PRODUCT_IMAGE))) : ?>
          <img src="<?php echo PRODUCT_IMAGE[$box['product_cd']]; ?>" alt="<?php echo PRODUCT_NAME[$box['product_cd']]; ?>" class="img-item">
          <?php else : ?>
          <img src="/images/box-other.png" alt="その他の画像" class="img-item">
          <?php endif; ?>
        </li>
        <li class="l-detail-desc">
          <ul>
            <li class="l-detail-info">
              <label class="txt-hline">ボックス名</label>
              <h2 class="ttl-item"><?php echo nl2br(h($box['box_name'])); ?></h2>
            </li>
            <li class="l-detail-info">
              <ul class="ls-status-box">
                <li class="l-status">
                  <label class="txt-hline">ボックスID</label>
                  <p class="txt-status"><?php echo h($box['box_id']); ?></p>
                </li>
                <li class="l-status">
                  <label class="txt-hline">ステータス</label>
                  <p class="txt-status"><?php echo h(BOX_STATUS_LIST[$box['box_status']]); ?></p>
                </li>
                <?php if(in_array($box['product_cd'], WRAPPING_TYPE_PRODUCT_CD_LIST, true)): ?>
                <li class="l-status">
                  <label class="txt-hline">外装の取り外し</label>
                  <?php if ($box['wrapping_type'] !== '' && in_array($box['wrapping_type'], array_keys(BOX_WRAPPING_TYPE_LIST))) : ?>
                  <p class="txt-status"><?php echo h(BOX_WRAPPING_TYPE_LIST[$box['wrapping_type']]);?></p>
                  <?php else : ?>
                  <p class="txt-status">-</p>
                  <?php endif; ?>
                </li>
                <?php endif; ?>
                <?php if(in_array($box['product_cd'], KEEPING_TYPE_PRODUCT_CD_LIST, true)): ?>
                <li class="l-status">
                  <label class="txt-hline">保管方法</label>
                  <?php if ($box['keeping_type'] !== '' && in_array($box['keeping_type'], array_keys(BOX_KEEPING_TYPE_LIST))) : ?>
                  <p class="txt-status"><?php echo h(BOX_KEEPING_TYPE_LIST[$box['keeping_type']]);?></p>
                  <?php else : ?>
                  <p class="txt-status">-</p>
                  <?php endif; ?>
                </li>
                <?php endif; ?>
                <li class="l-status">
                  <label class="txt-hline">入庫日</label>
                  <?php if ($box['box_status'] >= BOXITEM_STATUS_INBOUND_DONE) : ?>
                  <p class="txt-status"><?php echo $this->Html->formatYmdKanji($box['last_inbound_date']); ?></p>
                  <?php else : ?>
                  <p class="txt-status">未入庫</p>
                  <?php endif; ?>
                </li>
              </ul>
            </li>
          </ul>
        </li>
      </ul>
    </li>
    <li>
      <ul class="l-detail-bottom">
        <li class="l-detail-info">
          <label class="txt-hline">備考</label>
          <p class="txt-desc"><?php echo nl2br(h($box['box_note'])); ?></p>
        </li>
        <li class="l-detail-info">
          <?php $attention_message_flag = false; ?>
          <ul class="ls-action-box">
            <li class="l-action"><a class="btn-red-line" href="/box/detail/<?php echo $box['box_id']; ?>/edit">情報を編集する</a></li>
            <?php if ($box['product_cd'] == PRODUCT_CD_LIBRARY) : ?>
              <li class="l-action"><a class="btn-red" href="/outbound/library_select_item?box_id=<?php echo $box['box_id']; ?>">取り出しリスト登録</a></li>
            <?php elseif ($box['product_cd'] == PRODUCT_CD_CLOSET) : ?>
              <li class="l-action"><a class="btn-red" href="/outbound/closet_select_item?box_id=<?php echo $box['box_id']; ?>">取り出しリスト登録</a></li>
            <?php else : ?>
              <?php if (empty($denyOutboundList)) : ?>
                <li class="l-action">
                <?php echo $this->Form->create(false, ['url' => '/outbound/box', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                <?php echo $this->Form->hidden("box_id.${box['box_id']}", ['value' => '1']); ?>
                <button type="submit" class="btn-red">取り出しリスト登録</button>
                <?php echo $this->Form->end(); ?>
                </li>
              <?php else : ?>
                <li class="l-action"><a class="btn-red btn-disabled" href="javascript:void(0)">取り出しリスト登録</a></li>
                <?php $attention_message_flag = true; ?>
              <?php endif; ?>
            <?php endif; ?>
          </ul>
          <?php if ($attention_message_flag) : ?>
            <p class="txt-cancel">現在作業中または配送中のため、サービスの一部がご利用できません。</p>
          <?php endif; ?>
        </li>
      </ul>
    </li>
  </ul>
  <?php if (!empty($itemList)) : ?>
  <div class="item-content">
    <ul class="grid grid-md">
      <!--loop-->
      <?php foreach($itemList as $item): ?>
      <li class="l-item-dtl">
        <a href="/item/detail/<?php echo $item['item_id'];?>" class="link-dtl" ontouchstart></a>
        <div class="l-item-info box-info">
          <img src="<?php echo $item['image_first']['image_url']; ?>" alt="<?php echo $item['image_first']['item_id']; ?>">
          <p class="l-box-id">
            <span class="txt-box-id"><?php echo $item['item_id']; ?></span>
            <span class="txt-free-limit">入庫日<span class="date"><?php echo $this->Html->formatYmdKanji($item['box']['inbound_date']); ?></span></span>
          </p>
          <?php if (!empty($box['kit_cd'])) : ?>
            <p class="box-type"><?php echo KIT_NAME[$item['box']['kit_cd']] ?></p>
          <?php else : ?>
            <p class="box-type"><?php echo PRODUCT_NAME[$item['box']['product_cd']] ?></p>
          <?php endif; ?>
          <p class="box-name"><?php echo $item['item_name']; ?></p>
        </div>
      </li>
      <?php endforeach; ?>
      <!--loop end-->
    </ul>
  </div>
  <?php endif; ?>
</div>
<div class="nav-fixed">
  <ul>
    <li><a class="btn-d-gray" href="/box?product=">ボックス一覧へ戻る</a></li>
  </ul>
</div>
