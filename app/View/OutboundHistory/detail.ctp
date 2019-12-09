        <div id="page-wrapper" class="wrapper l-history-dtl">
          <?php $data_error = $this->Flash->render('data_error');?>
          <?php if (isset($data_error)) : ?>
            <p class="valid-bl"><?php echo $data_error; ?></p>
          <?php endif; ?>
          <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> お申し込み履歴詳細</h1>
          <div class="l-deposit-info">
            <dl>
              <dt><label class="headline">取り出し申込日</label></dt>
              <dd>
                <p class="txt-detail"><?php echo $this->Html->formatYmdKanjiDatetime($outbound_data['create_date']);?></p>
              </dd>
            </dl>
            <dl>
              <dt><label class="headline">ステータス</label></dt>
              <dd>
                <?php if (isset($outbound_data['link_status'])) : ?>
                    <?php if($outbound_data['link_status'] == WORKS_LINKAGE_LINK_STATUS_CANCEL): ?>
                    <p class="txt-detail">キャンセル済み</p>
                    <?php else: ?>
                    <p class="txt-detail">出庫依頼中</p>
                    <?php endif;?>
                <?php else: ?>
                    <?php if($outbound_data['works_progress_type'] == WORKS_PROGRESS_TYPE_COMPLETE): ?>
                    <p class="txt-detail">完了</p>
                    <?php else: ?>
                    <p class="txt-detail">出庫依頼中</p>
                    <?php endif;?>
                <?php endif;?>
              </dd>
            </dl>
            <ul class="l-cancel">
              <?php if (isset($outbound_data['link_status'])): ?>
              <?php if ($outbound_data['link_status'] === LINKAGE_LINK_STATUS_NON): ?>
              <li class="l-btn-cancel">
                <a class="btn-red-line" href="/outbound_history/cancel_confirm?wl_id=<?php echo $outbound_data['work_linkage_id']; ?>">取り出しキャンセル</a>
              </li>
              <?php elseif ($outbound_data['link_status'] === LINKAGE_LINK_STATUS_CANCEL): ?>
              <li class="l-btn-cancel">
                <a class="btn-red-line btn-disabled" href="javascript:void(0)">取り出しキャンセル</a>
              </li>
              <li class="l-txt-cancel">
                <p class="txt-cancel">キャンセル済みです。</p>
              </li>
              <?php endif; ?>
              <?php else : ?>
              <li class="l-btn-cancel">
                <a class="btn-red-line btn-disabled" href="javascript:void(0)">取り出しキャンセル</a>
              </li>
              <li class="l-txt-cancel">
                <p class="txt-cancel">作業中または発送完了しているため、キャンセルできません。</p>
              </li>
              <?php endif; ?>
            </ul>
          </div>
          <h2 class="ttl-dtl">取り出し内容</h2>
          <ul class="l-lst-dtl">
            <?php foreach($box_list as $box): ?>
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
                  <p class="txt-detail"><?php echo $box['box_name'];?></p>
                </li>

                <li class="l-txt-trade-name">
                  <label class="headline">商品名</label>
                  <p class="txt-detail"><?php echo $box['kit_name'];?></p>
                </li>
                <li class="l-lst-item-lower">
                  <ul class="l-lst-item-lower-dtl">
                    <li class="l-txt-box-id">
                      <label class="headline">ボックス ID</label>
                      <p class="txt-detail"><?php echo $box['box_id'];?></p>
                    </li>
                  </ul>
                </li>
              </ul>
              <?php if(isset($box['item_data'])): ?>
              <ul class="l-lst-itm-dtl">
                <?php foreach($box['item_data'] as $item): ?>
                <li>
                  <div class="l-itm-img"><img src="<?php echo $item['image_first']['image_url'];?>"></div>
                  <p class="txt-itm-ttl"><?php echo $item['item_name'];?></p>
                  <p class="txt-itm-id"><?php echo $item['item_id'];?></p>
                </li>
                <?php endforeach; ?>
              </ul>
              <?php endif;?>
            </li>
            <?php endforeach; ?>
          </ul>
          <ul class="input-info">
            <?php if (!empty($outbound_data['delivery_postcode'])) : ?>
            <li>
              <label class="headline">配送先</label>
              <ul class="li-address">
                <li>〒<?php echo $outbound_data['delivery_postcode'];?></li>
                <li><?php echo $outbound_data['delivery_state'] . $outbound_data['delivery_city'] . $outbound_data['delivery_street_address'] . ' ' . $outbound_data['delivery_suburb'];?></li>
              </ul>
            </li>
            <?php endif; ?>
            <?php if (!empty($outbound_data['delivery_name'])) : ?>
            <li>
              <label class="headline">配送先名</label>
              <ul class="li-address">
                <li><?php echo $outbound_data['delivery_name'];?> 様</li>
              </ul>
            </li>
            <?php endif; ?>
            <?php if (!empty($outbound_data['delivery_request_date']) && !empty($outbound_data['delivery_request_timezone'])) : ?>
            <li>
              <label class="headline">配送日時</label>
              <ul class="li-address">
                <li><?php echo $this->Html->formatYmdKanji($outbound_data['delivery_request_date']) . '　' . $outbound_data['delivery_request_timezone'];?></li>
              </ul>
            </li>
            <?php endif; ?>
          </ul>
        </div>
        <div class="nav-fixed">
          <ul>
            <li><a class="btn-d-gray" href="/outbound_history/">戻る</a>
            </li>
          </ul>
        </div>