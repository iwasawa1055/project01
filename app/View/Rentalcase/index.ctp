<?php $this->Html->script('minikura/outbound_limit', ['block' => 'scriptMinikura']); ?>
<?php $noSelect = (count($itemList) === 0)  ?>
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i>取り出し</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
  <?php if (!$noSelect) : ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <?php echo $this->Form->error("OutboundLimit.product", null, ['wrap' => 'p', 'class' => 'error-message-red']) ?>
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
                  <?php echo $this->element('List/item_body_rentalcase', ['item' => $item]); ?>
                  <?php echo $this->element('List/item_footer', ['item' => $item]); ?>
                </div>
              </div>
              <!--loop end-->
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php echo $this->Form->create('Rentalcase', ['url' => '/rentalcase/confirm', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true, 'class' => 'select-add-address-form']); ?>
    <?php if(!$customer->isSneaker()) : ?>
    <div class="panel panel-default" id="accordion-point">
      <div class="panel-body">
        <div class="row">
          <a data-toggle="collapse" data-parent="#accordion-point" href="#collapse-point">
            <div class="col-lg-12 accordion-heading accordion-point-header">
              <h2>ポイントのご利用</h2>
            </div>
          </a>
          <div class="col-lg-12 panel-collapse collapse" id="collapse-point">
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
    <?php /*
    <div class="panel panel-default" id="accordion-point">
      <div class="row">
        <div class="col-lg-12 input-point">
          <a class="accordion-heading" data-toggle="collapse" data-parent="#accordion-point" href="#collapse-point">
            <div class="panel-heading">
              <h2>ポイントのご利用</h2>
            </div>
          </a>
          <div id="collapse-point" class="panel-collapse collapse">
            <div class="panel-body">
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
    </div>*/ ?>
    <?php else : ?>
      <?php echo $this->Form->hidden("PointUse.use_point", ['value' => '0']); ?>
    <?php endif; ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="form-group col-lg-12">
          <label>お届け先住所</label>
          <?php echo $this->Form->select("OutboundLimit.address_id", $this->Order->setAddress($addressList), ['class' => 'form-control select-add-address', 'empty' => '以下からお選びください', 'error' => false, 'id' => 'OutboundAddressId']); ?>
          <?php echo $this->Form->error("OutboundLimit.address_id", null, ['wrap' => 'p']) ?>
          <?php if (!$this->Form->isFieldError('OutboundLimit.address_id')) : ?>
          <?php echo $this->Form->error('OutboundLimit.lastname', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('OutboundLimit.lastname_kana', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('OutboundLimit.firstname', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('OutboundLimit.firstname_kana', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('OutboundLimit.tel1', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('OutboundLimit.postal', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('OutboundLimit.pref', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('OutboundLimit.address1', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('OutboundLimit.address2', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('OutboundLimit.address3', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
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
                <?php echo $this->Form->input("OutboundLimit.aircontent_select", ['legend' => false, 'type' => 'radio', 'options' => OUTBOUND_HAZMAT, 'error' => false,
                      'class' => 'aircontent_select', 'before' => '<label>', 'after' => '</label>', 'separator' => '</label><label>']); ?>
                <?php echo $this->Form->error("OutboundLimit.aircontent_select", null, ['wrap' => 'p']) ?>
              </div>
              <!--航空機輸送禁止品目が含まれている場合、ここから表示-->
              <div class="aircontent">
                <label>含まれている場合、その品目をご記入ください。</label>
                <?php echo $this->Form->textarea('OutboundLimit.aircontent', ['class' => 'aircontent_text', 'error' => false]); ?>
                <?php echo $this->Form->error('OutboundLimit.aircontent', null, ['wrap' => 'p']) ?>
              </div>
              <!--航空機輸送禁止品目が含まれている場合、ここまで表示-->
            </div>
          </div>
        </div>
        <!-- 発送先が沖縄の場合、ここまで表示 -->

        <div class="form-group col-lg-12 datetime_select">
          <label>お届け希望日と時間帯</label>
          <?php echo $this->Form->select("OutboundLimit.datetime_cd", $this->Order->setDatetime($dateItemList), ['class' => 'form-control', 'empty' => false, 'error' => false, 'id' => 'OutboundDatetimeCd']); ?>
          <?php echo $this->Form->error("OutboundLimit.datetime_cd", null, ['wrap' => 'p']) ?>
        </div>
        <div class="form-group col-lg-12 outbound-expire" style="display:none;">
          <label>ご返却予定日</label>
          <p id="outbound-expire-date"></p>
          <?php echo $this->Form->hidden('OutboundLimit.expire', ['value' => $expireDate]);?>
          <?php echo $this->Form->error('OutboundLimit.expire', __d('validation', 'outbound_expire'), ['wrap' => 'p']) ?>
        </div>

        <div class="form-group col-lg-12">
          <?php echo $this->Form->error("OutboundLimit.product", null, ['wrap' => 'p', 'class' => 'error-message-red']) ?>
        </div>

        <span class="col-lg-6 col-md-6 col-xs-12">
          <a class="btn btn-primary btn-lg btn-block" href="/rentalcase/item">アイテムを選択に戻る</a>
        </span>
        <span class="col-lg-6 col-md-6 col-xs-12">
          <a class="btn btn-primary btn-lg btn-block" href="/rentalcase/mono">ボックスを選択に戻る</a>
        </span>
        <span class="col-lg-12 col-md-12 col-xs-12">
          <button type="submit" class="btn btn-danger btn-lg btn-block">この内容で確認する</button>
        </span>
        <input type="hidden" id="isolateIsland" value="<?php echo $isolateIsland; ?>">
      </div>
    </div>
    <?php echo $this->Form->end(); ?>
  <?php endif; ?>
  <?php if ($noSelect) : ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="col-lg-12">
          <?php echo $this->element('List/empty_outbound'); ?>
        </div>
        <span class="col-lg-6 col-md-6 col-xs-12">
          <a class="btn btn-primary btn-lg btn-block" href="/rentalcase/item">アイテムを取り出す</a>
        </span>
        <span class="col-lg-6 col-md-6 col-xs-12">
          <a class="btn btn-primary btn-lg btn-block" href="/rentalcase/mono">ボックスを取り出す</a>
        </span>
      </div>
    </div>
  <?php endif; ?>
  </div>
</div>
