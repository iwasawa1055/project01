    <?php
    $this->Html->script('jquery-ui.min', ['block' => 'scriptMinikura']);
    $this->Html->script('jquery.easing', ['block' => 'scriptMinikura']);
    $this->Html->script('outbound/library_confirm', ['block' => 'scriptMinikura']);
    ?>

    <div id="page-wrapper" class="wrapper outbound">
      <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> minikura Library</h1>
      <ul class="pagenation">
        <li><span class="number">1</span><span class="txt">アイテム<br>選択</span>
        </li>
        <li><span class="number">2</span><span class="txt">配送情報<br>入力</span>
        </li>
        <li class="on"><span class="number">3</span><span class="txt">確認</span>
        </li>
        <li><span class="number">4</span><span class="txt">完了</span>
        </li>
      </ul>
      <p class="page-caption">以下の内容でminikura Libraryの取り出し手続きを行います。</p>
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
                      <p class="l-conf-box-id"><?php echo $v['box']['box_name']; ?><br><?php echo $k; ?></p>
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
            <li><?php echo h("{$address['lastname']} {$address['firstname']}"); ?></li>
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
            <li>ご登録のクレジットカード</li>
            <li><?php echo $default_card['card_no']; ?></li>
            <li><?php echo $default_card['holder_name']; ?></li>
          </ul>
        </li>
          <?php if(!empty($use_point)) : ?>
            <li>
              <label class="headline">ご利用になるポイント</label>
              <ul class="li-address">
                <li><?php echo $use_point; ?>ポイント</li>
              </ul>
            </li>
          <?php endif;?>
        <li>
            <?php echo $this->element('keeping-period'); ?>
            <?php echo $this->element('about-fee'); ?>
        </li>
        <li class="caution-box">
          <p class="title">minikuraの他の商品と異なり、<br class="sp">お申し込み完了と同時に決済完了となります。</p>
          <div class="content">
            <div id="check-error"></div>
            <label class="input-check">
              <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">お申込み完了後、日時を含む内容の変更はお受けすることができません。<br>
                            内容にお間違いないか再度ご確認の上、「この内容で取り出す」にお進みください。</span>
            </label>
              <?php if (isset($datetime_cd) == false) : ?>
                <label class="input-check">
                  <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">1冊のみのお取り出しの場合、メール便での配送となります。<br>お届け希望日時および伝票追跡ができません。</span>
                </label>
              <?php endif ; ?>
          </div>
        </li>
        <li>
          <div class="panel panel-red">
            <div class="panel-heading">
              <label>ご注意ください</label>
              <ul>
                <li>
                  早期の取り出しについて、預け入れから1ヶ月以内の場合は月額保管料の2ヶ月分。2ヶ月以内の場合は月額保管料の1ヶ月分が料金として発生いたします。個品のお取り出しがある場合は適用致しません。
                </li>
              </ul>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div class="nav-fixed">
      <ul>
        <li><a class="btn-d-gray" href="/outbound/library_input_address">戻る</a></li>
        <li><button class="btn-red" id="execute">この内容で取り出す</button></li>
      </ul>
    </div>
