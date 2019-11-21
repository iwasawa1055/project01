    <div id="page-wrapper" class="wrapper l-history-dtl">
      <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> お申し込みキャンセル</h1>
      <p class="page-caption">取り出しのお申し込みキャンセルが完了しました。</p>
      <h2 class="ttl-dtl">キャンセル内容</h2>
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
        <li>
          <label class="headline">配送先</label>
          <ul class="li-address">
            <li>〒<?php echo $outbound_data['delivery_postcode'];?></li>
            <li><?php echo $outbound_data['delivery_state'] . $outbound_data['delivery_city'] . $outbound_data['delivery_street_address'] . ' ' . $outbound_data['delivery_suburb'];?></li>
          </ul>
        </li>
        <li>
          <label class="headline">配送先名</label>
          <ul class="li-address">
            <li><?php echo $outbound_data['delivery_name'];?></li>
          </ul>
        </li>
        <li>
          <label class="headline">配送日時</label>
          <ul class="li-address">
            <li><?php echo $this->Html->formatYmdKanji($outbound_data['delivery_request_date']) . '　' . $outbound_data['delivery_request_timezone'];?></li>
          </ul>
        </li>
      </ul>
    </div>
    <div class="nav-fixed">
      <ul>
        <li><a class="btn-red" href="/outbound_history/detail?wl_id=<?php echo $work_linkage_id; ?>">お申し込み履歴詳細へ戻る</a>
        </li>
      </ul>
    </div>