    <div id="page-wrapper" class="wrapper l-history-dtl">
      <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> お申し込み内容変更</h1>
      <ul class="l-lst-dtl">
        <li class="l-lst-item">
          <ul class="l-lst-item-upper">
            <li class="l-img-item">
              <?php if (!empty($box['kit_cd']) && in_array($box['kit_cd'], array_keys(KIT_IMAGE))) : ?>
                <img src="<?php echo KIT_IMAGE[$box['kit_cd']]; ?>" alt="<?php echo KIT_NAME[$box['kit_cd']]; ?>" class="img-item">
              <?php elseif (!empty($box['product_cd']) && in_array($box['product_cd'], array_keys(PRODUCT_IMAGE))) : ?>
                <img src="<?php echo PRODUCT_IMAGE[$box['product_cd']]; ?>" alt="<?php echo PRODUCT_NAME[$box['product_cd']]; ?>" class="img-item">
              <?php else : ?>
                <img src="/images/box-other.png" alt="その他の画像" class="img-item">
              <?php endif; ?>
            </li>
            <li class="l-txt-box-name">
              <label class="headline">ボックス名</label>
              <p class="txt-detail"><?php echo h($box['box_name']); ?></p>
            </li>
            <li class="l-txt-trade-name">
              <label class="headline">商品名</label>
              <p class="txt-detail"><?php echo h($box['kit_name']); ?></p>
            </li>
            <li class="l-lst-item-lower">
              <ul class="l-lst-item-lower-dtl">
                <li class="l-txt-box-id">
                  <label class="headline">ボックス ID</label>
                  <p class="txt-detail"><?php echo h($box['box_id']); ?></p>
                </li>
                <li class="l-txt-box-status">
                  <label class="headline">ステータス</label>
                  <p class="txt-detail"><?php echo BOX_STATUS_LIST[$box['box_status']]; ?></p>
                </li>
                <?php if(in_array($box['product_cd'], WRAPPING_TYPE_PRODUCT_CD_LIST, true)): ?>
                <li class="l-txt-box-outer">
                  <label class="headline">外装の取り外し</label>
                  <?php if(empty($box['wrapping_type'])): ?>
                  <p class="txt-detail"><?php echo BOX_WRAPPING_TYPE_LIST[0];?></p>
                  <?php else:?>
                  <p class="txt-detail"><?php echo BOX_WRAPPING_TYPE_LIST[1];?></p>
                  <?php endif;?>
                </li>
                <?php endif;?>
                <?php if(in_array($box['product_cd'], KEEPING_TYPE_PRODUCT_CD_LIST, true)): ?>
                <li class="l-txt-box-storage">
                  <label class="headline">保管方法</label>
                  <p class="txt-detail"><?php echo BOX_KEEPING_TYPE_LIST[$box['keeping_type']];?></p>
                </li>
                <?php endif;?>
              </ul>
            </li>
          </ul>
        </li>

      </ul>

    </div>
    <div class="nav-fixed">
      <ul>
        <li>
          <a class="btn-d-gray" href="/inbound_history/edit?box_id=<?php echo h($box['box_id']); ?>&w_id=<?php echo $work_id; ?>">戻る</a>
        </li>
        <li><a class="btn-red" href="/inbound_history/complete">変更する</a>
        </li>
      </ul>
    </div>