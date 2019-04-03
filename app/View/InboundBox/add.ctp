<?php
$this->Html->script('https://maps.google.com/maps/api/js?key=' . Configure::read('app.googlemap.api.key') . '&libraries=places', ['block' => 'scriptMinikura']); 
$this->Html->script('jquery.easing', ['block' => 'scriptMinikura']);
$this->Html->script('minikura/address', ['block' => 'scriptMinikura']);
$this->Html->script('jquery.airAutoKana.js', ['block' => 'scriptMinikura']);
$this->Html->script('inbound_box/add', ['block' => 'scriptMinikura']);
$this->Html->script('pickupYamato', ['block' => 'scriptMinikura']);
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
            <form name="form" action='/inbound/box/confirm' method="POST">
                <ul class="setting-switcher">
                    <li>
                        <label class="setting-switch">
                            <input type="radio" class="ss" name="data[Inbound][box_type]" value="new" checked>
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
                        <ul class="delivery-method">
                            <li>
                                <label class="input-check">
                                    <input type="radio" class="rb" name="data[Inbound][delivery_carrier]" value="6_1" checked><span class="icon"></span>
                                    <span class="label-txt">集荷を申し込む</span>
                                </label>
                            </li>
                            <li id="dev-self-delivery"><label class="input-check">
                                    <input type="radio" class="rb" name="data[Inbound][delivery_carrier]" value="7"><span class="icon"></span>
                                    <span class="label-txt">自分で発送する</span>
                                </label>
                            </li>
                        </ul>
                    </li>
                    <div id="dev-input-box-type-new">
                        <li>
                            <label class="headline">お預かりに上がる住所<span class="note">配送業者が荷物を受け取りに伺います。</span></label>
                            <select class="address" name="data[Inbound][address_id]">
                                <?php foreach ($addressList as $data) : ?>
                                  <option value="<?php echo $data['address_id']; ?>">
                                    <?php echo h("〒${data['postal']} ${data['pref']}${data['address1']}${data['address2']}${data['address3']}　${data['lastname']}${data['firstname']}"); ?>
                                  </option>
                                <?php endforeach; ?>;
                                <option value="add" <?php echo (isset($_POST['data']['Inbound']['address_id']) && $_POST['data']['Inbound']['address_id'] == 'add') ? 'selected' : ''; ?>>お届先を追加する</option>
                            </select>
                        </li>
                        <li class="input-address">
                            <ul class="add-address">
                                <li>
                                    <label>郵便番号</label>
                                    <input id="postal" name="data[CustomerAddress][postal]" type="tel" placeholder="例：140-0002" class='search_address_postal' value="<?php echo isset($this->request->data['CustomerAddress']['postal']) ? $this->request->data['CustomerAddress']['postal'] : ''; ?>">
                                    <p class="txt-caption">入力すると住所が自動で反映されます。</p>
                                </li>
                                <li>
                                    <label>都道府県</label>
                                    <input name="data[CustomerAddress][pref]" type="text" placeholder="例：東京都" class='address_pref' value="<?php echo isset($this->request->data['CustomerAddress']['pref']) ? $this->request->data['CustomerAddress']['pref'] : ''; ?>">
                                </li>
                                <li>
                                    <label>住所</label>
                                    <input name="data[CustomerAddress][address1]" type="text" placeholder="例：品川区東品川2" class='address_address1' value="<?php echo isset($this->request->data['CustomerAddress']['address1']) ? $this->request->data['CustomerAddress']['address1'] : ''; ?>">
                                </li>
                                <li>
                                    <label>番地</label>
                                    <input name="data[CustomerAddress][address2]" type="text" placeholder="例：6-10" class='address_address2' value="<?php echo isset($this->request->data['CustomerAddress']['address2']) ? $this->request->data['CustomerAddress']['address2'] : ''; ?>">
                                </li>
                                <li>
                                    <label>建物名</label>
                                    <input name="data[CustomerAddress][address3]" type="text" placeholder="例：Tビル" class='address_address3' value="<?php echo isset($this->request->data['CustomerAddress']['address3']) ? $this->request->data['CustomerAddress']['address3'] : ''; ?>">
                                </li>
                                <li>
                                    <label>電話番号</label>
                                    <input name="data[CustomerAddress][tel1]" type="tel" placeholder="例：0312345678" value="<?php echo isset($this->request->data['CustomerAddress']['tel1']) ? $this->request->data['CustomerAddress']['tel1'] : ''; ?>">
                                </li>
                                <li>
                                    <label>お名前 姓</label>
                                    <input name="data[CustomerAddress][lastname]" class="lastname" type="text" placeholder="例：寺田" value="<?php echo isset($this->request->data['CustomerAddress']['lastname']) ? $this->request->data['CustomerAddress']['lastname'] : ''; ?>">
                                </li>
                                <li>
                                    <label>お名前 名</label>
                                    <input name="data[CustomerAddress][firstname]" class="firstname" type="text" placeholder="例：太郎" value="<?php echo isset($this->request->data['CustomerAddress']['firstname']) ? $this->request->data['CustomerAddress']['firstname'] : ''; ?>">
                                </li>
                            </ul>
                            <label class="input-check">
                                <input type="checkbox" class="cb-square" name="data[CustomerAddress][register_address_book]" value="1"<?php echo isset($this->request->data['CustomerAddress']['register_address_book']) ? 'checked' : ''; ?>><span class="icon"></span><span class="label-txt">アドレスブックに登録する</span>
                            </label>
                        </li>
                        <li>
                            <input type="hidden" value="" id="pickup_date">
                            <label class="headline">集荷の日程</label>
                            <select id="day_cd" name="data[Inbound][day_cd]"></select>
                        </li>
                        <li>
                            <input type="hidden" value="" id="pickup_time_code">
                            <label class="headline">集荷の時間</label>
                            <select id="time_cd" name="data[Inbound][time_cd]"></select>
                        </li>
                    </div>
                    <!--li>
                        <ul class="frequently">
                            <li><a href="#">預け入れまでの流れはこちら</a></li>
                            <li><a href="#">minikuraMONOの撮影についてはこちら</a></li>
                            <li><a href="#">注意事項についてはこちら</a></li>
                        </ul>
                    </li-->
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
