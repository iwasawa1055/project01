    <div id="page-wrapper" class="wrapper l-history-dtl">
      <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> お申し込みキャンセル</h1>
      <ul class="pagenation">
        <li class="on"><span class="number">1</span><span class="txt">キャンセル<br>内容確認</span>
        </li>
        <li><span class="number">2</span><span class="txt">キャンセル<br>完了</span>
        </li>
      </ul>
      <p class="page-caption">以下の取り出しのお申し込みをキャンセルをします。</p>
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
            <li><?php echo $outbound_data['delivery_name'];?></li>
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
        <li><a class="btn-d-gray" href="/outbound_history/detail?wl_id=<?php echo $outbound_data['work_linkage_id']; ?>">戻る</a>
        </li>
        <li><a class="btn-red" href="/outbound_history/cancel_complete">この内容でキャンセル</a>
        </li>
      </ul>
    </div>