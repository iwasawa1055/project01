<?php
$this->Html->script('https://maps.google.com/maps/api/js?key=' . Configure::read('app.googlemap.api.key') . '&libraries=places', ['block' => 'scriptMinikura']);
$this->Html->script('jquery.easing', ['block' => 'scriptMinikura']);
$this->Html->script('minikura/address', ['block' => 'scriptMinikura']);
$this->Html->script('jquery.airAutoKana.js', ['block' => 'scriptMinikura']);
$this->Html->script('inbound_box/add.js?'.time(), ['block' => 'scriptMinikura']);
$this->Html->script('pickupYamato', ['block' => 'scriptMinikura']);
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
        $selectedList[$k] = $v;
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
            <ul class="l-banner">
              <li class="l-banner-dtl">
                <a href="/news/detail/417">
                  <picture>
                    <source media="(min-width: 768px)" srcset="/images/price-revision-pc@1x.png 1x, /images/price-revision-pc@2x.png 2x">
                    <source media="(min-width: 1px)" srcset="/images/price-revision-sp@1x.png 1x, /images/price-revision-sp@2x.png 2x">
                    <img src="/images/price-revision-pc@1x.png" alt="2019年10月1日よりご利用料金が変更になります 詳しくはこちら">
                  </picture>
                </a>
              </li>
            </ul>
            <ul class="pagenation">
                <li class="on"><span class="number">1</span><span class="txt">ボックス<br>選択</span>
                </li>
                <li><span class="number">2</span><span class="txt">確認</span>
                </li>
                <li><span class="number">3</span><span class="txt">完了</span>
                </li>
            </ul>
            <form name="form" action='/inbound/box/confirm' method="POST">
                <?php echo $this->Form->error("Inbound.box", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                <ul class="setting-switcher">
                    <li>
                        <label class="setting-switch">
                            <input type="radio" class="ss" name="data[Inbound][box_type]" value="new">
                            <span class="btn-ss"><span class="icon"></span>新しい<br class="sp">ボックスを預ける</span>
                        </label>
                    </li>
                    <li>
                        <label class="setting-switch">
                            <input type="radio" class="ss" name="data[Inbound][box_type]" value="old">
                            <span class="btn-ss"><span class="icon"></span>取り出し済ボックスを<br class="sp">再度預ける</span>
                        </label>
                    </li>
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
                        <label class="headline">預け入れ・撮影についてのよくあるご質問</label>
                        <ul class="frequently">
                            <li>預け入れまでの流れについては<a href="<?php echo Configure::read('site.static_content_url'); ?>/help/packing.html" target="_blank">専用ボックスの到着から預け入れまで</a></li>
                            <li>minikuraMONOの撮影については<a href="https://help.minikura.com/hc/ja/articles/221053727" target="_blank">撮影付き保管サービスの概要</a></li>
                            <li>注意事項については<a href="https://help.minikura.com/hc/ja/articles/216414387" target="_blank">お取り出し・配送について</a></li>
                        </ul>
                    </li>
                    <li>
                        <label class="headline">ボックスの配送方法</label>
                        <?php echo $this->Form->error("Inbound.delivery_carrier", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        <ul class="delivery-method">
                            <li>
                                <label class="input-check">
                                    <input type="radio" class="rb" name="data[Inbound][delivery_carrier]" value="6_1" <?php echo ((isset($this->request->data['Inbound']['delivery_carrier']) && $this->request->data['Inbound']['delivery_carrier'] == '6_1') || (isset($this->request->data['Inbound']['delivery_carrier']) === false)) ? 'checked' : ''; ?>><span class="icon"></span>
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
                            <select class="address" name="data[Inbound][address_id]">
                                <?php foreach ($addressList as $data) : ?>

                                <option value="<?php echo $data['address_id']; ?>" <?php echo (isset($this->request->data['Inbound']['address_id']) && $this->request->data['Inbound']['address_id'] == $data['address_id']) ? 'selected' : ''; ?> data-address-name="<?php echo h("${data['lastname']}${data['firstname']}"); ?>">
                                    <?php echo h("〒${data['postal']} ${data['pref']}${data['address1']}${data['address2']}${data['address3']}　${data['lastname']}${data['firstname']}"); ?>
                                  </option>
                                <?php endforeach; ?>;
                                <option value="add" <?php echo (isset($this->request->data['Inbound']['address_id']) && $this->request->data['Inbound']['address_id'] == 'add') ? 'selected' : ''; ?> data-address-name="">お届先を追加する</option>
                            </select>
                            <?php echo $this->Form->error("Inbound.address_id", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                        <li class="input-address">
                            <ul class="add-address">
                                <li>
                                    <label>郵便番号</label>
                                    <input id="postal" name="data[CustomerAddress][postal]" type="tel" placeholder="例：140-0002" class='search_address_postal' value="<?php echo isset($this->request->data['CustomerAddress']['postal']) ? $this->request->data['CustomerAddress']['postal'] : ''; ?>">
                                    <?php echo $this->Form->error("CustomerAddress.postal", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                                    <p class="txt-caption">入力すると住所が自動で反映されます。</p>
                                </li>
                                <li>
                                    <label>都道府県</label>
                                    <input name="data[CustomerAddress][pref]" type="text" placeholder="例：東京都" class='address_pref' value="<?php echo isset($this->request->data['CustomerAddress']['pref']) ? $this->request->data['CustomerAddress']['pref'] : ''; ?>">
                                    <?php echo $this->Form->error("CustomerAddress.pref", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                                </li>
                                <li>
                                    <label>市区郡</label>
                                    <input name="data[CustomerAddress][address1]" type="text" placeholder="例：品川区" class='address_address1' value="<?php echo isset($this->request->data['CustomerAddress']['address1']) ? $this->request->data['CustomerAddress']['address1'] : ''; ?>">
                                    <?php echo $this->Form->error("CustomerAddress.address1", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                                </li>
                                <li>
                                    <label>町域以降</label>
                                    <input name="data[CustomerAddress][address2]" type="text" placeholder="例：東品川2-6-10" class='address_address2' value="<?php echo isset($this->request->data['CustomerAddress']['address2']) ? $this->request->data['CustomerAddress']['address2'] : ''; ?>">
                                    <?php echo $this->Form->error("CustomerAddress.address2", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                                </li>
                                <li>
                                    <label>建物名</label>
                                    <input name="data[CustomerAddress][address3]" type="text" placeholder="例：Tビル" class='address_address3' value="<?php echo isset($this->request->data['CustomerAddress']['address3']) ? $this->request->data['CustomerAddress']['address3'] : ''; ?>">
                                    <?php echo $this->Form->error("CustomerAddress.address3", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                                </li>
                                <li>
                                    <label>電話番号</label>
                                    <input name="data[CustomerAddress][tel1]" type="tel" placeholder="例：0312345678" value="<?php echo isset($this->request->data['CustomerAddress']['tel1']) ? $this->request->data['CustomerAddress']['tel1'] : ''; ?>">
                                    <?php echo $this->Form->error("CustomerAddress.tel1", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                                </li>
                                <li>
                                    <label>お名前 姓</label>
                                    <input name="data[CustomerAddress][lastname]" class="lastname" type="text" placeholder="例：寺田" value="<?php echo isset($this->request->data['CustomerAddress']['lastname']) ? $this->request->data['CustomerAddress']['lastname'] : ''; ?>">
                                    <?php echo $this->Form->error("CustomerAddress.lastname", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                                </li>
                                <li>
                                    <label>お名前 名</label>
                                    <input name="data[CustomerAddress][firstname]" class="firstname" type="text" placeholder="例：太郎" value="<?php echo isset($this->request->data['CustomerAddress']['firstname']) ? $this->request->data['CustomerAddress']['firstname'] : ''; ?>">
                                    <?php echo $this->Form->error("CustomerAddress.firstname", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                                </li>
                                <li>
                                    <label>お名前 姓 カナ</label>
                                    <input name="data[CustomerAddress][lastname_kana]" class="lastname_kana" type="text" placeholder="例：テラダ" value="<?php echo isset($this->request->data['CustomerAddress']['lastname']) ? $this->request->data['CustomerAddress']['lastname_kana'] : ''; ?>">
                                    <?php echo $this->Form->error("CustomerAddress.lastname_kana", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                                </li>
                                <li>
                                    <label>お名前 名 カナ</label>
                                    <input name="data[CustomerAddress][firstname_kana]" class="firstname_kana" type="text" placeholder="例：タロウ" value="<?php echo isset($this->request->data['CustomerAddress']['firstname']) ? $this->request->data['CustomerAddress']['firstname_kana'] : ''; ?>">
                                    <?php echo $this->Form->error("CustomerAddress.firstname_kana", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                                </li>
                            </ul>
                            <label class="input-check">
                                <input type="checkbox" class="cb-square" name="data[CustomerAddress][resister]" value="1" <?php echo (isset($this->request->data['CustomerAddress']['resister']) && $this->request->data['CustomerAddress']['resister'] == '1') ? 'checked' : ''; ?>><span class="icon"></span><span class="label-txt">アドレスブックに登録する</span>
                            </label>
                        </li>
                        <li>
                            <input type="hidden" value="<?php echo isset($this->request->data['Inbound']['day_cd']) ?$this->request->data['Inbound']['day_cd'] : ""; ?>" id="pickup_date">
                            <label class="headline">集荷の日程</label>
                            <select id="day_cd" name="data[Inbound][day_cd]"></select>
                            <?php echo $this->Form->error("Inbound.day_cd", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                        <li>
                        <input type="hidden" value="<?php echo isset($this->request->data['Inbound']['time_cd']) ?$this->request->data['Inbound']['time_cd'] : ""; ?>" id="pickup_time_code">
                            <label class="headline">集荷の時間</label>
                            <select id="time_cd" name="data[Inbound][time_cd]"></select>
                            <?php echo $this->Form->error("Inbound.time_cd", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
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
