<?php $this->Html->script('minikura/box', ['block' => 'scriptMinikura']); ?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-tag"></i> ご利用中のサービス</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                  <?php
                  $productName = '';
                    if ($product === 'mono') {
                        $productName = 'minikuraMONO';
                    } else if ($product === 'hako') {
                        $productName = 'minikuraHAKO';
                    } else if ($product === 'cargo01') {
                        $productName = 'minikura CARGO じぶんでコース';
                    } else if ($product === 'cargo02') {
                        $productName = 'minikura CARGO ひとまかせコース';
                    } else if ($product === 'cleaning') {
                        $productName = 'クリーニングパック';
                    } else if ($product === 'shoes') {
                        $productName = 'シューズパック';
                    } else if ($product === 'sneakers') {
                        $productName = 'minikura SNEAKERS';
                    }
                  ?>
                <h2><?php echo $productName; ?></h2>

<!-- 開発用暫定 -->
<?php echo $this->Form->create('BoxSearch', ['type' => 'get','id' => 'box-search', 'url' => ['controller' => 'box', 'action' => 'index'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>

  <input type="hidden" name="product" value="<?php echo $product;?>" />
  <?php 
  if(!empty($this->request->query('hide_outboud'))) {
    $hide_outbound = '1';
  } else {
    $hide_outbound = '0';
  }
  ?>
  <input type="hidden" name="hide_outboud" value="<?php echo $hide_outbound;?>" />
  <div class="row box-sort">
    <div class="col-xs-12">
      <div class="input-group custom-search-form">
      <?php 
      $keyword_value = null;
      if (!empty($this->request->query('keyword'))) {
        $keyword_value = $this->request->query('keyword');
      }
      echo $this->Form->text("keyword", ['class' => 'form-control', 'error' => false, 'placeholder' => '検索する', 'value' => $keyword_value]); ?>
        <span class="input-group-btn">
          <button class="btn btn-default btn-search" type="submit" value="search">
            <i class="fa fa-search"></i>
          </button>
        </span>
      </div>
    </div>
  </div>
<?php echo $this->Form->end(); ?>

<?php echo $this->Form->create('BoxSort', ['type' => 'get','id' => 'box-sort', 'url' => ['controller' => 'box', 'action' => 'index'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
  <input type="hidden" name="product" value="<?php echo $product;?>" />
  <input type="hidden" name="keyword" value="<?php echo $keyword_value;?>" />
  <input type="hidden" name="hide_outboud" value="<?php echo $hide_outbound;?>" />
  <div class="row box-sort">
    <div class="col-sm-6 col-xs-12">
    <?php echo $this->Form->select('order', ['inbound_date' => 'お預かり日順 で', 'box_id' => 'ボックスID順 で', 'box_name' => 'ボックス名順 で'], ['class' => 'form-control', 'empty' => false, 'error' => false, 'value' => 'box_id']); ?>
    </div>
    <div class="col-sm-3 col-xs-4">
    <?php echo $this->Form->select('direction', ['asc' => 'A〜Z', 'desc' => 'Z〜A'], ['class' => 'form-control', 'empty' => false, 'error' => false, 'value' => '']); ?>
  </div>
    <div class="col-sm-3 col-xs-8">
          <button class="btn btn-danger btn-block btn-sm btn-sort" type="submit" value="sort">
          表示する
          </button>
    </div>
  </div>
<?php echo $this->Form->end(); ?>

  <div class="row box-sort">
    <div class="col-sm-5 col-sm-offset-7 col-sm-12">
      <a class="btn btn-primary btn-block btn-xs btn-sort animsition-link" href="#">取り出し済みを表示する</a>
    </div>
  </div>
<!-- /開発用暫定設定 -->

                <div class="row box-list">
                <?php /*
                  <?php if (empty($boxList)) : ?>
                    <?php if (in_array($product, ['mono', 'hako', 'cleaning'], true)) : ?>
                    <?php echo $this->element('List/empty'); ?>
                    <?php else: ?>
                    <?php echo $this->element('List/empty_notorder'); ?>
                    <?php endif; ?>
                  <?php else: ?>
                */?>
                  <div class="col-lg-3 col-lg-offset-6">
                    <?php if ($hideOutboud): ?>
                    <?php echo $this->Html->link('出庫済み以外を表示する', $hideOutboudSwitchUrl, ['class' => 'btn btn-primary btn-block']); ?>
                    <?php else: ?>
                    <?php echo $this->Html->link('出庫済みのみを表示する', $hideOutboudSwitchUrl, ['class' => 'btn btn-primary btn-block']); ?>
                    <?php endif; ?>
                  </div>
                  <div class="col-lg-3">
                    <?php echo $this->Form->input(false, ['type' => 'select', 'options' => $sortSelectList, 'selected' => $select_sort_value, 'id' => 'select_sort', 'class' => 'form-control sort-form', 'empty' => '並べ替え', 'label'=>false, 'div'=>false]); ?>
                  </div>
                <?php /*<?php endif; ?>*/?>
                  <?php foreach ($boxList as $box): ?>
                  <?php $url = '/box/detail/' . $box['box_id']; ?>
                  <!--loop-->
                  <div class="col-lg-12">
                    <div class="panel panel-default">
                      <?php echo $this->element('List/box_body', ['box' => $box]); ?>
                      <?php echo $this->element('List/box_footer', ['box' => $box]); ?>
                    </div>
                  </div>
                  <?php endforeach; ?>
                  <!--loop end-->
                </div>
                <?php echo $this->element('paginator'); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
