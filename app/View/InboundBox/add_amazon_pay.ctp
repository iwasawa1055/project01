<?php
if (!empty($validErrors)) {
    $this->Form->validationErrors = $validErrors;
}
?>
<?php $this->Html->script('minikura/inboundbox', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('inbound_box/add_amazon_pay', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->css('/css/app.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/app_dev.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/dsn-amazon-pay.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/add_amazon_pay_dev.css', ['block' => 'css']); ?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> ボックス預け入れ</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <?php echo $this->Form->create('Inbound', ['url' => '/inbound/box/confirm_amazon_pay', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true, 'class' => 'select-add-address-form']); ?>
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>預け入れボックスを選択</h2>

              <?php if (empty($boxList)) : ?>
                <p class="form-control-static col-lg-12">ご購入済みのキットがございません。<br />
                  預け入れの際はまず弊社指定の専用キットをご購入ください。</p>
              <?php else: ?>
                <p class="form-control-static col-lg-12">ご購入済みの専用ボックスの一覧です。<br />
                  預け入れるボックスのタイトルを入力してボックスを選択しましたら「預け入れボックスの確認」にすすんでください。</p>
              <?php endif; ?>
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
                              <div class="col-lg-5 col-md-5 col-sm-12">
                                <?php echo $this->Form->text("Inbound.box_list.${i}.title", ['class' => 'form-control', 'error' => false, 'placeholder' => 'ボックスタイトルを入力してください']); ?>
                                <?php echo $this->Form->error("Inbound.box_list.${i}.title", null, ['wrap' => 'p']) ?>
                              </div>
                              <div class="col-lg-4 col-md-4 col-sm-12">
                                <?php echo $this->Form->select("Inbound.box_list.${i}.option", KIT_OPTION[$kitCd], ['class' => 'form-control', 'empty' => '選択してください', 'error' => false]); ?>
                              </div>
                          <?php else: ?>
                              <div class="col-lg-9 col-md-9 col-xs-12">
                                <?php echo $this->Form->text("Inbound.box_list.${i}.title", ['class' => 'form-control', 'error' => false, 'placeholder' => 'ボックスタイトルを入力してください']); ?>
                                <?php echo $this->Form->error("Inbound.box_list.${i}.title", null, ['wrap' => 'p']) ?>
                              </div>
                          <?php endif; ?>
                          <div class="col-lg-3 col-md-3 col-xs-12 inbound_box_select_checkbox">
                              <?php echo $this->Form->checkbox("Inbound.box_list.${i}.checkbox"); ?>
                              <button class="btn btn-danger btn-md btn-block btn-detail inbound-btn"></button>
                          </div>
                        </div>
                        <?php echo $this->Form->error("box_list.${box['box_id']}.title", null, ['wrap' => 'p']) ?>
                        <?php echo $this->Form->error("box_list.${box['box_id']}.option", null, ['wrap' => 'p']) ?>
                      </div>
                      <?php echo $this->element('List/box_footer', ['box' => $box]); ?>
                    </div>
                  </div>
                  <?php endforeach; ?>
                  <?php echo $this->Form->error("Inbound.box", null, ['wrap' => 'p']) ?>
                  <!--loop end-->
                </div>
              </div>
            </div>
          <?php if (!empty($boxList)) : ?>
            <div class="form-group col-lg-12">
              <label>預け入れ方法</label>
              <?php if($customer->isSneaker()):?>
                <?php echo $this->Form->select("Inbound.delivery_carrier", INBOUND_CARRIER_DELIVERY_SNEAKERS, ['class' => 'form-control', 'empty' => '以下からお選びください', 'error' => false]); ?>
              <?php else:?>
                <?php echo $this->Form->select("Inbound.delivery_carrier", INBOUND_CARRIER_DELIVERY, ['class' => 'form-control', 'empty' => '以下からお選びください', 'error' => false]); ?>
              <?php endif;?>
              <?php echo $this->Form->error("Inbound.delivery_carrier", null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12 inbound_pickup_only">
              <label>集荷の住所</label>

              <div id="dsn-amazon-pay" class="form-group col-lg-12">
                <div class="dsn-address">
                  <div id="addressBookWidgetDiv">
                  </div>
                </div>
              </div>
              
              <?php echo $this->Form->error('Inbound.lastname', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Inbound.lastname_kana', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Inbound.firstname', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Inbound.firstname_kana', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Inbound.tel1', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Inbound.postal', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Inbound.pref', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Inbound.address1', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Inbound.address2', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
              <?php echo $this->Form->error('Inbound.address3', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
            </div>

            <div class="form-group col-lg-12 inbound_pickup_only">
              <label>お名前</label>
              <div class="dsn-form">
                <input type="text" name="lastname" class="dsn-name-last lastname focused" placeholder="寺田" size="10" maxlength="30">
                <input type="text" name="firstname" class="dsn-name-first firstname focused" placeholder="太郎" size="10" maxlength="30">
                <br>
                <?php echo $this->Form->error("Inbound.lastname", null, ['wrap' => 'p']) ?>
                <?php echo $this->Form->error("Inbound.firstname", null, ['wrap' => 'p']) ?>
              </div>
            </div>

            <div class="form-group col-lg-12 inbound_pickup_only">
              <label>集荷の日程</label>
              <?php echo $this->Form->select("Inbound.day_cd", $this->Order->setOption($dateList, 'date_cd', 'text'), ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
              <?php echo $this->Form->error("Inbound.day_cd", null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12 inbound_pickup_only">
              <label>集荷の時間</label>
              <?php echo $this->Form->select("Inbound.time_cd", $this->Order->setOption($timeList, 'time_cd', 'text'), ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
              <?php echo $this->Form->error("Inbound.time_cd", null, ['wrap' => 'p']) ?>
            </div>
            <span class="col-lg-12 col-md-12 col-xs-12">
                <button type="submit" class="btn btn-danger btn-lg btn-block js-btn-submit">預け入れボックスを確認する</button>
            </span>
          <?php endif; ?>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>

<script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>"></script>