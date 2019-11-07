<?php echo $this->Form->create('Box', ['url' => '/box/detail/'.$box['box_id'].'/edit', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
<div id="page-wrapper" class="wrapper outbound">
  <h1 class="page-header"><i class="fa fa-cube"></i> ボックス</h1>
  <ul class="ls-detail-box">
    <li>
      <ul class="l-detail-top">
        <li class="l-detail-img">
          <?php if (!empty($box['kit_cd'])) : ?>
            <img src="<?php echo KIT_IMAGE[$box['kit_cd']]; ?>" alt="<?php echo KIT_NAME[$box['kit_cd']]; ?>" class="img-item">
          <?php else : ?>
            <img src="<?php echo PRODUCT_IMAGE[$box['product_cd']]; ?>" alt="<?php echo PRODUCT_NAME[$box['product_cd']]; ?>" class="img-item">
          <?php endif; ?>
        </li>
        <li class="l-detail-desc">
          <ul>
            <li class="l-detail-info">
              <label class="txt-hline">ボックス名</label>
                <?php echo $this->Form->textarea('Box.box_name', ['class' => "h7", 'error' => false]); ?>
                <?php echo $this->Form->error('Box.box_name', null, ['wrap' => 'p']) ?>
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
                  <?php if (in_array($box['wrapping_type'] !== '' && $box['wrapping_type'], BOX_WRAPPING_TYPE_LIST)) : ?>
                  <p class="txt-status"><?php echo h(BOX_WRAPPING_TYPE_LIST[$box['wrapping_type']]);?></p>
                  <?php else : ?>
                  <p class="txt-status">-</p>
                  <?php endif; ?>
                </li>
                <?php endif; ?>
                <?php if(in_array($box['product_cd'], KEEPING_TYPE_PRODUCT_CD_LIST, true)): ?>
                <li class="l-status">
                  <label class="txt-hline">保管方法</label>
                  <?php if ($box['keeping_type'] !== '' && in_array($box['keeping_type'], BOX_KEEPING_TYPE_LIST)) : ?>
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
          <?php echo $this->Form->textarea('Box.box_note', ['class' => "h10", 'error' => false]); ?>
          <?php echo $this->Form->error('Box.box_note', null, ['wrap' => 'p']) ?>
        </li>
      </ul>
    </li>
  </ul>
</div>
<div class="nav-fixed">
  <ul>
    <li><a class="btn-d-gray" href="/box/detail/<?php echo $box['box_id'] ?>">詳細に戻る</a></li>
    <li><button type="submit" class="btn-red">情報を保存する</button></li>
  </ul>
</div>
<?php echo $this->Form->hidden('Box.box_id'); ?>
<?php echo $this->Form->end(); ?>
