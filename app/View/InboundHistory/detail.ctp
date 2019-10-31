        <div id="page-wrapper" class="wrapper l-history-dtl">
          <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> 預け入れ履歴詳細</h1>
          <div class="l-deposit-info">
            <dl>
              <dt><label class="headline">預け入れ申込日</label></dt>
              <dd>
                <p class="txt-detail"><?php echo h($this->Html->formatYmdKanjiDatetime($inbound_data['create_date'])); ?></p>
              </dd>
            </dl>
            <dl>
              <dt><label class="headline">入庫方法</label></dt>
              <dd>
                <?php if($inbound_data['box_delivery_type'] == BOX_DELIVERY_TYPE_YOURSELF): ?>
                <p class="txt-detail">自分で申込</p>
                <?php else: ?>
                <p class="txt-detail">集荷で申込</p>
                <?php endif;?>
              </dd>
            </dl>
            <dl>
              <dt><label class="headline">ステータス</label></dt>
              <dd>
                <?php if($inbound_data['works_progress_type'] == WORKS_PROGRESS_TYPE_COMPLETE): ?>
                <p class="txt-detail">完了</p>
                <?php else: ?>
                <p class="txt-detail">入庫依頼中</p>
                <?php endif;?>
              </dd>
            </dl>
          </div>
          <h2 class="ttl-dtl">預け入れ内容</h2>
          <ul class="l-lst-dtl">
            <?php foreach($box_list as $box): ?>
            <li class="l-lst-item">
              <ul class="l-lst-item-upper">
                <li class="l-img-item">
                  <?php if (!empty($box['kit_cd'])): ?>
                  <img src="<?php echo KIT_IMAGE[$box['kit_cd']]; ?>" alt="<?php echo KIT_NAME[$box['kit_cd']]; ?>" class="img-item">
                  <?php else : ?>
                  <img src="<?php echo PRODUCT_IMAGE[$box['product_cd']]; ?>" alt="<?php echo PRODUCT_NAME[$box['product_cd']]; ?>" class="img-item">
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
                    <?php if(in_array($box['product_cd'], WARAPPING_TYPE_PRODUCT_CD_LIST, true)): ?>
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
                    <li class="l-txt-box-action">
                      <a class="btn" href="/inbound_history/edit?box_id=<?php echo h($box['box_id']); ?>">内容を変更する</a>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>
            <?php endforeach; ?>
          </ul>
          <ul class="input-info">
            <?php if(!empty($pickup_data)) : ?>
            <li>
              <label class="headline">ボックスの発送方法</label>
              <ul class="li-address">
                <li>ヤマト運輸に依頼する</li>
              </ul>
            </li>
            <li>
              <label class="headline">集荷先</label>
              <ul class="li-address">
                <li>〒<?php echo h($pickup_data['pickup_yamato_postcode']); ?></li>
                <li><?php echo h($pickup_data['pickup_yamato_address1']); ?><?php echo h($pickup_data['pickup_yamato_address2']); ?></li>
                <li><?php echo h($pickup_data['pickup_yamato_name']); ?></li>
                <li><?php echo h($pickup_data['pickup_yamato_telephone']); ?></li>
              </ul>
            </li>
            <li>
              <label class="headline">集荷の日程</label>
              <ul class="li-address">
                <li><?php echo $this->Html->formatYmdKanji($pickup_data['pickup_date']); ?></li>
              </ul>
            </li>
            <li>
              <label class="headline">集荷の時間</label>
              <ul class="li-address">
                <li><?php echo INBOUND_PICKUP_TIME_CODE[$pickup_data['pickup_time_code']]; ?></li>
              </ul>
            </li>
            <?php else: ?>
            <li>
              <label class="headline">ボックスの発送方法</label>
              <ul class="li-address">
                <li>自分で発送する</li>
              </ul>
            </li>
            <?php endif; ?>
          </ul>
        </div>
        <div class="nav-fixed">
          <ul>
            <li>
              <a class="btn-d-gray" href="/inbound_history/">戻る</a>
            </li>
            <?php if(!empty($pickup_data)) : ?>
            <?php if (in_array($announcement_data['category_id'], ANNOUNCEMENT_CATEGORY_YAMATO)) : ?>
            <?php if (isset($pickup_yamato_change) && $pickup_yamato_change) : ?>
            <li>
              <a class="btn-red" href="/pickup/edit/<?php echo h($pickup_data['pickup_yamato_id']); ?>">集荷情報を変更する</a>
            </li>
            <?php endif;?>
            <?php endif;?>
            <?php endif;?>
          </ul>
        </div>