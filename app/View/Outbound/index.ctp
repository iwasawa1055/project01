<?php $this->Html->script('minikura/outbound', ['block' => 'scriptMinikura']); ?>
<?php $noSelect = (count($itemList) === 0 && count($boxList) === 0)  ?>
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i>取り出し</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
  <?php if (!$noSelect) : ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <?php if (!empty($itemList)) : ?>
            <h2>取り出すアイテム</h2>
            <?php endif; ?>
            <div class="row box-list">
              <?php foreach ($itemList as $item): ?>
              <?php
              $i = $item['item_id'];
              $url = '/item/detail/' . $item['item_id'];
              ?>
              <!--loop-->
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <?php echo $this->element('List/item_body_outbound', ['item' => $item]); ?>
                  <?php echo $this->element('List/item_footer', ['item' => $item]); ?>
                </div>
              </div>
              <!--loop end-->
              <?php endforeach; ?>
            </div>
            <?php echo $this->Form->error("Outbound.product", null, ['wrap' => 'p']) ?>
          </div>
          <div class="col-lg-12">
            <?php if (!empty($boxList)) : ?>
            <h2>取り出すボックス</h2>
            <?php endif; ?>
            <div class="row box-list">
              <?php foreach ($boxList as $box): ?>
              <!--loop-->
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <?php echo $this->element('List/box_body_outbound', ['box' => $box]); ?>
                  <?php echo $this->element('List/box_footer', ['box' => $box]); ?>
                </div>
              </div>
              <!--loop end-->
              <?php endforeach; ?>
            </div>
            <?php echo $this->Form->error("Outbound.product", null, ['wrap' => 'p']) ?>
          </div>
        </div>
      </div>
    </div>
    <?php echo $this->Form->create('Outbound', ['url' => '/outbound/confirm', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true, 'class' => 'select-add-address-form']); ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <h2>ポイントのご利用</h2>
            <p class="form-control-point col-lg-12"> お持ちのポイントをご利用料金に割り当てることが出来ます。<a href="<?php echo Configure::read('site.static_content_url'); ?>/lineup/points.html" class="animsition-link">▶ポイントについて</a>
              <br />
              ※1ポイント＝1円換算<br />
              ※100ポイントから利用可能です。<br />
              ※ポイントは100ポイント以上の残高かつ10ポイント単位からのご利用となります。</p>
            <div class="form-group col-lg-12">
              <span class="point"><?php echo $pointBalance['point_balance']; ?></span> ポイント利用可能です。
              <p class="help-block">ご利用状況によっては、お申込みされたポイントをご利用できない場合がございます。
                取り出しのお知らせやオプションのお知らせにはポイント料金調整前の価格が表示されます。ご了承ください。</p>
              <h3>利用ポイント</h3>
              <div class="form-group col-lg-2">
                <?php if (!empty($pointBalance['point_balance'])) : ?>
                  <?php echo $this->Form->input('PointUse.use_point', ['class' => 'form-control', 'error' => false]); ?>
                <?php else : ?>
                  <?php echo $this->Form->input('PointUse.use_point', ['class' => 'form-control', 'value' => '0', 'readonly' => 'readonly', 'error' => false]); ?>
                <?php endif; ?>
              </div>
              <div class="form-group col-lg-12">
                <?php echo $this->Form->error("PointUse.use_point", null, ['wrap' => 'p']) ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="form-group col-lg-12">
          <label>お届け先住所</label>
          <?php echo $this->Form->select("Outbound.address_id", $this->Order->setAddress($addressList), ['class' => 'form-control select-add-address', 'empty' => '以下からお選びください', 'error' => false]); ?>
          <?php echo $this->Form->error("Outbound.address_id", null, ['wrap' => 'p']) ?>
          <?php if (!$this->Form->isFieldError('Outbound.address_id')) : ?>
          <?php echo $this->Form->error('Outbound.lastname', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('Outbound.lastname_kana', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('Outbound.firstname', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('Outbound.firstname_kana', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('Outbound.tel1', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('Outbound.postal', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('Outbound.pref', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('Outbound.address1', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('Outbound.address2', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php echo $this->Form->error('Outbound.address3', __d('validation', 'format_address'), ['wrap' => 'p']) ?>
          <?php endif; ?>
        </div>
        <div class="form-group col-lg-12">
          <label>お届け希望日と時間帯</label>
          <?php echo $this->Form->select("Outbound.datetime_cd", $this->Order->setDatetime($dateItemList), ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
          <?php echo $this->Form->error("Outbound.datetime_cd", null, ['wrap' => 'p']) ?>
        </div>
        <span class="col-lg-6 col-md-6 col-xs-12">
          <a class="btn btn-primary btn-lg btn-block" href="/outbound/item">アイテムを選択に戻る</a>
        </span>
        <span class="col-lg-6 col-md-6 col-xs-12">
          <a class="btn btn-primary btn-lg btn-block" href="/outbound/box">ボックスを選択に戻る</a>
        </span>
        <span class="col-lg-12 col-md-12 col-xs-12">
          <button type="submit" class="btn btn-danger btn-lg btn-block">この内容で確認する</button>
        </span>
      </div>
    </div>
    <?php echo $this->Form->end(); ?>
  <?php endif; ?>
  <?php if ($noSelect) : ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="col-lg-12">
          <?php echo $this->element('List/empty_outbound'); ?>
        </div>
        <span class="col-lg-6 col-md-6 col-xs-12">
          <a class="btn btn-primary btn-lg btn-block" href="/outbound/mono">アイテムを取り出す</a>
        </span>
        <span class="col-lg-6 col-md-6 col-xs-12">
          <a class="btn btn-primary btn-lg btn-block" href="/outbound/box">ボックスを取り出す</a>
        </span>
      </div>
    </div>
  <?php endif; ?>
  </div>
</div>
