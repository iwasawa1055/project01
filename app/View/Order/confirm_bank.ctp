  <?php $this->Html->script('order/input', ['block' => 'scriptMinikura']); ?>

  <?php echo $this->Form->create('PaymentAccountTransferKit', ['url' => ['controller' => 'order', 'action' => 'complete_bank'], 'novalidate' => true]); ?>

  <div id="page-wrapper" class="lineup wrapper">
      <?php echo $this->Flash->render(); ?>

      <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>

      <?php echo $this->element('Order/breadcrumb_list'); ?>

      <p class="page-caption">以下の内容でボックス購入手続きを行います。</p>

      <ul class="input-info">
        <?php foreach($order_list as $order_type => $order_data): ?>
        <li>
          <label class="headline">ご注文内容</label>
          <table class="usage-details">
            <thead>
            <tr>
              <th>商品名</th>
              <td>個数</td>
              <td>価格</td>
            </tr>
            </thead>
            <tbody>
              <?php foreach ($order_data as $key => $item): ?>
              <tr>
                <th><?php echo $item['kit_name'] ?></th>
                <td><?php echo $item['number'] ?></td>
                <td></td>
              </tr>
              <?php endforeach; ?>
              <tr>
                <th>合計</th>
                <td><?php echo $order_total_data[$order_type]['number'] ?></td>
                <td><?php echo $order_total_data[$order_type]['price'] ?></td>
              </tr>
            </tbody>
          </table>
        </li>
        <li>
          <label class="headline">配送住所</label>
          <ul class="li-address">
            <li>〒<?php echo h($PaymentAccountTransferKit['postal']); ?></li>
            <li><?php echo h($PaymentAccountTransferKit['address']); ?></li>
            <li><?php echo h($PaymentAccountTransferKit['name']); ?></li>
            <li><?php echo h($PaymentAccountTransferKit['tel1']); ?></li>
          </ul>
        </li>
        <li>
          <label class="headline">お届け日時</label>
          <ul class="li-address">
            <li><?php echo h($PaymentAccountTransferKit['select_delivery_text']); ?></li>
          </ul>
        </li>
        <li class="border_gray"></li>
        <?php endforeach; ?>
        <li>
          <label class="headline">決済</label>
          <ul class="li-credit">
            <li>口座振替</li>
          </ul>
        </li>
        <li class="caution-box">
          <p class="title">注意事項(ご確認の上、チェックしてください)</p>
          <div class="content">
            <label id="confirm_check" class="input-check">
              <input type="checkbox" class="cb-square">
              <span class="icon"></span>
              <span class="label-txt">
                お申込み完了後、日時を含む内容の変更はお受けすることができません。<br>
              </span>
            </label>
            <label id="confirm_check" class="input-check">
              <input type="checkbox" class="cb-square">
              <span class="icon"></span>
              <span class="label-txt">
                以下のお荷物は預け入れすることができません。<br />
                現金、有価証券、通帳、切手、印紙、証書、重要書類、印鑑、クレジットカード、キャッシュカード類<br />
                貴金属、美術品、骨董品、宝石、工芸品、毛皮、着物等の高額品または貴重品<br />
                精密機器、ガラス製品、陶磁器、仏壇等の壊れやすい物品<br />
                磁気を発し、その他の保管品に影響を与える物品<br />
                灯油、ガソリン、ガスボンベ、マッチ、ライター、塗料等の可燃物<br />
                農薬、劇薬、火薬、毒物、化学薬品、放射性物質等の危険物また劇物<br />
                食品、動物、植物（種子、苗を含む）<br />
                液体物<br />
                異臭、悪臭を発するまたは発するおそれのある物品<br />
                廃棄物<br />
                法令により所持を禁止されている物品<br />
                公序良俗に反する物品<br />
                弊社が保管に適さないと判断した物品<br />
                <br />
                上記に該当するお荷物が弊社に届いた場合、返送いたします。その場合、送料および手数料金（500円税抜き）はお客様の負担になります。<br />
              </span>
            </label>
            <label id="confirm_check" class="input-check">
              <input type="checkbox" class="cb-square">
              <span class="icon"></span>
              <span class="label-txt">
                お預かり中の保証につきまして、寄託価額（きたくかがく）を基に対応いたします。<br />
                寄託価額は1箱につき上限は１万円です。<br />
                寄託価額とは、寄託（保管するために預ける行為）する荷物の値打ちに相当する金額を指します。<br />
                保管中の荷物に万一の事故や弊社の過失によって損害が発生した場合などで保証できる金額の上限（時価額）となります。<br />
              </span>
            </label>
          </div>
        </li>
      </ul>
    </div>
    <div class="nav-fixed">
      <ul>
        <li><a class="btn-d-gray" href="/order/add">戻る</a>
        </li>
        <li>
          <button id="execute" class="btn-red" type="button">ボックスを購入</button>
        </li>
      </ul>
    </div>
  <?php echo $this->Form->end(); ?>
