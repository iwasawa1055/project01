<?php
$this->Html->script('inbound_box/confirm', ['block' => 'scriptMinikura']);
?>
        <div id="page-wrapper" class="wrapper inbound">
            <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> ボックス預け入れ</h1>
            <ul class="pagenation">
                <li><span class="number">1</span><span class="txt">ボックス<br>選択</span>
                </li>
                <li><span class="number">2</span><span class="txt">ボックス<br>情報入力</span>
                </li>
                <li class="on"><span class="number">3</span><span class="txt">確認</span>
                </li>
                <li><span class="number">4</span><span class="txt">完了</span>
                </li>
            </ul>
            <p class="page-caption">以下の内容でボックスの預け入れ手続きを行います。</p>
            <?php echo $this->Form->create('', ['url' => '/inbound/box/complete', 'name' => 'form', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
            <div class="item-content">
                <ul class="grid grid-md">
                    <!--loop-->
                    <?php foreach ($target_box_list as $box): ?>
                    <li>
                        <label>
                            <?php // kit_cdがない場合のHAKOがあるので考慮 ?>
                            <?php if (($box['kit_cd'] == null || $box['kit_cd'] == '') && $box['product_cd'] == PRODUCT_CD_HAKO) : ?>
                            <span class="item-img"><img src="<?php echo h($this->Html->getProductImage(KIT_CD_HAKO)); ?>" alt="<?php echo h($box['product_name']); ?>" class="img-item"></span>
                            <?php else : ?>
                            <span class="item-img"><img src="<?php echo h($this->Html->getProductImage($box['kit_cd'])); ?>" alt="<?php echo h($box['product_name']); ?>" class="img-item"></span>
                            <?php endif; ?>
                        </label>
                        <div class="box-info">
                            <p class="box-id"><?php echo $box['box_id']; ?></p>
                            <p class="box-type"><?php echo h($box['product_name']); ?></p>
                            <p class="box-name"><?php echo h($this->Html->replaceBoxtitleChar($select_box_list_data[$box['box_id']]['title'])); ?></p>
                            <?php if (isset($select_box_list_data[$box['box_id']]['wrapping_type']) && $select_box_list_data[$box['box_id']]['wrapping_type'] == 1) :?>
                              <p class="txt-remove-package">外装を除いて撮影</p>
                            <?php endif; ?>
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
                        <li><?php echo INBOUND_CARRIER_DELIVERY[$inbound_base_data['delivery_carrier']] ?></li>
                    </ul>
                </li>
                <?php if (strpos($inbound_base_data['delivery_carrier'], INBOUND_DELIVERY_PICKUP) !== FALSE): ?>
                <li>
                    <label class="headline">お預かりに上がる住所</label>
                    <ul class="li-address">
                        <li>
                            〒<?php echo $inbound_base_data['postal']; ?>&nbsp;
                            <?php echo $inbound_base_data['pref']; ?><?php echo $inbound_base_data['address1']; ?><?php echo $inbound_base_data['address2']; ?><?php echo $inbound_base_data['address3']; ?>&nbsp;
                            <?php echo $inbound_base_data['lastname']; ?>
                            <?php echo $inbound_base_data['firstname']; ?>
                        </li>
                    </ul>
                </li>
                <li>
                    <label class="headline">お預かりに上がる日時</label>
                    <ul class="li-address">
                        <li><?php echo $this->Order->echoOption($dateList, 'date_cd', 'text', $inbound_base_data['day_cd']) ?> <?php echo $this->Order->echoOption($timeList, 'time_cd', 'text', $inbound_base_data['time_cd']) ?></li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if($box_use_flag[PRODUCT_CD_CLEANING_PACK]) :?>
                <li class="cleaning">
                    <label class="headline">クリーニングパックの保管</label>
                    <ul class="grid grid-md">
                        <li><label class="cleaning-check">
                                <input type="radio" class="rb-circle" name="data[InboundBase][keeping_type]" value="2"><span class="icon"></span>
                                <span class="item-img"><img src="/images/select-hunger.png" alt="ハンガー保管" class="img-item"></span>
                            </label>
                            <p class="cleaning-info">ハンガー保管</p>
                        </li>
                        <li>
                            <label class="cleaning-check">
                                <input type="radio" class="rb-circle" name="data[InboundBase][keeping_type]" value="1"><span class="icon"></span>
                                <span class="item-img"><img src="/images/select-fold.png" alt="タタミ保管" class="img-item"></span>
                            </label>
                            <p class="cleaning-info">タタミ保管</p>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <li class="caution-box">
                  <p class="title">注意事項(ご確認の上、チェックしてください)</p>
                  <div class="content">
                    <?php if($box_use_flag[PRODUCT_CD_MONO] || $box_use_flag[PRODUCT_CD_CLOSET] || $box_use_flag[PRODUCT_CD_LIBRARY] || $box_use_flag[PRODUCT_CD_CLEANING_PACK]) :?>
                    <label id="confirm_check" class="input-check agree-before-submit">
                      <input type="checkbox" class="cb-square">
                      <span class="icon"></span>
                      <span class="label-txt">
                         現在、撮影の完了まで倉庫にお荷物が届いてから10営業日前後頂戴しております。<br>
                         ※現在繁忙期につき、倉庫にお荷物が届いてから撮影の完了まで上記日数よりお時間を頂戴する可能性がございます。<br>
                         お荷物到着後、すぐの取り出しはできませんので、ご注意ください。<br>
                      </span>
                    </label>
                    <?php endif; ?>
                    <label id="confirm_check" class="input-check agree-before-submit">
                      <input type="checkbox" class="cb-square">
                      <span class="icon"></span>
                      <span class="label-txt">
                        重量は20kg（おおよそ1人で持ち運びできる程度）までを目安に梱包してください。<br>
                        ※明らかに20kgを超えた場合はお預かりできない場合がございます。1485円にて返送またはお荷物を受領できず運送会社にて持ち帰りになります。その場合、往復の送料はお客様の負担となります。
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
                        <strong style="font-weight:bold">上記に該当するお荷物が弊社に届いた場合、お預かりができません。1485円にて返送またはお荷物を受領できず運送会社にて持ち帰りになります。その場合、往復の送料はお客様の負担となります。</strong><br />
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
                    <?php if($box_use_flag[PRODUCT_CD_CLOSET]) :?>
                    <label id="hanger_check" class="input-check agree-before-submit">
                      <input type="checkbox" class="cb-square">
                      <span class="icon"></span>
                      <span class="label-txt">
                        Closet ボックスは、衣類および布製品以外はお預かりできません。
                      </span>
                    </label>
                    <?php endif; ?>
                    <?php if($box_use_flag[PRODUCT_CD_LIBRARY]) :?>
                    <label id="hanger_check" class="input-check agree-before-submit">
                      <input type="checkbox" class="cb-square">
                      <span class="icon"></span>
                      <span class="label-txt">
                        minikuraLibraryは開封・アイテム撮影するサービスですが、一枚単位の撮影はお断りしております。お客様が管理しやすい単位でおまとめをお願いいたします。
                      </span>
                    </label>
                    <?php endif; ?>
                    <?php if($box_use_flag[PRODUCT_CD_CLEANING_PACK]) :?>
                    <label class="input-check">
                      <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">クリーニングパックを6ヶ月を超えて保管をする場合、1パックにつき、月額500円(税抜)で保管ができます。</span>
                    </label>
                    <label class="input-check agree-before-submit">
                      <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">衣類の洗濯タグが全て不可になっているものはクリーニングできません。また、高級衣類についてはクリーニング不可または別途見積もりになります。<a class="link-charge" href="https://minikura.com/help/special_cleaning_charge.html" target="_blank">一覧はこちら</a></span>
                    </label>
                    <?php endif; ?>
                  </div>
                </li>
            </ul>
            <?php echo $this->Form->end(); ?>
        </div>
        <div class="nav-fixed">
            <ul>
                <li>
                  <a class="btn-d-gray" href="/inbound/box/attention">戻る</a>
                </li>
                <li>
                  <button class="btn-red" id="execute">この内容で預ける</button>
                </li>
            </ul>
        </div>
        <div class="remodal about-id" data-remodal-id="about-id">
            <p class="page-caption">バーコード番号(ボックスID)はボックス側面にバーコードと共に記載されています。</p>
            <img src="/images/about-id@2x.png" alt="">
            <a class="btn-close" data-remodal-action="close">閉じる</a>
        </div>
