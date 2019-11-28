    <div id="page-wrapper" class="wrapper outbound">
      <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> minikura Library</h1>
      <ul class="pagenation">
        <li><span class="number">1</span><span class="txt">アイテム<br>選択</span>
        </li>
        <li><span class="number">2</span><span class="txt">配送情報<br>入力</span>
        </li>
        <li><span class="number">3</span><span class="txt">確認</span>
        </li>
        <li class="on"><span class="number">4</span><span class="txt">完了</span>
        </li>
      </ul>

      <p class="page-caption">以下の内容でminikura Libraryの取り出し手続きが完了しました。</p>
      <ul class="input-info">
          <?php if (isset($outbound_item_list)) : ?>
            <li>
              <label class="headline">アイテムの取り出し</label>
              <ul class="ls-conf-item">
                  <?php foreach($outbound_item_list as $k => $v): ?>
                    <li>
                      <p class="l-conf-item-pict"><img src="<?php echo $v['image_first']['image_url']; ?>" alt="<?php echo $v['item_name']; ?>" class="l-conf-img"></p>
                      <p class="l-conf-item-name"><?php echo $v['item_name']; ?><span><?php echo $v['item_id']; ?></span></p>
                      <p class="l-conf-item-price"><?php echo LIBRARY_OUTBOUND_PER_ITEM_PRICE; ?>円</p>
                    </li>
                  <?php endforeach; ?>
                <li>
                  <p class="l-conf-item-pict"></p>
                  <p class="l-conf-item-name">基本料金</p>
                  <p class="l-conf-item-price"><?php echo LIBRARY_OUTBOUND_BASIC_PRICE; ?>円</p>
                </li>
                <li>
                  <p class="l-conf-item-pict"></p>
                  <p class="l-conf-item-name">小計</p>
                  <p class="l-conf-item-price"><?php echo $outbound_item_price; ?>円</p>
                </li>
              </ul>
            </li>
          <?php endif; ?>
          <?php if (isset($outbound_box_list)) : ?>
            <li>
              <label class="headline">解約の取り出し</label>
              <ul class="ls-conf-box">
                  <?php foreach($outbound_box_list as $k => $v): ?>
                    <li>
                      <p class="l-conf-box-id"><?php echo $this->App->getBoxName($k); ?><br><?php echo $k; ?></p>
                      <ul class="l-conf-box-item">
                          <?php foreach($v['item'] as $kk => $vv): ?>
                            <li><?php echo $vv['item_name']; ?>(<?php echo $kk; ?>)</li>
                          <?php endforeach; ?>
                      </ul>
                      <p class="l-conf-box-price"><?php echo $v['price']; ?>円</p>
                    </li>
                  <?php endforeach; ?>
                <li>
                  <p class="l-conf-box-id"></p>
                  <p class="l-conf-box-item">小計</p>
                  <p class="l-conf-box-price"><?php echo $outbound_box_price; ?>円</p>
                </li>
              </ul>
            </li>
          <?php endif; ?>
        <li>
          <ul class="ls-conf-box">
            <?php if(!empty($use_point)) : ?>
            <li>
              <p class="l-conf-box-id"></p>
              <p class="l-conf-box-item">ポイントご利用</p>
              <p class="l-conf-box-price">-<?php echo number_format($use_point);?>円</p>
            </li>
            <?php endif;?>
            <li>
              <p class="l-conf-box-id"></p>
              <p class="l-conf-box-item">総計(税込み)</p>
              <p class="l-conf-box-price"><?php echo number_format($outbound_total_price - $use_point); ?>円</p>
            </li>
          </ul>
        </li>
        <li>
          <label class="headline">配送住所</label>
          <ul class="li-address">
            <li>〒<?php echo h($address['postal']); ?></li>
            <li><?php echo h($address['pref'] . $address['address1'] . $address['address2'] . $address['address3']); ?></li>
            <li><?php echo h("{$address['lastname']}{$address['firstname']}"); ?></li>
            <li><?php echo h($address['tel1']); ?></li>
          </ul>
        </li>
        <li>
          <label class="headline">配送方法</label>
          <ul class="li-address">
              <?php if (isset($datetime_cd) == false) : ?>
                <li class="note">メール便での配送</li>
              <?php else : ?>
                <li class="note">宅配便での配送</li>
                <li class="note"><?php echo $this->App->convDatetimeCode($datetime_cd); ?></li>
              <?php endif ; ?>
          </ul>
        </li>
        <li>
          <label class="headline">決済</label>
          <ul class="li-credit">
            <li>Amazon Pay</li>
          </ul>
        </li>
          <?php if(!empty($use_point)) : ?>
            <li>
              <label class="headline">ご利用されたポイント</label>
              <ul class="li-address">
                <li><?php echo $use_point; ?>ポイント</li>
              </ul>
            </li>
          <?php endif;?>
      </ul>
    </div>
    <div class="nav-fixed">
      <ul>
        <li><a class="btn-red" href="/">マイページへ戻る</a></li>
      </ul>
    </div>
    </div>
