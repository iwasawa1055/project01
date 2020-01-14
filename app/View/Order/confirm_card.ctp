  <?php $this->Html->script('order/input', ['block' => 'scriptMinikura']); ?>

  <?php echo $this->Form->create('PaymentGMOKitByCreditCard', ['url' => ['controller' => 'order', 'action' => 'complete_card'], 'novalidate' => true]); ?>

  <div id="page-wrapper" class="lineup wrapper">
    <?php echo $this->Flash->render(); ?>

    <h1 class="page-header"><i class="fa fa-shopping-cart"></i> サービスの申し込み</h1>

    <?php echo $this->element('Order/breadcrumb_list'); ?>

    <p class="page-caption">以下の内容でサービスのお申し込み手続きを行います。</p>
    <?php foreach($order_list as $order_type => $order_product_list): ?>
    <div class="l-breakdown">
      <?php foreach($order_product_list as $product_cd => $order_kit_list): ?>
      <ul class="l-bd-item" id="mono">
        <li class="l-bd-header">
          <ul class="l-bd-title">
            <li class="img-bd-title">
              <picture>
                <img src="/images/order/photo-<?php echo PRODUCT_DATA_ARRAY[$product_cd]['photo_name']; ?>@1x.jpg" alt="minikura<?php echo PRODUCT_NAME[$product_cd]; ?>">
              </picture>
            </li>
            <li class="txt-bd-title"><?php echo PRODUCT_NAME[$product_cd]; ?>
            </li>
          </ul>
        </li>
        <?php foreach($order_kit_list as $kit_cd => $kit_data): ?>
        <li>
          <ul class="list-bd">
            <li class="body">
              <dl class="content">
                <dt class="items">プラン名</dt>
                <dd class="value"><?php echo $kit_data['kit_name']; ?></dd>
              </dl>
            </li>
            <li class="body">
              <dl class="content">
                <dt class="items">個数</dt>
                <dd class="value"><?php echo $kit_data['number']; ?></dd>
              </dl>
            </li>
            <li class="body">
              <dl class="content">
                <dt class="items">サービス申し込み料</dt>
                <dd class="value">
                  <?php if($order_type === 'cleaning') :?>
                  <?php echo number_format($order_total_data['price']); ?>円(税込)
                  <?php else:?>
                  0円
                  <?php endif; ?>
                </dd>
              </dl>
            </li>
            <?php if($order_type !== 'cleaning') :?>
            <li class="body">
              <dl class="content">
                <dt class="items">月額保管料</dt>
                <dd class="value">
                  <?php echo number_format(PRODUCT_DATA_ARRAY[$product_cd]['monthly_price'] * $kit_data['number']); ?>円(税抜)
                </dd>
              </dl>
            </li>
            <?php endif ?>
          </ul>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endforeach; ?>
    </div>
    <ul class="l-caution">
      <li class="caution-box">
        <p class="title">注意事項（ご確認の上、チェックしてください）</p>
        <div class="content">
          <?php if($order_type !== 'cleaning') :?>
          <label class="input-check">
            <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt"><span class="txt-date"><?php echo $free_limit_date; ?></span>までに倉庫に到着すると、お申し込み代金が無料でご利用いただけます。<br>その日付を超えてお荷物が到着した場合は　保管料金１ヶ月分の初期費用が発生します。</span>
          </label>
          <?php endif; ?>
          <?php if($order_type === 'hanger') :?>
          <label class="input-check">
            <?php if($order_kit_list[KIT_CD_CLOSET]['number'] > 2) : ?>
            <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">Closetボックスを３箱以上でお申し込みの場合、宅配便でのお届けになります。</span>
            <?php else: ?>
            <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">minikuraClosetはminikuraの他の商品と異なり、お届け日時が選べません。<br>ネコポスでの配送となりお客さまのポストに直接投函・配達します。<br>注文内容にお間違いないか再度ご確認の上、「ボックスの確認」にお進みください。</span>
            <?php endif; ?>
          </label>
          <?php endif; ?>
          <?php if($order_type === 'cleaning') :?>
          <label class="input-check">
            <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">6ヶ月を超えて保管をする場合、1パックにつき、月額500円で保管ができます。</span>
          </label>
          <label class="input-check">
            <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">衣類の洗濯タグが全て不可になっているものはクリーニングできません。また、高級衣類についてはクリーニング不可または別途見積もりになります。<a class="link-charge" href="https://minikura.com/help/special_cleaning_charge.html" target="_blank">一覧はこちら</a></span>
          </label>
          <?php endif; ?>
        </div>
      </li>
    </ul>
    <?php endforeach; ?>

    <ul class="l-subtotal" id="subtotal">
      <li>
        <ul class="list-bd">
          <li class="body">
            <dl class="content">
              <dt class="items">初月合計金額</dt>
              <dd class="value"><span class="txt-value"><?php echo number_format($order_total_data['price']); ?></span>円(税込)</dd>
            </dl>
          </li>
        </ul>
      </li>
    </ul>

    <ul class="input-info">
      <li>
        <label class="headline">配送住所</label>
        <ul class="li-address">
          <li>〒<?php echo h($PaymentGMOKitByCreditCard['postal']); ?></li>
          <li><?php echo h($PaymentGMOKitByCreditCard['address']); ?></li>
          <li><?php echo h($PaymentGMOKitByCreditCard['name']); ?></li>
          <li><?php echo h($PaymentGMOKitByCreditCard['tel1']); ?></li>
        </ul>
      </li>
      <?php if (!(array_key_exists('hanger', $order_list) && count($order_list) == 1)) :?>
      <li>
        <label class="headline">お届け日時</label>
        <ul class="li-address">
          <li><?php echo h($PaymentGMOKitByCreditCard['select_delivery_text']); ?></li>
        </ul>
      </li>
      <?php endif; ?>
      <li class="border_gray"></li>
      <li>
        <label class="headline">決済</label>
        <ul class="li-credit">
          <li>ご登録のクレジットカード</li>
          <li><?php echo h($card_data['card_no']); ?></li>
          <li><?php echo h($card_data['holder_name']); ?></li>
        </ul>
      </li>
      <li class="caution-box">
        <p class="title">注意事項(ご確認の上、チェックしてください)</p>
        <div class="content">
          <label id="confirm_check" class="input-check">
            <input type="checkbox" class="cb-square">
            <span class="icon"></span>
            <span class="label-txt">
              サービスの申し込み完了後、日時を含む内容の変更およびキャンセルはお受けすることができません。<br>
            </span>
          </label>
          <label id="confirm_check" class="input-check">
            <input type="checkbox" class="cb-square">
            <span class="icon"></span>
            <span class="label-txt">
              以下のお荷物は預け入れすることができません。<br />
              &nbsp&nbsp&nbsp現金、有価証券、通帳、切手、印紙、証書、重要書類、印鑑、クレジットカード、キャッシュカード類<br />
              &nbsp&nbsp&nbsp貴金属、美術品、骨董品、宝石、工芸品、毛皮、着物等の高額品または貴重品<br />
              &nbsp&nbsp&nbsp精密機器、ガラス製品、陶磁器、仏壇等の壊れやすい物品<br />
              &nbsp&nbsp&nbsp磁気を発し、その他の保管品に影響を与える物品<br />
              &nbsp&nbsp&nbsp灯油、ガソリン、ガスボンベ、マッチ、ライター、塗料等の可燃物<br />
              &nbsp&nbsp&nbsp農薬、劇薬、火薬、毒物、化学薬品、放射性物質等の危険物また劇物<br />
              &nbsp&nbsp&nbsp食品、動物、植物（種子、苗を含む）<br />
              &nbsp&nbsp&nbsp液体物<br />
              &nbsp&nbsp&nbsp異臭、悪臭を発するまたは発するおそれのある物品<br />
              &nbsp&nbsp&nbsp廃棄物<br />
              &nbsp&nbsp&nbsp法令により所持を禁止されている物品<br />
              &nbsp&nbsp&nbsp公序良俗に反する物品<br />
              &nbsp&nbsp&nbsp弊社が保管に適さないと判断した物品<br />
              <br />
              <strong style="font-weight:bold">上記に該当するお荷物が弊社に届いた場合、お預かりができません。1485円にて返送またはお荷物を受領できず運送会社にて持ち帰りになります。その場合、往復の送料はお客様の負担となります。</strong><br />
            </span>
          </label>
          <label id="confirm_check" class="input-check">
            <input type="checkbox" class="cb-square">
            <span class="icon"></span>
            <span class="label-txt">
              お預かり中の保証につきまして、寄託価額（きたくかがく）を基に対応いたします。<br />
              <strong style="font-weight:bold">寄託価額は1箱につき上限は１万円です。</strong><br />
              寄託価額とは、寄託（保管するために預ける行為）する荷物の値打ちに相当する金額を指します。<br />
              保管中のお荷物に万一の事故や弊社の過失によって損害が発生した場合などで保証できる金額の上限（時価額）となります。<br />
            </span>
          </label>
        </div>
      </li>
    </ul>
  </div>
  <div class="nav-fixed">
    <ul>
      <li><a class="btn-d-gray animsition-link" href="/order/add">戻る</a>
      </li>
      <li>
        <button id="execute" class="btn-red" type="button">サービスの申し込み</button>
      </li>
    </ul>
  </div>
  <?php echo $this->Form->end(); ?>
