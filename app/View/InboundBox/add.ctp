<?php $this->Html->script('minikura/inboundbox', ['block' => 'scriptMinikura']); ?>
<div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> ボックス預け入れ</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <?php echo $this->Form->create('Inbound', ['url' => '/inbound/box/confirm', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>預け入れボックスを選択</h2>
                <p class="form-control-static col-lg-12">ご購入済みの専用ボックスの一覧です。<br />
                  預け入れるボックスのタイトルを入力してボックスを選択しましたら「預け入れボックスの確認」にすすんでください。</p>
                <div class="row box-list">
                  <!--loop-->
                  <?php foreach ($boxList as $box): ?>

                  <?php
                  $i = $box['box_id'];
                  echo $this->Form->hidden("Inbound.box_list.${i}.box_id", ['value' => $box['box_id']]); ?>
                  <?php echo $this->Form->hidden("Inbound.box_list.${i}.product_cd", ['value' => $box['product_cd']]); ?>
                  <?php echo $this->Form->hidden("Inbound.box_list.${i}.product_name", ['value' => $box['product_name']]); ?>
                  <?php echo $this->Form->hidden("Inbound.box_list.${i}.kit_cd", ['value' => $box['kit_cd']]); ?>
                  <?php echo $this->Form->hidden("Inbound.box_list.${i}.box_id", ['value' => $box['box_id']]); ?>
                  <div class="col-lg-12">
                    <div class="panel panel-default">
                      <div class="panel-body <?php echo $this->MyPage->boxClassName($box); ?>">
                        <div class="row">
                          <?php $kitCd = $box['kit_cd'];
                          if (array_key_exists($kitCd, KIT_OPTION)) : ?>
                              <div class="col-lg-6 col-md-6 col-sm-12">
                                <?php echo $this->Form->text("Inbound.box_list.${i}.title", ['class' => 'form-control', 'error' => false, 'placeholder' => 'ボックスタイトルを入力してください']); ?>
                                <?php echo $this->Form->error("Inbound.box_list.${i}.title", null, ['wrap' => 'p']) ?>
                              </div>
                              <div class="col-lg-3 col-md-3 col-sm-12">
                                <?php echo $this->Form->select("Inbound.box_list.${i}.option", KIT_OPTION[$kitCd], ['class' => 'form-control', 'empty' => '選択してください', 'error' => false]); ?>
                              </div>
                          <?php else: ?>
                              <div class="col-lg-9 col-md-9 col-xs-12">
                                <?php echo $this->Form->text("Inbound.box_list.${i}.title", ['class' => 'form-control', 'error' => false, 'placeholder' => 'ボックスタイトルを入力してください']); ?>
                                <?php echo $this->Form->error("Inbound.box_list.${i}.title", null, ['wrap' => 'p']) ?>
                              </div>
                          <?php endif; ?>
                          <div class="col-lg-3 col-md-3 col-xs-12 box_select_checkbox">
                              <?php echo $this->Form->checkbox("Inbound.box_list.${i}.checkbox"); ?>
                              <button class="btn btn-danger btn-md btn-block btn-detail inbound-btn"></button>
                          </div>
                        </div>
                      </div>
                      <div class="panel-footer">
                        <div class="row">
                          <div class="col-lg-12 col-md-12 col-sm-12">
                            <p class="box-list-caption"><span>商品名</span><?php echo $box['product_name'] ?></p>
                            <p class="box-list-caption"><span>ボックスID</span><?php echo $box['box_id'] ?></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                  <!--loop end-->
                </div>
              </div>
            </div>
            <div class="form-group col-lg-12">
              <label>預け入れ方法</label>
              <?php echo $this->Form->select("Inbound.delivery_carrier", INBOUND_CARRIER_DELIVERY, ['class' => 'form-control', 'empty' => '以下からお選びください', 'error' => false]); ?>
              <?php echo $this->Form->error("Inbound.delivery_carrier", null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12 inbound_pickup_only">
              <label>集荷の住所</label>
              <?php echo $this->Form->select("Inbound.address_id", $this->order->setAddress($addressList), ['class' => 'form-control', 'empty' => '以下からお選びください', 'error' => false]); ?>
              <?php echo $this->Form->error("Inbound.address_id", null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12 inbound_pickup_only">
              <label>集荷の日程</label>
              <?php echo $this->Form->select("Inbound.day_cd", $this->order->setOption($dateList, 'date_cd', 'text'), ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
              <?php echo $this->Form->error("Inbound.day_cd", null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12 inbound_pickup_only">
              <label>集荷の時間</label>
              <?php echo $this->Form->select("Inbound.time_cd", $this->order->setOption($timeList, 'time_cd', 'text'), ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
              <?php echo $this->Form->error("Inbound.time_cd", null, ['wrap' => 'p']) ?>
            </div>
            <span class="col-lg-12 col-md-12 col-xs-12">
                <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">預け入れボックスの確認</button>
            </span>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
  </div>
