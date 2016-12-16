<?php $this->Html->script('minikura/outbound_limit', ['block' => 'scriptMinikura']); ?>
<?php $noSelect = (count($itemList) === 0)  ?>
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-suitcase"></i> minikuraTRAVEL</h1>
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
                  <?php echo $this->element('List/item_body_travel', ['item' => $item]); ?>
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
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <h2>minikuraTRAVELでのお取り出しについて</h2>
            <div class="row box-list">
              <div id="travel" class="col-lg-12">
                <h3>ご利用料金</h3>
                <ul>
                  <li>¥5,000（税込）/1お申込みあたり</li>
                </ul>
                <h3>セットに含まれるサービス</h3>
                <ul>
                  <li>スーツケース　7日間レンタル料金（一律1週間）</li>
                  <li>往復送料</li>
                  <li>お洋服のクリーニング（3着まで）</li>
                </ul>
                <h3>ご注意事項</h3>
                <ul>
                  <li>スーツケースはお選びいただけません。（色：黒　外寸：60×41×26cm　容量：57L　重量：4.0kg）</li>
                  <li>鍵を掛けてお送りいたしますので、お申込後、鍵番号をメールにて配信いたします。</li>
                  <li>スーツケースのレンタル期間超過に関しては、300円（税込）/1日あたり　追加でいただきます。</li>
                  <li>スーツケースのレンタル期間はお届け希望日から、運送会社が返送を受け取る日までの1週間です。</li>
                  <li>クリーニングはお洋服3点までがセット料金に含まれます。</li>
                  <li>以下のものはクリーニングができません。
                    <ul>
                      <li>以下の衣類はお取り扱いできません。</li>
                      <li>皮革・毛皮製品</li>
                      <li>和服（着物・浴衣）</li>
                      <li>肌着・下着類</li>
                      <li>帽子・ドレスなど輸送中に型崩れが危惧されるもの</li>
                      <li>絹・カシミヤ・アンゴラ・ビキューナ5%以上の商品</li>
                      <li>乾いていない衣類（輸送中にカビ、においが付く恐れがあるため）</li>
                      <li>布団・毛布・枕など寝具類</li>
                      <li>礼服・制服類（急な入用の際、希望日にお届けできないため）＜寄託者の承諾がある場合は除きます＞</li>
                      <li>その他、クリーニング不可能と当社が判断したもの</li>
                    </ul>
                  </li>
                  <li>スーツケースをお戻しいただく際、以前お預かりしていたアイテムは極力元のボックスにお戻しいたします。また再撮影はいたしません。</li>
                  <li>新規のアイテムが多数ある場合、新しくボックスを作成し保管いたします（月額250円になります）。</li>
                  <li>アイテムを取り出す元のボックスの月額保管料は継続となります（月額250円になります）。</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php echo $this->Form->create('Travel', ['url' => '/travel/confirm', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true, 'class' => 'select-add-address-form']); ?>
    <?php if(!$customer->isSneaker()) : ?>
    <div class="panel panel-default" id="accordion-point">
      <div class="panel-body">
        <div class="row">
          <a data-toggle="collapse" data-parent="#accordion-point" href="#collapse-point">
            <div class="col-lg-12 accordion-heading accordion-point-header">
              <h2><i class="fa fa-angle-right" aria-hidden="true"></i> ポイントのご利用</h2>
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
          <a class="btn btn-primary btn-lg btn-block" href="/travel/item">アイテムを選択に戻る</a>
        </span>
        <span class="col-lg-6 col-md-6 col-xs-12">
          <a class="btn btn-primary btn-lg btn-block" href="/travel/mono">ボックスを選択に戻る</a>
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
          <a class="btn btn-primary btn-lg btn-block" href="/travel/item">アイテムを取り出す</a>
        </span>
        <span class="col-lg-6 col-md-6 col-xs-12">
          <a class="btn btn-primary btn-lg btn-block" href="/travel/mono">ボックスを取り出す</a>
        </span>
      </div>
    </div>
  <?php endif; ?>
  </div>
</div>
