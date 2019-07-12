<?php
$this->Html->script('https://maps.google.com/maps/api/js?key=' . Configure::read('app.googlemap.api.key') . '&libraries=places', ['block' => 'scriptMinikura']);
$this->Html->script('jquery.easing', ['block' => 'scriptMinikura']);
$this->Html->script('minikura/address', ['block' => 'scriptMinikura']);
$this->Html->script('inbound_box/add_amazon_pay.js?'.time(), ['block' => 'scriptMinikura']);
$this->Html->script('pickupYamato', ['block' => 'scriptMinikura']);
$this->Html->css('/css/dsn-amazon-pay.css', ['block' => 'css']);
$this->Html->css('/css/add_amazon_pay_dev.css', ['block' => 'css']);
?>
        <div id="page-wrapper" class="wrapper inbound">
            <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> ボックス預け入れ</h1>
            <ul class="pagenation">
                <li class="on"><span class="number">1</span><span class="txt">ボックス<br>選択</span>
                </li>
                <li><span class="number">2</span><span class="txt">個数入力</span>
                </li>
                <li><span class="number">3</span><span class="txt">確認</span>
                </li>
                <li><span class="number">4</span><span class="txt">完了</span>
                </li>
            </ul>
            <?php echo $this->Flash->render(); ?>
            <form name="form" action='/inbound/box/input_amazon_pay' method="POST">
                <?php echo $this->Form->error("InboundBase.box", null, ['wrap' => 'p']) ?>
                <ul class="setting-switcher">
                    <li>
                        <label class="setting-switch">
                            <input type="radio" class="ss" name="data[InboundBase][box_type]" value="new" <?php echo (!isset($this->request->data['InboundBase']['box_type']) || $this->request->data['InboundBase']['box_type'] == 'new') ? 'checked' : ''; ?>>
                            <span class="btn-ss"><span class="icon"></span>新しく購入した<br class="sp">ボックスを預ける</span>
                        </label>
                    </li>
                    <li>
                        <label class="setting-switch">
                            <input type="radio" class="ss" name="data[InboundBase][box_type]" value="old" <?php echo ($this->request->data['InboundBase']['box_type'] == 'old') ? 'checked' : ''; ?>>
                            <span class="btn-ss"><span class="icon"></span>取り出し済ボックスを<br class="sp">再度預ける</span>
                        </label>
                    </li>
                    <input type='hidden' id='dev-selected-box_type' value="<?php echo $this->request->data['InboundBase']['box_type']?>">
                </ul>
                <div id="dev-new-box" class="item-content">
                    <ul class="l-caution">
                        <li>
                            <a href="javascript:void(0)" data-remodal-target="about-id" class="about-box-id title-caution">
                                <img src="/images/question.svg">ボックスIDについて
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" data-remodal-target="packaging" class="about-box-id title-caution">
                                <img src="/images/question.svg">「外装を除いて撮影」について
                            </a>
                        </li>
                    </ul>
                    <ul id="dev-new-box-grid" class="grid grid-md">
                        <?php foreach ($new_box_list as $key => $new_box): ?>
                        <li>
                            <label class="input-check box-img-area">
                                <input type="checkbox" name="data[BoxList][new][<?php echo $new_box['box_id']; ?>][checkbox]" class="cb-circle dev-box-check" value="1" <?php if(isset($box_list_data['new'][$new_box['box_id']]['checkbox'])): ?>checked="checked"<?php endif; ?>>
                                <span class="icon"></span>
                                <span class="item-img">
                                  <img src="<?php echo KIT_IMAGE[$new_box['kit_cd']] ?>" alt="<?php echo $new_box['kit_name']; ?>" class="img-item">
                                </span>
                            </label>
                            <div class="box-info">
                                <p class="box-id"><?php echo $new_box['box_id']; ?></p>
                                <p class="box-type"><?php echo $new_box['kit_name']; ?></p>
                                <input type="text" name="data[BoxList][new][<?php echo $new_box['box_id']; ?>][title]" placeholder="ボックス名を記入してください" class="box-input-name" value="<?php if(isset($box_list_data['new'][$new_box['box_id']]['title'])) echo $box_list_data['new'][$new_box['box_id']]['title']; ?>">
                                <?php if($new_box['product_cd'] === PRODUCT_CD_MONO): ?>
                                <label class="input-check">
                                    <input type="checkbox" name="data[BoxList][new][<?php echo $new_box['box_id']; ?>][wrapping_type]" class="cb-square" value="1" <?php if(isset($box_list_data['new'][$new_box['box_id']]['wrapping_type'])): ?>checked="checked"<?php endif; ?>>
                                    <span class="icon"></span>
                                    <span class="label-txt">外装を除いて撮影</span>
                                </label>
                                <?php endif;?>
                                <?php echo $this->Form->error("InboundBase.box_list.new." . $new_box['box_id'] . '.title', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div id="dev-old-box" class="item-content">
                    <p class="page-caption">minikuraHAKOのみ再度のお預け入れが可能でございます。<br>
                        ボックスの状態については十分ご確認の上、ご利用ください。<br>
                        なお、再度のお預け入れの場合、初月保管料金の無料は含まれておりません。</p>
                    <ul class="l-caution">
                        <li>
                            <a href="javascript:void(0)" data-remodal-target="about-id" class="about-box-id title-caution">
                                <img src="/images/question.svg">ボックスIDについて
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" data-remodal-target="packaging" class="about-box-id title-caution">
                                <img src="/images/question.svg">「外装を除いて撮影」について
                            </a>
                        </li>
                    </ul>
                    <ul id="dev-old-box-grid" class="grid grid-md">
                        <?php foreach ($old_box_list as $key => $old_box): ?>
                        <li>
                            <label class="input-check box-img-area">
                                <input type="checkbox" name="data[BoxList][old][<?php echo $old_box['box_id']; ?>][checkbox]" class="cb-circle dev-box-check" value="1" <?php if(isset($box_list_data['old'][$old_box['box_id']]['checkbox'])): ?>checked="checked"<?php endif; ?>>
                                <span class="icon"></span>
                                <span class="item-img">
                            <img src="<?php echo KIT_IMAGE[$old_box['kit_cd']] ?>" alt="<?php echo $old_box['kit_name']; ?>" class="img-item">
                          </span>
                            </label>
                            <div class="box-info">
                                <p class="box-id"><?php echo $old_box['box_id']; ?></p>
                                <p class="box-type"><?php echo $old_box['kit_name']; ?></p>
                                <input type="text" name="data[BoxList][old][<?php echo $old_box['box_id']; ?>][title]" placeholder="ボックス名を記入してください" class="box-input-name" value="<?php if(isset($box_list_data['old'][$old_box['box_id']]['title'])) echo $box_list_data['old'][$old_box['box_id']]['title']; ?>">
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <ul class="input-info">
                    <li>
                        <label class="headline">預け入れ・撮影についてのよくあるご質問</label>
                        <ul class="frequently">
                            <li>預け入れまでの流れについては<a href="<?php echo Configure::read('site.static_content_url'); ?>/help/packing.html" target="_blank">専用ボックスの到着から預け入れまで</a></li>
                            <li>minikuraMONOの撮影については<a href="<?php echo Configure::read('site.static_content_url'); ?>/lineup/mono.html" target="_blank">撮影付き保管サービスの概要</a></li>
                            <li>注意事項については<a href="<?php echo Configure::read('site.static_content_url'); ?>/use_agreement/#attachment1" target="_blank">お取り出し・配送について</a></li>
                        </ul>
                    </li>
                    <li>
                        <label class="headline">ボックスの配送方法</label>
                        <?php echo $this->Form->error("InboundBase.delivery_carrier", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        <ul class="delivery-method">
                            <li>
                                <label class="input-check">
                                    <input type="radio" class="rb" name="data[InboundBase][delivery_carrier]" value="6_1" <?php echo (isset($this->request->data['InboundBase']['delivery_carrier']) && $this->request->data['InboundBase']['delivery_carrier'] == '6_1' || (isset($this->request->data['InboundBase']['delivery_carrier']) === false)) ? 'checked' : ''; ?>><span class="icon"></span>
                                    <span class="label-txt">集荷を申し込む</span>
                                </label>
                            </li>
                            <li id="dev-self-delivery"><label class="input-check">
                                    <input type="radio" class="rb" name="data[InboundBase][delivery_carrier]" value="7" <?php echo (isset($this->request->data['InboundBase']['delivery_carrier']) && $this->request->data['InboundBase']['delivery_carrier'] == '7') ? 'checked' : ''; ?>><span class="icon"></span>
                                    <span class="label-txt">自分で発送する</span>
                                </label>
                            </li>
                        </ul>
                    </li>
                    <div id="dev-input-box-type-new">
                        <li>
                            <label class="headline">お預かりに上がる住所<span class="note">配送業者が荷物を受け取りに伺います。</span></label>
                                <div id="dsn-amazon-pay" class="form-group col-lg-12">
                                  <div class="dsn-address">
                                    <div id="addressBookWidgetDiv">
                                    </div>
                                  </div>
                                </div>
                            <?php echo $this->Form->error("InboundBase.address_id", null, ['wrap' => 'p']) ?>
                        </li>
                        <input type="hidden" id="firstname" name="data[InboundBase][firstname]">
                        <input type="hidden" id="lastname"  name="data[InboundBase][lastname]">
                        <li>
                            <input type="hidden" value="<?php echo isset($this->request->data['InboundBase']['day_cd']) ?$this->request->data['InboundBase']['day_cd'] : ""; ?>" id="pickup_date">
                            <label class="headline">集荷の日程</label>
                            <select id="day_cd" name="data[InboundBase][day_cd]"></select>
                            <?php echo $this->Form->error("InboundBase.day_cd", null, ['wrap' => 'p']) ?>
                        </li>
                        <li>
                        <input type="hidden" value="<?php echo isset($this->request->data['InboundBase']['time_cd']) ?$this->request->data['InboundBase']['time_cd'] : ""; ?>" id="pickup_time_code">
                            <label class="headline">集荷の時間</label>
                            <select id="time_cd" name="data[InboundBase][time_cd]"></select>
                            <?php echo $this->Form->error("InboundBase.time_cd", null, ['wrap' => 'p']) ?>
                        </li>
                    </div>
                </ul>
            </form>
        </div>
        <div class="nav-fixed">
            <ul>
                <li><button id="execute" class="btn-red">ボックスの確認</button>
                </li>
            </ul>
        </div>
        <div class="remodal about-id" data-remodal-id="about-id">
            <p class="page-caption">バーコード番号(ボックスID)はボックス側面にバーコードと共に記載されています。</p>
            <img src="/images/about-id@2x.png" alt="">
            <a class="btn-close" data-remodal-action="close">閉じる</a>
        </div>
        <div class="remodal l-packaging" data-remodal-id="packaging">
            <h2 class="title-packaging"><img src="/images/title-packaging.svg" alt="minikuraMONOご利用の方"></h2>
            <p class="text-packaging"><span>「外装を除いて撮影」</span>にチェックを入れると、複数点まとめた梱包や、アイテム保護のための緩衝材梱包等を取り外し撮影できます。</p>
            <picture>
                <source srcset="/images/packaging@1x.png 1x, /images/packaging@2x.png 2x">
                <img src="/images/packaging@1x.png" alt="開封における例外ケース">
            </picture>
            <h3 class="title-note">開封における例外ケース</h3>
            <ul class="l-note">
                <li><span>写真付の商品パッケージ・透明なビニール(OPP袋)など</span>の商品箱から中身がわかるものはそのまま撮影に移ります。</li>
                <li>商品箱から中身がわからないものは、箱を含め、中身一式全てを撮影します。ただし、<span>丸められた状態のポスター類、ラッピングされた箱など、</span>撮影後の原状回復ができないものは開封せず撮影します。</li>
            </ul>
            <p class="note-packaging">※再撮影はお断りしております。</p>
            <a class="btn-close" data-remodal-action="close">閉じる</a>
        </div>
