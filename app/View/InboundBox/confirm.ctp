<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> ボックス預け入れ</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <?php echo $this->Form->create('Inbound', ['url' => '/inbound/box/complete', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12 none-float">
            <h2>預け入れボックスを選択</h2>
            <p class="form-control-static col-lg-12">以下の内容で預け入れ手続きを行います。</p>
            <div class="row box-list">
              <!--loop-->
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
                ?>
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-body <?php echo $this->MyPage->boxClassName($box); ?>">
                    <div class="row">
                      <div class="col-lg-9 col-md-9 col-sm-12">
                        <h3 class="boxitem-name"><?php echo h($formBox['title']); ?></h3>
                      </div>
                      <div class="col-lg-3 col-md-3 col-sm-12">
                        <p class="photo-control"><?php echo $kitName; ?></p>
                      </div>
                    </div>
                  </div>
                  <?php echo $this->element('List/box_footer', ['box' => $box]); ?>
                </div>
              </div>
              <?php endforeach; ?>
              <!--loop end-->
            </div>
          </div>
        </div>
        <div class="form-group col-lg-12">
          <label>預け入れ方法</label>
          <p class="form-control-static">
              <?php echo INBOUND_CARRIER_DELIVERY[$this->data['Inbound']['delivery_carrier']] ?>
          </p>
        </div>
        <?php if (strpos($this->Form->data['Inbound']['delivery_carrier'], INBOUND_DELIVERY_PICKUP) !== FALSE): ?>
        <div class="form-group col-lg-12">
          <label>集荷の住所</label>
          <p class="form-control-static">
              <?php echo $this->Order->setAddress($addressList)[$this->data['Inbound']['address_id']] ?>
          </p>
        </div>
        <div class="form-group col-lg-12">
          <label>集荷の日程</label>
          <p class="form-control-static">
              <?php echo $this->Order->echoOption($dateList, 'date_cd', 'text', $this->data['Inbound']['day_cd']) ?>
          </p>
        </div>
        <div class="form-group col-lg-12">
          <label>集荷の時間</label>
          <p class="form-control-static">
              <?php echo $this->Order->echoOption($timeList, 'time_cd', 'text', $this->data['Inbound']['time_cd']) ?>
          </p>
        </div>
        <?php endif; ?>
        <div class="form-group col-lg-12">
          <div class="panel panel-red">
            <div class="panel-heading">
              <h4>注意事項（ご確認の上、チェックしてください）</h4>
            </div>
            <div class="panel-body">
              <p>
                <label>
                  <input type="checkbox" class="agree-before-submit">
                  重量は20kg（おおよそ1人で持ち運びできる程度）までを目安に梱包してください。</label>
              </p>
              <p>※明らかに20kgを超えた場合は保管料を別途頂戴することがございます。あらかじめご了承ください。</p>
              <p>
                <label>
                  <input type="checkbox" class="agree-before-submit">
                  発火性・引火性のある危険物、液体・生物・その他<a href="<?php echo Configure::read('site.static_content_url'); ?>/terms" target="_blank">利用規約</a>
                  で定められたものはお預かりできません。 </label>
              </p>
            </div>
            <div class="panel-footer">
              <label>
                <input type="checkbox" class="agree-before-submit">
                利用規約に同意する。</label>
            </div>
          </div>
        </div>
        <div class="form-group col-lg-12">
        <span class="col-lg-6 col-md-6 col-xs-12">
        <a class="btn btn-primary btn-lg btn-block" href="/inbound/box/add?back=true">戻る</a>
        </span>
        <span class="col-lg-6 col-md-6 col-xs-12">
            <button type="submit" class="btn btn-danger btn-lg btn-block">この内容で預け入れる</button>
        </span>
      </div>
      <?php echo $this->Form->end(); ?>
    </div>
  </div>
</div>
