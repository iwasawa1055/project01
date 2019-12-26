<?php
$this->Html->script('https://maps.google.com/maps/api/js?key=' . Configure::read('app.googlemap.api.key') . '&libraries=places', ['block' => 'scriptMinikura']);
$this->Html->script('jquery.easing', ['block' => 'scriptMinikura']);
$this->Html->script('minikura/address', ['block' => 'scriptMinikura']);
$this->Html->script('inbound_box/attention', ['block' => 'scriptMinikura']);
$this->Html->script('pickupYamato', ['block' => 'scriptMinikura']);
?>

        <?php if (isset($validErrors['BoxList'])) { $this->validationErrors['BoxList'] = $validErrors['BoxList']; } ?>
        <?php if (isset($validErrors['InboundBase'])) { $this->validationErrors['InboundBase'] = $validErrors['InboundBase']; } ?>

        <?php echo $this->Form->create('', ['url' => '/inbound/box/attention', 'name' => 'form', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
        <div id="page-wrapper" class="wrapper inbound">
            <?php $error_item_excess = $this->Flash->render('item_excess');?>
            <?php if (!empty($error_item_excess)) :?>
            <br>
            <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i><?php echo $error_item_excess;?></div>
            <?php endif;?>
            <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> ボックス預け入れ</h1>
            <ul class="pagenation">
                <li><span class="number">1</span><span class="txt">ボックス<br>選択</span>
                </li>
                <li class="on"><span class="number">2</span><span class="txt">ボックス<br>情報入力</span>
                </li>
                <li><span class="number">3</span><span class="txt">確認</span>
                </li>
                <li><span class="number">4</span><span class="txt">完了</span>
                </li>
            </ul>

            <div class="item-content">
                <ul class="l-caution">
                  <?php if($box_use_flag[PRODUCT_CD_MONO] || $box_use_flag[PRODUCT_CD_CLOSET] || $box_use_flag[PRODUCT_CD_LIBRARY] || $box_use_flag[PRODUCT_CD_CLEANING_PACK]) :?>
                  <li class="wrapping_modal">
                    <a href="javascript:void(0)" data-remodal-target="packaging" class="about-box-id title-caution">
                      <img src="/images/question.svg">「外装を除いて撮影」について
                    </a>
                  </li>
                  <?php endif; ?>
                </ul>
                <ul class="grid grid-md">
                    <?php foreach ($target_box_list as $box_data): ?>
                    <li>
                        <label>
                            <span class="item-img">
                              <?php if (!empty($box_data['kit_cd']) && in_array($box_data['kit_cd'], array_keys(KIT_IMAGE))) : ?>
                              <img src="<?php echo KIT_IMAGE[$box_data['kit_cd']]; ?>" alt="<?php echo KIT_NAME[$box_data['kit_cd']]; ?>" class="img-item">
                              <?php elseif (!empty($box_data['product_cd']) && in_array($box_data['product_cd'], array_keys(PRODUCT_IMAGE))) : ?>
                              <img src="<?php echo PRODUCT_IMAGE[$box_data['product_cd']]; ?>" alt="<?php echo PRODUCT_NAME[$box_data['product_cd']]; ?>" class="img-item">
                              <?php else : ?>
                              <img src="/images/box-other.png" alt="その他の画像" class="img-item">
                              <?php endif; ?>
                            </span>
                        </label>
                        <div class="box-info">
                            <p class="l-box-id">
                                <span class="txt-box-id"><?php echo $box_data['box_id']; ?></span>
                            </p>
                            <p class="box-type"><?php echo $box_data['kit_name']; ?></p>
                            <?php echo $this->Form->input("BoxList.{$inbound_base_data['box_type']}.{$box_data['box_id']}.checkbox", ['type' => 'hidden', 'value' => '1']); ?>
                            <?php echo $this->Form->input("BoxList.{$inbound_base_data['box_type']}.{$box_data['box_id']}.title", ['type' => 'text', 'placeholder' => "ボックス名を記入してください", 'class' => "dev-mbs5", 'error' => false, 'label' => false, 'div' => false]); ?>
                            <?php echo $this->Form->error("BoxList.{$inbound_base_data['box_type']}.{$box_data['box_id']}.title", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </div>
                        <?php if(in_array($box_data['product_cd'], EXTERIOR_REMOVAL_PRODUCT_CD, true)): ?>
                          <?php echo $this->Form->input("BoxList.{$inbound_base_data['box_type']}.{$box_data['box_id']}.wrapping_type", ['type' => 'hidden', 'value' => '0']); ?>
                          <label class="input-check">
                              <?php
                              echo $this->Form->input(
                                  "BoxList.{$inbound_base_data['box_type']}.{$box_data['box_id']}.wrapping_type",
                                  [
                                      'class'       => 'cb-square',
                                      'label'       => false,
                                      'error'       => false,
                                      'type'        => 'checkbox',
                                      'div'         => false,
                                      'hiddenField' => false,
                                  ]
                              );
                              ?>
                            <span class="icon"></span>
                            <span class="label-txt">外装を除いて撮影</span>
                          </label>
                        <?php endif;?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <ul class="input-info">
              <li>
                <label class="headline">ボックスの配送方法</label>
                  <?php echo $this->Form->error("InboundBase.delivery_carrier", null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                <ul class="delivery-method">
                  <li>
                    <label class="input-check">
                        <?php
                        echo $this->Form->input(
                            "InboundBase.delivery_carrier",
                            [
                                'id'    => '',
                                'class' => 'rb',
                                'label' => false,
                                'error' => false,
                                'options' => [
                                    '6_1' => '<span class="icon"></span><span class="label-txt">集荷を申し込む</span>'
                                ],
                                'type' => 'radio',
                                'div' => false,
                                'hiddenField' => false,
                                'checked' => 'checked'
                            ]
                        );
                        ?>
                    </label>
                  </li>
                  <li id="dev-self-delivery">
                    <label class="input-check" <?php echo (!isset($this->request->data['InboundBase']['box_type']) || $this->request->data['InboundBase']['box_type'] == 'new') ? '' : 'style="display:none"'; ?>>
                        <?php
                        echo $this->Form->input(
                            "InboundBase.delivery_carrier",
                            [
                                'id'    => '',
                                'class' => 'rb',
                                'label' => false,
                                'error' => false,
                                'options' => [
                                    '7' => '<span class="icon"></span><span class="label-txt">自分で発送する</span>'
                                ],
                                'type' => 'radio',
                                'div' => false,
                                'hiddenField' => false,
                            ]
                        );
                        ?>
                    </label>
                  </li>
                </ul>
              </li>
              <li>
                <ul class="input-info" id="dev_collect" <?php echo (isset($this->request->data['InboundBase']['delivery_carrier']) && $this->request->data['InboundBase']['delivery_carrier'] == '7') ? 'style="display:none"' : ''; ?>>
                  <li>
                    <label class="headline">お届けに上がる住所<span class="note">配送業者がお届けに伺います。</span></label>
                      <?php echo $this->Form->select('InboundBase.address_id', $address_list, ['id' => 'address_id', 'class' => 'address', 'empty' => false, 'label' => false, 'error' => false, 'div' => false]); ?>
                      <?php echo $this->Form->error('InboundBase.address_id', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                  </li>
                  <li class="inbound-input-address">
                      <?php echo $this->element('add-address', ['model' => 'InboundBase']); ?>
                  </li>
                  <li class="select_other">
                    <label class="headline">お預かりに上がる日時</label>
                      <?php echo $this->Form->select('InboundBase.datetime_cd', [], ['id' => 'datetime_cd', 'empty' => false, 'label' => false, 'error' => false, 'div' => false]); ?>
                      <?php echo $this->Form->error('InboundBase.datetime_cd', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                      <input type='hidden' id='select_datetime_cd' value="<?php echo isset($inbound_base_data['datetime_cd']) ? $inbound_base_data['datetime_cd'] : ""; ?>">
                  </li>
                </ul>
              </li>
              <li>
                <ul class="input-info" id="dev_self" <?php echo (isset($this->request->data['InboundBase']['delivery_carrier']) && $this->request->data['InboundBase']['delivery_carrier'] == '7') ? '' : 'style="display:none"'; ?>>
                  <li class="l-mtd-desc">
                    <p>自分で発送する際は、以下の2つから送ることができます。</p>
                  </li>
                  <li class="l-mtd-dtl">
                    <label class="headline">コンビニエンスストアで発送</label>
                    <p class="txt-desc">専用ボックスに同梱されている宅配伝票を専用ボックスに貼り、お近くのコンビニエンスストアからお荷物をお送りください。<br>
                      取扱店：セブン-イレブン、ファミリーマート、サークルKサンクス、デイリーヤマザキ、スリーエフ、ポプラ、ニューデイズなど</p>
                    <label class="headline">自分でヤマト運輸へ集荷の依頼</label>
                    <p class="txt-desc">専用ボックスに同梱されている宅配伝票を専用ボックスに貼り、ヤマト運輸のインターネットまたはお電話で集荷の依頼をすることでお荷物を送ることができます。<br>
                      直接の申し込みは<a class="link" href="http://www.kuronekoyamato.co.jp/ytc/customer/send/" target="_blank">こちら</a></p>
                  </li>
                </ul>
              </li>
            </ul>
            <?php if($box_use_flag[PRODUCT_CD_CLEANING_PACK] || $box_use_flag[PRODUCT_CD_CLOSET]) :?>
            <ul class="input-info">
                <li class="caution-box">
                    <p class="title">注意事項（ご確認の上、チェックしてください）</p>
                    <div class="content">
                        <div class="l-desc-num-input">
                          <p class="txt-desc-num-input">下記表に記載のサービスは預け入れの上限点数まで無料でお預かりができ、それを超えると以下の料金でご利用いただけます。（毎月の保管料は変わりません）</p>
                          <table class="tbl-desc-num-input">
                            <tbody>
                            <tr>
                              <th class="th">minikura Closet</th>
                              <td class="td">上限<?php echo EXCESS_ATTENTION_PRODUCT_CD[PRODUCT_CD_CLOSET]; ?>点。<br class="sp">上限超過時＋91円(税抜)/1アイテム</td>
                            </tr>
                            <tr>
                              <th class="th">minikura<br class="sp">クリーニングパック</th>
                              <td class="td">上限<?php echo EXCESS_ATTENTION_PRODUCT_CD[PRODUCT_CD_CLEANING_PACK]; ?>点。<br class="sp">上限超過時＋850円(税抜)/1アイテム</td>
                            </tr>
                            </tbody>
                          </table>
                        </div>
                        <label class="input-check">
                            <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">倉庫でお預かり品を計測した際、点数に齟齬があった場合は、点数と料金表に基づいて実際の料金をご連絡させていただきます。</span>
                        </label>
                    </div>
                </li>
            </ul>
            <?php endif; ?>
        </div>
        <div class="nav-fixed">
            <ul>
                <li>
                  <a class="btn-d-gray" href="/inbound/box/add">戻る</a>
                </li>
                <li><a class="btn-red" id="execute">ボックスの確認</a>
                </li>
            </ul>
        </div>
        <?php echo $this->Form->end(); ?>

        <!-- popup -->
        <div class="remodal l-packaging" data-remodal-id="packaging" data-remodal-options="hashTracking:false">
          <h2 class="title-packaging">minikuraMONO、<br class="sp">minikuraLibraryの<br>「外装を除いて撮影」について</h2>
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
