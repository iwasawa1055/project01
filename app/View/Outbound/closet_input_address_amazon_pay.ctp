    <?php
    $this->Html->script('https://maps.google.com/maps/api/js?key=' . Configure::read('app.googlemap.api.key') . '&libraries=places', ['block' => 'scriptMinikura']);
    $this->Html->script('outbound/closet_input_address_amazon_pay', ['block' => 'scriptMinikura']);
    $this->Html->script('jquery.airAutoKana.js', ['block' => 'scriptMinikura']);

    $this->Html->css('dsn-amazon-pay', ['block' => 'css']);
    $this->Html->css('outbound/input_amazon_pay_dev', ['block' => 'css']);
    ?>
    <form method="POST" action="/outbound/closet_input_address_amazon_pay" name="form" id="form">
      <div id="page-wrapper" class="wrapper library">
        <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> minikura Closet</h1>
        <ul class="pagenation">
          <li><span class="number">1</span><span class="txt">アイテム<br>選択</span>
          </li>
          <li class="on"><span class="number">2</span><span class="txt">配送情報<br>入力</span>
          </li>
          <li><span class="number">3</span><span class="txt">確認</span>
          </li>
          <li><span class="number">4</span><span class="txt">完了</span>
          </li>
        </ul>
          <?php if (isset($datetime_cd_error)) : ?>
            <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i><?php echo $datetime_cd_error; ?></div>
          <?php endif; ?>
          <?php if (isset($amazon_order_reference_id_error)) : ?>
            <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i><?php echo $amazon_order_reference_id_error; ?></div>
          <?php endif; ?>
        <p class="page-caption">minikura Closetで取り出すアイテムの配送情報を入力します。</p>
        <ul class="input-info">
          <li>
            <label class="headline">配送住所と支払い方法</label>
            <div id="dsn-amazon-pay" class="form-group col-lg-12">
              <div class="dsn-adress">
                <div id="addressBookWidgetDiv">
                </div>
              </div>
              <div class="dsn-credit">
                <div id="walletWidgetDiv">
                </div>
              </div>
            </div>
          </li>

          <li>
            <label class="headline">お届け希望日時</label>
            <select name="datetime_cd" data-datetime_cd='<?php echo isset($datetime_cd) ? $datetime_cd : '0000-00-00'; ?>' id="datetime_cd">
            </select>
          </li>
          <li>
            <section class="l-input-pnt">
              <label class="headline">ポイントのご利用</label>
              <ul class="l-pnt-detail">
                <li>
                  <p class="txt-pnt">お持ちのポイントをご利用料金に割り当てることが出来ます。<br>
                    1ポイント1円として100ポイント以上の残高から10ポイント単位でご利用いただけます。</p>
                </li>
                <li>
                  <h3 class="title-pnt-sub">今回のご利用料金合計<span class="val"><?php echo number_format($outbound_total_price);?></span>円</h3>
                </li>
                <li>
                  <h3 class="title-pnt-sub">現在のお持ちのポイント<span class="val"><?php echo number_format($point_balance);?></span>ポイント</h3>
                </li>
                <li>
                  <h3 class="title-pnt-sub">ご利用可能ポイント<span class="val"><?php echo number_format($use_possible_point);?></span>ポイント</h3>
                </li>
                <li>
                  <p class="txt-pnt">ご利用状況によっては、お申込みされたポイントをご利用できない場合がございます。<br>取り出しのお知らせやオプションのお知らせにはポイント料金調整前の価格が表示されます。ご了承ください。
                  </p>
                </li>
                <li>
                  <label class="headline">ご利用になるポイントを入力ください</label>
                    <?php echo $this->Form->input('PointUseImmediate.use_point', ['id' => 'use_point', 'class' => 'use_point', 'type' => 'text', 'placeholder'=>'例：100', 'error' => false, 'label' => false, 'div' => false]); ?>
                    <?php echo $this->Form->error('PointUseImmediate.use_point', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
              </ul>
            </section>
          </li>
      </div>
    </form>
    <div class="nav-fixed">
      <ul>
        <li><a class="btn-d-gray" href="/outbound/closet_select_item">戻る</a></li>
        <li><button class="btn-red" id="execute">確認</button></li>
      </ul>
    </div>
    <input type="hidden" id="trunkCds" value='<?php echo json_encode($trunkCds); ?>'>
