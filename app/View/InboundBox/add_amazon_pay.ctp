<?php
$this->Html->script('https://maps.google.com/maps/api/js?key=' . Configure::read('app.googlemap.api.key') . '&libraries=places', ['block' => 'scriptMinikura']); 
$this->Html->script('jquery.easing', ['block' => 'scriptMinikura']);
$this->Html->script('minikura/address', ['block' => 'scriptMinikura']);
$this->Html->script('jquery.airAutoKana.js', ['block' => 'scriptMinikura']);
$this->Html->script('inbound_box/add_amazon_pay.js?'.time(), ['block' => 'scriptMinikura']);
$this->Html->script('pickupYamato', ['block' => 'scriptMinikura']);
$this->Html->css('/css/dsn-amazon-pay.css', ['block' => 'css']);
$this->Html->css('/css/add_amazon_pay_dev.css', ['block' => 'css']);
?>
<?php
if (!empty($validErrors)) {
    // Form->errorで使用できるようにする
    $this->Form->validationErrors = $validErrors;
    // 一覧表示のアイテム用にjsonでエラーを格納
    if (isset($validErrors["box_list"])) {
        echo "<input type='hidden' id='dev-box-list-errors' value='".json_encode($validErrors["box_list"])."'>";
    }
}
// 選択したボックスの一覧
if (isset($this->request->data['Inbound']['box_list'])) {
    $selectedList = [];
    foreach ($this->request->data['Inbound']['box_list'] as $k => $v) {
        if ($v['checkbox'] == "1") {
            $selectedList[$k] = $v;
        }
    }
    echo "<input type='hidden' id='dev-box-list-selected' value='".json_encode($selectedList)."'>";
}
// 選択したボックスタイプ
if (isset($this->request->data['Inbound']['box_type'])) {
    echo "<input type='hidden' id='dev-selected-box_type' value='".$this->request->data['Inbound']['box_type']."'>";
}
?>
        <div id="page-wrapper" class="wrapper inbound">
            <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> ボックス預け入れ</h1>
            <ul class="pagenation">
                <li class="on"><span class="number">1</span><span class="txt">ボックス<br>選択</span>
                </li>
                <li><span class="number">2</span><span class="txt">確認</span>
                </li>
                <li><span class="number">3</span><span class="txt">完了</span>
                </li>
            </ul>
            <form name="form" action='/inbound/box/confirm_amazon_pay' method="POST">
                <?php echo $this->Form->error("Inbound.box", null, ['wrap' => 'p']) ?>
                <ul class="setting-switcher">
                    <li>
                        <label class="setting-switch">
                            <input type="radio" class="ss" name="data[Inbound][box_type]" value="new">
                            <span class="btn-ss"><span class="icon"></span>新規購入ボックス</span>
                        </label>
                    </li>
                    <li>
                        <label class="setting-switch">
                            <input type="radio" class="ss" name="data[Inbound][box_type]" value="old">
                            <span class="btn-ss"><span class="icon"></span>取出し済ボックス</span>
                        </label>
                    </li>
                </ul>
                <div id="dev-new-box" class="item-content">
                    <a href="#" data-remodal-target="about-id" class="about-box-id"><img src="/images/question.svg">ボックスIDについて</a>
                    <ul id="dev-new-box-grid" class="grid grid-md">
                    </ul>
                </div>
                <div id="dev-old-box" class="item-content">
                    <p class="page-caption">minikuraHAKOのみ、再入庫を受け付けておりますが、ボックスの強度をご確認の上、ご利用ください。</p>
                    <a href="#" data-remodal-target="about-id" class="about-box-id"><img src="/images/question.svg">ボックスIDについて</a>
                    <!--ul class="id-search">
                        <li>
                            <input type="search" placeholder="ボックスのバーコード下4桁を入力" class="search">
                        </li>
                        <li>
                            <a href="#" data-remodal-target="about-id" class="about-box-id"><img src="/images/question.svg"></a>
                        </li>
                    </ul-->
                    <ul id="dev-old-box-grid" class="grid grid-md">
                    </ul>
                </div>
                <ul class="input-info">
                    <li>
                        <label class="headline">ボックスの配送方法</label>
                        <?php echo $this->Form->error("Inbound.delivery_carrier", null, ['wrap' => 'p']) ?>
                        <ul class="delivery-method">
                            <li>
                                <label class="input-check">
                                    <input type="radio" class="rb" name="data[Inbound][delivery_carrier]" value="6_1" <?php echo (isset($this->request->data['Inbound']['delivery_carrier']) && $this->request->data['Inbound']['delivery_carrier'] == '6_1' || (isset($this->request->data['Inbound']['delivery_carrier']) === false)) ? 'checked' : ''; ?>><span class="icon"></span>
                                    <span class="label-txt">集荷を申し込む</span>
                                </label>
                            </li>
                            <li id="dev-self-delivery"><label class="input-check">
                                    <input type="radio" class="rb" name="data[Inbound][delivery_carrier]" value="7" <?php echo (isset($this->request->data['Inbound']['delivery_carrier']) && $this->request->data['Inbound']['delivery_carrier'] == '7') ? 'checked' : ''; ?>><span class="icon"></span>
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
                            <?php echo $this->Form->error("Inbound.address_id", null, ['wrap' => 'p']) ?>
                        </li>
                        <li class="name-form-group">
                            <label class="headline">お名前 (姓)</label>
                            <input type="text" class="lastname" name="lastname" placeholder="寺田" size="10" maxlength="30">
                            <?php echo $this->Form->error("Inbound.lastname", null, ['wrap' => 'p']) ?>
                        </li>
                        <li class="name-form-group">
                            <label class="headline">お名前 (名)</label>
                            <input type="text" class="firstname" name="firstname" placeholder="太郎" size="10" maxlength="30">
                            <?php echo $this->Form->error("Inbound.firstname", null, ['wrap' => 'p']) ?>
                        </li>
                        <li>
                            <input type="hidden" value="<?php echo isset($this->request->data['Inbound']['day_cd']) ?$this->request->data['Inbound']['day_cd'] : ""; ?>" id="pickup_date">
                            <label class="headline">集荷の日程</label>
                            <select id="day_cd" name="data[Inbound][day_cd]"></select>
                            <?php echo $this->Form->error("Inbound.day_cd", null, ['wrap' => 'p']) ?>
                        </li>
                        <li>
                        <input type="hidden" value="<?php echo isset($this->request->data['Inbound']['time_cd']) ?$this->request->data['Inbound']['time_cd'] : ""; ?>" id="pickup_time_code">
                            <label class="headline">集荷の時間</label>
                            <select id="time_cd" name="data[Inbound][time_cd]"></select>
                            <?php echo $this->Form->error("Inbound.time_cd", null, ['wrap' => 'p']) ?>
                        </li>
                        <li>
                            <ul class="frequently">
                                <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/help/packing.html" target="_blank">預け入れまでの流れはこちら</a></li>
                                <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/lineup/mono.html" target="_blank">minikuraMONOの撮影についてはこちら</a></li>
                                <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/use_agreement/#attachment1" target="_blank">注意事項についてはこちら</a></li>
                            </ul>
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
