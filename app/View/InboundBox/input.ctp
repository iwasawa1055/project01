<?php
$this->Html->script('https://maps.google.com/maps/api/js?key=' . Configure::read('app.googlemap.api.key') . '&libraries=places', ['block' => 'scriptMinikura']);
$this->Html->script('jquery.easing', ['block' => 'scriptMinikura']);
$this->Html->script('minikura/address', ['block' => 'scriptMinikura']);
$this->Html->script('inbound_box/add.js?'.time(), ['block' => 'scriptMinikura']);
$this->Html->script('pickupYamato', ['block' => 'scriptMinikura']);
?>
        <?php if (isset($validErrors['BoxList'])) { $this->validationErrors['BoxList'] = $validErrors['BoxList']; } ?>
        <?php if (isset($validErrors['InboundBase'])) { $this->validationErrors['InboundBase'] = $validErrors['InboundBase']; } ?>

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
                <li><span class="number">2</span><span class="txt">ボックス<br>情報入力</span>
                </li>
                <li><span class="number">3</span><span class="txt">確認</span>
                </li>
                <li><span class="number">4</span><span class="txt">完了</span>
                </li>
            </ul>
            <?php echo $this->Flash->render(); ?>
            <form name="form" action='/inbound/box/input' method="POST">
                <?php $data_error = $this->Flash->render('data_error');?>
                <?php if (isset($data_error)) : ?>
                  <p class="valid-bl"><?php echo $data_error; ?></p>
                <?php endif; ?>
                <ul class="setting-switcher">
                    <li>
                        <label class="setting-switch">
                            <input type="radio" class="ss" name="data[InboundBase][box_type]" value="new" <?php echo ($this->request->data['InboundBase']['box_type'] == 'new') ? 'checked' : ''; ?>>
                            <span class="btn-ss"><span class="icon"></span>新しく取寄せた<br class="sp">ボックスを預ける</span>
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
                <?php foreach ($holding_box_list as $box_type => $box_list): ?>
                <div id="dev-<?php echo $box_type; ?>-box" class="item-content" <?php echo $this->request->data['InboundBase']['box_type'] == $box_type ? '' : 'style="display:none"'; ?>>
                  <?php if ($box_type == 'old'): ?>
                  <p class="page-caption">minikuraHAKOのみ再度のお預け入れが可能でございます。<br>
                    ボックスの状態については十分ご確認の上、ご利用ください。<br>
                    なお、再度のお預け入れの場合、初月保管料金の無料は含まれておりません。
                  </p>
                  <?php endif; ?>
                  <ul class="l-caution">
                    <li>
                      <a href="javascript:void(0)" data-remodal-target="about-id" class="title-caution">
                        <img src="/images/question.svg">ボックスIDについて
                      </a>
                    </li>
                    <li>
                      <a href="javascript:void(0)" data-remodal-target="free-box" class="title-caution">
                        <img src="/images/question.svg">無料期限について
                      </a>
                    </li>
                  </ul>
                  <?php echo $this->Form->error("BoxList.{$box_type}.box", null, ['wrap' => 'p']) ?>
                  <ul id="dev-<?php echo $box_type; ?>-box-grid" class="grid grid-md">
                    <?php foreach ($box_list as $box): ?>
                    <li>
                      <label class="input-check box-img-area">
                        <?php
                          echo $this->Form->input(
                              "BoxList.{$box_type}.{$box['box_id']}.checkbox",
                              [
                                  'class'       => 'cb-circle dev-box-check',
                                  'label'       => false,
                                  'error'       => false,
                                  'type'        => 'checkbox',
                                  'div'         => false,
                                  'value'       => '1',
                                  'hiddenField' => false,
                              ]
                          );
                        ?>
                        <span class="icon"></span>
                        <span class="item-img">
                          <img src="<?php echo KIT_IMAGE[$box['kit_cd']] ?>" alt="<?php echo $box['kit_name']; ?>" class="img-item">
                        </span>
                      </label>
                      <div class="box-info">
                        <p class="l-box-id">
                            <span class="txt-box-id"><?php echo $box['box_id']; ?></span>
                            <?php if(!empty($box['free_limit_date'])):?>
                            <span class="txt-free-limit">無料期限<span class="date"><?php echo $box['free_limit_date']; ?></span></span>
                            <?php endif;?>
                        </p>
                        <p class="box-type"><?php echo $box['kit_name']; ?></p>
                      </div>
                    </li>
                    <?php endforeach; ?>
                  </ul>
                  <p class="page-caption not-applicable dev-mb1">
                    <?php if(empty($box_list) && $box_type == 'new'): ?>
                    新しいボックスが存在しません。
                    <?php elseif(empty($box_list) && $box_type == 'old'): ?>
                    取り出し済ボックスが存在しません。
                    <?php endif; ?>
                  </p>
                </div>
                <?php endforeach; ?>
                <ul class="input-info">
                    <li>
                        <label class="headline">預け入れ・撮影についてのよくあるご質問</label>
                        <ul class="frequently">
                            <li>預け入れまでの流れについては<a href="<?php echo Configure::read('site.static_content_url'); ?>/help/packing.html" target="_blank">専用ボックスの到着から預け入れまで</a></li>
                            <li>minikuraMONOの撮影については<a href="https://help.minikura.com/hc/ja/articles/221053727" target="_blank">撮影付き保管サービスの概要</a></li>
                            <li>注意事項については<a href="https://help.minikura.com/hc/ja/articles/216414387" target="_blank">お取り出し・配送について</a></li>
                        </ul>
                    </li>
                </ul>
            </form>
        </div>
        <div class="nav-fixed">
            <ul>
                <li><button id="execute" class="btn-red">ボックス情報入力</button>
                </li>
            </ul>
        </div>

        <!-- popup -->
        <div class="remodal about-id" data-remodal-id="about-id">
            <p class="page-caption">バーコード番号(ボックスID)はボックス側面にバーコードと共に記載されています。</p>
            <img src="/images/about-id@2x.png" alt="">
            <a class="btn-d-gray" data-remodal-action="close">閉じる</a>
        </div>
        <div class="remodal about-id" data-remodal-id="free-box">
          <p class="page-caption">無料期限とは、サービス申し込み代金の無料期限のことです。記載の日付までに倉庫に到着すると、お申し込み代金が無料でご利用いただけます。</p>
          <a class="btn-d-gray" data-remodal-action="close">閉じる</a>
        </div>
