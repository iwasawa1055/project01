<?php $this->Html->script('minikura/outbound', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('outbound/index', ['block' => 'scriptMinikura']); ?>
<?php $noSelect = (count($itemList) === 0 && count($boxList) === 0)  ?>

<div id="page-wrapper" class="wrapper">
  <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> ボックス取り出し</h1>
  <ul class="pagenation">
    <li><span class="number">1</span><span class="txt">取り出し<br>選択</span>
    </li>
    <li class="on"><span class="number">2</span><span class="txt">配送情報<br>入力</span>
    </li>
    <li><span class="number">3</span><span class="txt">確認</span>
    </li>
    <li><span class="number">4</span><span class="txt">完了</span> </li>
  </ul>
  <div class="row">
    <div class="col-lg-12">
      <?php if (!$noSelect) : ?>
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <?php echo $this->Form->error("Outbound.product", null, ['wrap' => 'p', 'class' => 'error-message-red']) ?>
              <?php if (!empty($itemList)) : ?>
              <h2>取り出すアイテム</h2>
              <?php endif; ?>
              <div class="row box-list">
                <?php foreach ($itemList as $item): ?>
                <?php
                $i = $item['item_id'];
                $url = '/item/detail/' . $item['item_id'];
                ?>
                <!--loop-->
                <div class="col-lg-12">
                  <div class="panel panel-default">
                    <?php echo $this->element('List/item_body_outbound', ['item' => $item]); ?>
                    <?php echo $this->element('List/item_footer', ['item' => $item]); ?>
                  </div>
                </div>
                <!--loop end-->
                <?php endforeach; ?>
              </div>
            </div>
            <div class="col-lg-12">
              <?php if (!empty($boxList)) : ?>
              <h2>取り出すボックス</h2>
              <?php endif; ?>
              <div class="row box-list">
                <?php foreach ($boxList as $box): ?>
                <!--loop-->
                <div class="col-lg-12">
                  <div class="panel panel-default">
                    <?php echo $this->element('List/box_body_outbound', ['box' => $box]); ?>
                    <?php echo $this->element('List/box_footer', ['box' => $box]); ?>
                  </div>
                </div>
                <!--loop end-->
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php echo $this->Form->create('Outbound', ['id' => 'target_form', 'url' => '/outbound/confirm', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true, 'class' => 'select-add-address-form']); ?>
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <h2>ポイントのご利用</h2>
              <p class="form-control-point col-lg-12"> お持ちのポイントをご利用料金に割り当てることが出来ます。<a href="<?php echo Configure::read('site.static_content_url'); ?>/lineup/points.html" class="animsition-link">▶ポイントについて</a>
                <br />
                ※1ポイント＝1円換算<br />
                ※100ポイントから利用可能です。<br />
                ※ポイントは100ポイント以上の残高かつ10ポイント単位からのご利用となります。</p>
              <div class="form-group col-lg-12">
                <span class="point"><?php echo $pointBalance['point_balance']; ?></span> ポイント利用可能です。
                <p class="help-block">ご利用状況によっては、お申込みされたポイントをご利用できない場合がございます。
                  取り出しのお知らせやオプションのお知らせにはポイント料金調整前の価格が表示されます。ご了承ください。</p>
                <h3>利用ポイント</h3>
                <div class="form-group col-lg-2">
                  <?php if (!empty($pointBalance['point_balance'])) : ?>
                    <?php echo $this->Form->input('PointUse.use_point', ['class' => 'form-control', 'error' => false]); ?>
                  <?php else : ?>
                    <?php echo $this->Form->input('PointUse.use_point', ['class' => 'form-control', 'value' => '0', 'readonly' => 'readonly', 'error' => false]); ?>
                  <?php endif; ?>
                </div>
                <div class="form-group col-lg-12">
                  <?php echo $this->Form->error("PointUse.use_point", null, ['wrap' => 'p']) ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="form-group col-lg-12">
            <label>お届け先住所</label>
            <?php echo $this->Form->select("Outbound.address_id", $this->Order->setAddress($addressList), ['class' => 'form-control select-add-address', 'empty' => '以下からお選びください', 'error' => false]); ?>
            <?php echo $this->Form->error("Outbound.address_id", null, ['wrap' => 'p']) ?>
            <?php if (!$this->Form->isFieldError('Outbound.address_id')) : ?>
            <?php echo $this->Form->error('Outbound.lastname', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
            <?php echo $this->Form->error('Outbound.lastname_kana', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
            <?php echo $this->Form->error('Outbound.firstname', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
            <?php echo $this->Form->error('Outbound.firstname_kana', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
            <?php echo $this->Form->error('Outbound.tel1', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
            <?php echo $this->Form->error('Outbound.postal', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
            <?php echo $this->Form->error('Outbound.pref', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
            <?php echo $this->Form->error('Outbound.address1', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
            <?php echo $this->Form->error('Outbound.address2', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
            <?php echo $this->Form->error('Outbound.address3', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
            <?php endif; ?>
          </div>

          <!-- 発送先が沖縄の場合、ここから表示 -->
          <div id="collapseOne" class="form-group col-lg-12 panel-collapse collapse in isolate_island_select">
            <div class="panel panel-red dispatch-okinawa">
              <div class="panel-heading">
                お預かり品を沖縄県へ発送する場合、下記の品物については航空機で輸送できませんので、船便となりお届け希望日は選択できません。<br />
                お問い合わせ伝票番号をお伝えしますので<a href="http://toi.kuronekoyamato.co.jp/cgi-bin/tneko" target="_blank">ヤマト運輸のサイト</a>
                で配送状況をご確認いただけます。
              </div>
              <div class="panel-body">
                <p>花火、化粧品、油性ペンキ、アロマオイル、石油ストーブ、シンナー、ヘアスプレー、接着剤、自動車エンジンオイル、マッチ、オイルライター・ガスライター、ガスボンベ、消火器、ダイビング用ボンベ、レジャー用ボンベ、携帯用酸素スプレー、タイヤ（空気の入っているもの）、バッテリー、病毒を移しやすい物質、GPS・携帯電話（スイッチONの状態）、木炭</p>
                <p>※詳しくは<a href="http://www.kuronekoyamato.co.jp/koukuutousai/" target="_blank">ヤマト運輸のサイト</a>
                  をご確認ください。</p>
              </div>
              <div class="panel-footer">
                <label>お届け品の中に上記の品物が含まれますか？</label>
                <div>
                  <?php echo $this->Form->input("Outbound.aircontent_select", ['legend' => false, 'type' => 'radio', 'options' => OUTBOUND_HAZMAT, 'error' => false,
                        'class' => 'aircontent_select', 'before' => '<label>', 'after' => '</label>', 'separator' => '</label><label>']); ?>
                  <?php echo $this->Form->error("Outbound.aircontent_select", null, ['wrap' => 'p']) ?>
                </div>
                <!--航空機輸送禁止品目が含まれている場合、ここから表示-->
                <div class="aircontent">
                  <label>含まれている場合、その品目をご記入ください。</label>
                  <?php echo $this->Form->textarea('Outbound.aircontent', ['class' => 'aircontent_text', 'error' => false]); ?>
                  <?php echo $this->Form->error('Outbound.aircontent', null, ['wrap' => 'p']) ?>
                </div>
                <!--航空機輸送禁止品目が含まれている場合、ここまで表示-->
              </div>
            </div>
          </div>
          <!-- 発送先が沖縄の場合、ここまで表示 -->

          <div class="form-group col-lg-12 datetime_select">
            <label>お届け希望日と時間帯</label>
            <?php echo $this->Form->select("Outbound.datetime_cd", $this->Order->setDatetime($dateItemList), ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
            <?php echo $this->Form->error("Outbound.datetime_cd", null, ['wrap' => 'p']) ?>
          </div>
          <div class="form-group col-lg-12">
            <?php echo $this->Form->error("Outbound.product", null, ['wrap' => 'p', 'class' => 'error-message-red']) ?>
          </div>
          <input type="hidden" id="isolateIsland" value="<?php echo $isolateIsland; ?>">
        </div>
        <input type="hidden" id="trunkCds" value='<?php echo json_encode($trunkCds); ?>'>
      </div>
      <?php echo $this->Form->end(); ?>
      <?php else:?>
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="col-lg-12">
            <?php echo $this->element('List/empty_outbound'); ?>
          </div>
        </div>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php if (!$noSelect) : ?>
<div class="nav-fixed l-nav-three">
  <ul>
    <li><a class="btn-d-gray"  href="/outbound/mono">アイテム<br class="sp">選択に戻る</a>
    </li>
    <li><a class="btn-d-gray" href="/outbound/box">ボックス<br class="sp">選択に戻る</a>
    </li>
    <li><a class="btn-red js-btn-submit" href="javascript:void(0)">この内容で<br class="sp">確認する</a>
    </li>
  </ul>
</div>
<?php else:?>
<div class="nav-fixed">
  <ul>
    <li><a class="btn-d-gray"  href="/outbound/mono">アイテムを取り出す</a>
    </li>
    <li><a class="btn-d-gray" href="/outbound/box">ボックスを取り出す</a>
    </li>
  </ul>
</div>
<?php endif;?>
