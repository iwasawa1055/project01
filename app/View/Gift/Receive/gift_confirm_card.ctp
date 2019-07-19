<?php $this->Html->script('gift/receive/input_card.js?'.time(), ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('https://maps.google.com/maps/api/js?key=' . Configure::read('app.googlemap.api.key') . '&libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/address', ['block' => 'scriptMinikura']); ?>

<?php $this->Html->css('/css/order/dsn-purchase.css', ['block' => 'css']); ?>

  <?php echo $this->Form->create('ReceiveGiftByCreditCard', ['url' => ['controller' => 'receive', 'action' => 'complete_card'], 'novalidate' => true]); ?>

    <div id="page-wrapper" class="l-detail-gift wrapper">
      <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ギフトをもらう</h1>
      <ul class="pagenation">
        <li><span class="number">1</span><span class="txt">コード<br>入力</span>
        </li>
        <li class="on"><span class="number">2</span><span class="txt">確認</span>
        </li>
        <li><span class="number">3</span><span class="txt">完了</span>
        </li>
      </ul>
      <p class="page-caption">以下の内容でギフトの登録を行います。</p>
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
            <?php foreach ($ReceiveGiftByCreditCard['kit_list'] as $kit_data): ?>
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
            <li>〒<?php echo h($ReceiveGiftByCreditCard['postal']); ?></li>
            <li><?php echo h($ReceiveGiftByCreditCard['address']); ?></li>
            <li><?php echo h($ReceiveGiftByCreditCard['name']); ?></li>
            <li><?php echo h($ReceiveGiftByCreditCard['tel1']); ?></li>
          </ul>
        </li>
      </ul>
      <ul class="input-info">
        <li class="caution-box">
          <p class="title">注意事項(ご確認の上、チェックしてください)</p>
          <div class="content">
            <label id="confirm_check" class="input-check">
              <input type="checkbox" class="cb-square">
              <span class="icon"></span>
              <span class="label-txt">
                aaaaaaaaaaaaaaaaaaa
              </span>
            </label>
            <label id="confirm_check" class="input-check">
              <input type="checkbox" class="cb-square">
              <span class="icon"></span>
              <span class="label-txt">
                bbbbbbbbbbbbbbbbbbb
              </span>
            </label>
            <label id="confirm_check" class="input-check">
              <input type="checkbox" class="cb-square">
              <span class="icon"></span>
              <span class="label-txt">
                ccccccccccccccccccc
              </span>
            </label>
          </div>
        </li>
      </ul>
    </div>
    <div class="nav-fixed">
      <ul>
        <li>
          <a class="btn-d-gray" href="/gift/receive/add">戻る</a>
        </li>
        <li>
          <button id="execute" class="btn-red" type="button">完了</button>
        </li>
      </ul>
    </div>
  <?php echo $this->Form->end(); ?>
