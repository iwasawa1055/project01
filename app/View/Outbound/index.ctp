<?php $this->Html->script('minikura/outbound', ['block' => 'scriptMinikura']); ?>
<?php $noSelect = (count($itemList) === 0 && count($boxList) === 0)  ?>
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i>取り出し</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
        <?php if ($noSelect) : ?>
        <div class="col-lg-12">
          <?php echo $this->element('List/empty'); ?>
        </div>
        <?php endif; ?>
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

        <?php if ($noSelect) : ?>
          <span class="col-lg-6 col-md-6 col-xs-12">
            <a class="btn btn-primary btn-lg btn-block" href="/outbound/item">アイテムを選択に戻る</a>
          </span>
          <span class="col-lg-6 col-md-6 col-xs-12">
            <a class="btn btn-primary btn-lg btn-block" href="/outbound/box">ボックスを選択に戻る</a>
          </span>
        <?php else : ?>
        <?php echo $this->Form->create('Outbound', ['url' => '/outbound/confirm', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
        <div class="form-group col-lg-12">
          <label>お届け先住所</label>
          <?php echo $this->Form->select("Outbound.address_id", $this->order->setAddress($addressList), ['class' => 'form-control', 'empty' => '以下からお選びください', 'error' => false]); ?>
          <?php echo $this->Form->error("Outbound.address_id", null, ['wrap' => 'p']) ?>
        </div>
        <div class="form-group col-lg-12">
          <label>お届け希望日と時間帯</label>
          <?php echo $this->Form->select("Outbound.datetime_cd", $this->order->setDatetime($dateItemList), ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
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
      <?php echo $this->Form->end(); ?>
      <?php endif; ?>
    </div>
  </div>
</div>
