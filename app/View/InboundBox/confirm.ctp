<?php
$this->Html->script('inbound_box/confirm', ['block' => 'scriptMinikura']);
?>
        <div id="page-wrapper" class="wrapper inbound">
            <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> ボックス預け入れ</h1>
            <ul class="pagenation">
                <li><span class="number">1</span><span class="txt">ボックス<br>選択</span>
                </li>
                <li class="on"><span class="number">2</span><span class="txt">確認</span>
                </li>
                <li><span class="number">3</span><span class="txt">完了</span>
                </li>
            </ul>
            <p class="page-caption">以下の内容でボックスの預け入れ手続きを行います。</p>
            <?php echo $this->Form->create('Inbound', ['url' => '/inbound/box/complete', 'name' => 'form', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
            <div class="item-content">
                <ul class="grid grid-md">
                    <!--loop-->
                    <?php $library = false; ?>
                    <?php $closet = false; ?>
                    <?php $cleaning = false; ?>
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
                          if ($box['kit_cd'] == KIT_CD_CLEANING_PACK) {
                              $cleaning = true;
                          }
                      ?>
                        <li>
                            <label>
                                <span class="item-img"><img src="<?php echo h($this->Html->getProductImage($box['kit_cd'])); ?>" alt="<?php echo h($box['product_name']); ?>" class="img-item"></span>
                            </label>
                            <div class="box-info">
                                <p class="box-id"><?php echo $box['box_id']; ?></p>
                                <p class="box-type"><?php echo h($box['product_name']); ?></p>
                                <p class="box-name"><?php echo h($this->Html->replaceBoxtitleChar($formBox['title'])); ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    <!--loop end-->
                </ul>
            </div>
            <ul class="input-info">
                <li>
                    <label class="headline">ボックスの発送方法</label>
                    <ul class="li-address">
                        <li><?php echo INBOUND_CARRIER_DELIVERY[$this->data['Inbound']['delivery_carrier']] ?></li>
                    </ul>
                </li>
                <li>
                    <label class="headline">お預かりに上がる住所</label>
                    <ul class="li-address">
                        <li><?php echo $this->Order->setAddress($addressList)[$this->data['Inbound']['address_id']] ?></li>
                    </ul>
                </li>
                <li>
                    <label class="headline">お預かりに上がる日時</label>
                    <ul class="li-address">
                        <li><?php echo $this->Order->echoOption($dateList, 'date_cd', 'text', $this->data['Inbound']['day_cd']) ?> <?php echo $this->Order->echoOption($timeList, 'time_cd', 'text', $this->data['Inbound']['time_cd']) ?></li>
                    </ul>
                </li>
                <?php if($cleaning) :?>
                <li>
                    <label class="headline">クリーニングパックの保管</label>
                    <ul class="li-address">
                        <li>
                            <label class="input-check">
                                <input type="radio" class="rb" name="data[Inbound][keeping_type]" value="1" checked><span class="icon"></span>
                                <span class="label-txt">タタミ保管</span>
                            </label>
                        </li>
                        <li id="dev-self-delivery"><label class="input-check">
                                <input type="radio" class="rb" name="data[Inbound][keeping_type]" value="2"><span class="icon"></span>
                                <span class="label-txt">ハンガー保管</span>
                            </label>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <li class="caution-box">
                  <p class="title">注意事項(ご確認の上、チェックしてください)</p>
                  <div class="content">
                    <label id="confirm_check" class="input-check agree-before-submit">
                      <input type="checkbox" class="cb-square">
                      <span class="icon"></span>
                      <span class="label-txt">
                        ご購入完了後、日時を含む内容の変更およびキャンセルはお受けすることができません。<br>
                      </span>
                    </label>
                    <label id="confirm_check" class="input-check agree-before-submit">
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
                        <strong style="font-weight:bold">上記に該当するお荷物が弊社に届いた場合、お預かりができません。1390円にて返送またはお荷物を受領できず運送会社にて持ち帰りになります。その場合、往復の送料はお客様の負担となります。</strong><br />
                      </span>
                    </label>
                    <label id="confirm_check" class="input-check agree-before-submit">
                      <input type="checkbox" class="cb-square">
                      <span class="icon"></span>
                      <span class="label-txt">
                        お預かり中の保証につきまして、寄託価額（きたくかがく）を基に対応いたします。<br />
                        <strong style="font-weight:bold">寄託価額は1箱につき上限は１万円です。</strong><br />
                        寄託価額とは、寄託（保管するために預ける行為）する荷物の値打ちに相当する金額を指します。<br />
                        保管中のお荷物に万一の事故や弊社の過失によって損害が発生した場合などで保証できる金額の上限（時価額）となります。<br />
                      </span>
                    </label>
                    <?php if($closet) :?>
                    <label id="hanger_check" class="input-check agree-before-submit">
                      <input type="checkbox" class="cb-square">
                      <span class="icon"></span>
                      <span class="label-txt">
                        Closet ボックスは、衣類および布製品以外はお預かりできません。
                      </span>
                    </label>
                    <?php endif; ?>
                    <?php if($library) :?>
                    <label id="hanger_check" class="input-check agree-before-submit">
                      <input type="checkbox" class="cb-square">
                      <span class="icon"></span>
                      <span class="label-txt">
                        minikuraLibraryは開封・アイテム撮影するサービスですが、一枚単位の撮影はお断りしております。お客様が管理しやすい単位でおまとめをお願いいたします。
                      </span>
                    </label>
                    <?php endif; ?>
                  </div>
                </li>
            </ul>
            <?php echo $this->Form->end(); ?>
        </div>
        <div class="nav-fixed">
            <ul>
                <li><a class="btn-d-gray" href="/inbound/box/add?back=true">戻る</a>
                </li>
                <li><button class="btn-red" id="execute">この内容で預ける</button>
                </li>
            </ul>
        </div>
        <div class="remodal about-id" data-remodal-id="about-id">
            <p class="page-caption">バーコード番号(ボックスID)はボックス側面にバーコードと共に記載されています。</p>
            <img src="/images/about-id@2x.png" alt="">
            <a class="btn-close" data-remodal-action="close">閉じる</a>
        </div>
