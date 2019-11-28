  <div id="page-wrapper" class="l-detail-gift wrapper">
    <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ギフトをもらう</h1>
    <ul class="pagenation">
      <li><span class="number">1</span><span class="txt">コード<br>入力</span>
      </li>
      <li><span class="number">2</span><span class="txt">確認</span>
      </li>
      <li class="on"><span class="number">3</span><span class="txt">完了</span>
      </li>
    </ul>
    <p class="page-caption">以下の商品の登録が完了しました。<br>
      「預け入れ」メニューから「ボックス預け入れ」に進み、商品が登録されていることをご確認ください。</p>
    <ul class="input-info">
      <li>
        <label class="headline">ご注文内容</label>
        <table class="usage-details">
          <thead>
          <tr>
            <th>商品名</th>
            <td>個数</td>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($ReceiveGiftByAmazonPay['kit_list'] as $kit_data): ?>
          <tr>
            <th><?php echo KIT_NAME[$kit_data['kit_cd']]; ?></th>
            <td><?php echo $kit_data['kit_cnt']; ?></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </li>
      <li>
        <label class="headline">配送住所</label>
        <ul class="li-address">
          <li>〒<?php echo h($ReceiveGiftByAmazonPay['postal']); ?></li>
          <li><?php echo h($ReceiveGiftByAmazonPay['address']); ?></li>
          <li><?php echo h($ReceiveGiftByAmazonPay['name']); ?></li>
          <li><?php echo h($ReceiveGiftByAmazonPay['tel1']); ?></li>
        </ul>
      </li>
    </ul>
  </div>
  <div class="nav-fixed">
    <ul>
      <li>
        <a class="btn-red" href="/inbound/box/add">ボックス預け入れへ</a>
      </li>
    </ul>
  </div>

  <input type="hidden" value="<?php if (!empty($card_data)): ?>1<?php else: ?>0<?php endif; ?>" id="is_update">
