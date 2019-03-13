<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> ボックス預け入れ</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <?php echo $this->Form->create('Inbound', ['url' => '/inbound/box/complete', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12 none-float">
            <h2>預け入れボックスを選択</h2>
            <p class="form-control-static col-lg-12">以下の内容で預け入れ手続きを行います。</p>
            <div class="row box-list">
              <!--loop-->
              <?php $library = false; ?>
              <?php $closet = false; ?>
              <?php foreach ($boxList as $i => $box): ?>
              <?php
                    $formBox = $this->data['Inbound']['box_list'][$box['box_id']];
                    if (empty($formBox) || $formBox['checkbox'] === '0') {
                        continue;
                    }
                    $kitCd = $box['kit_cd'];
                    $kitName = '';
                    if (!empty($formBox['option'])) {
                        $kitName = KIT_OPTION[$kitCd][$formBox['option']];
                    }
                    if ($box['kit_cd'] == KIT_CD_LIBRARY_DEFAULT || $box['kit_cd'] == KIT_CD_LIBRARY_GVIDO) {
                        $library = true;
                    }
                    if ($box['kit_cd'] == KIT_CD_CLOSET) {
                        $closet = true;
                    }
                ?>
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-body <?php echo $this->MyPage->boxClassName($box); ?>">
                    <div class="row">
                      <div class="col-lg-9 col-md-9 col-sm-12">
                        <h3 class="boxitem-name"><?php echo h($this->Html->replaceBoxtitleChar($formBox['title'])); ?></h3>
                      </div>
                      <div class="col-lg-3 col-md-3 col-sm-12">
                        <p class="photo-control"><?php echo $kitName; ?></p>
                      </div>
                    </div>
                  </div>
                  <?php echo $this->element('List/box_footer', ['box' => $box]); ?>
                </div>
              </div>
              <?php endforeach; ?>
              <!--loop end-->
            </div>
          </div>
        </div>
        <div class="form-group col-lg-12">
          <label>預け入れ方法</label>
          <p class="form-control-static">
              <?php echo INBOUND_CARRIER_DELIVERY[$this->data['Inbound']['delivery_carrier']] ?>
          </p>
        </div>
        <?php if (strpos($this->Form->data['Inbound']['delivery_carrier'], INBOUND_DELIVERY_PICKUP) !== FALSE): ?>
        <div class="form-group col-lg-12">
          <label>集荷の住所</label>
          <p class="form-control-static">
              <?php echo $this->Order->setAddress($addressList)[$this->data['Inbound']['address_id']] ?>
          </p>
        </div>
        <div class="form-group col-lg-12">
          <label>集荷の日程</label>
          <p class="form-control-static">
              <?php echo $this->Order->echoOption($dateList, 'date_cd', 'text', $this->data['Inbound']['day_cd']) ?>
          </p>
        </div>
        <div class="form-group col-lg-12">
          <label>集荷の時間</label>
          <p class="form-control-static">
              <?php echo $this->Order->echoOption($timeList, 'time_cd', 'text', $this->data['Inbound']['time_cd']) ?>
          </p>
        </div>
        <?php endif; ?>
        <div class="form-group col-lg-12">
          <div class="panel panel-red">
            <div class="panel-heading">
              <h4>注意事項（ご確認の上、チェックしてください）</h4>
            </div>
            <div class="panel-body">
              <p>
                <label>
                  <input type="checkbox" class="agree-before-submit">
                  重量は20kg（おおよそ1人で持ち運びできる程度）までを目安に梱包してください。</label>
              </p>
              <p>重量超過およびお預かりできない物があった場合、お荷物を返送することがございます。その際、送料および返送手数料が発生いたしますので、あらかじめご了承ください。</p>
              <p>
                <label>
                  <input type="checkbox" class="agree-before-submit">
                    以下のお荷物は預け入れすることができません。<br />
                </label>
                <p>
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
                </p>
              </p>
              <p>
                <label>
                  <input type="checkbox" class="agree-before-submit">
                    お預かり中の保証につきまして、寄託価額（きたくかがく）を基に対応いたします。<br />
                  </label>
                  <p>
                    寄託価額は1箱につき上限は１万円です。<br />
                    寄託価額とは、寄託（保管するために預ける行為）する荷物の値打ちに相当する金額を指します。<br />
                    保管中の荷物に万一の事故や弊社の過失によって損害が発生した場合などで保証できる金額の上限（時価額）となります。<br />
                  </p>
              </p>
              <?php if ($library): ?>
              <p>
                <label>
                  <input type="checkbox" class="agree-before-submit">
                  minikuraLibraryは開封・アイテム撮影するサービスですが、一枚単位の撮影はお断りしております。お客様が管理しやすい単位でおまとめをお願いいたします。
                </label>
              </p>
              <?php endif; ?>
              <?php if ($closet): ?>
              <p>
                <label>
                  <input type="checkbox" class="agree-before-submit">
                  Closet ボックスは、衣類および布製品以外はお預かりできません。
                </label>
              </p>
              <?php endif; ?>
              <div class="caution">
                <img src="/images/burning@1x.png" srcset="/images/burning@1x.png 1x, /images/burning@2x.png 2x">
                <img src="/images/crack@1x.png" srcset="/images/crack@1x.png 1x, /images/crack@2x.png 2x">
                <img src="/images/liquid@1x.png" srcset="/images/liquid@1x.png 1x, /images/liquid@2x.png 2x">
                <?php if ($library) : ?>
                <img src="/images/loose@1x.png" srcset="/images/loose@1x.png 1x, /images/loose@2x.png 2x">
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group col-lg-12">
        <span class="col-lg-6 col-md-6 col-xs-12">
        <a class="btn btn-primary btn-lg btn-block" href="/inbound/box/add?back=true">戻る</a>
        </span>
        <span class="col-lg-6 col-md-6 col-xs-12">
            <button type="submit" class="btn btn-danger btn-lg btn-block">この内容で預け入れる</button>
        </span>
      </div>
      <?php echo $this->Form->end(); ?>
    </div>
  </div>
</div>
