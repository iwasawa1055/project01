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
  <div class="row box-sort">
    <div class="col-xs-12">
      <div class="input-group custom-search-form">
      <?php 
      $keyword_value = null;
      if (!empty($this->request->query['keyword'])) {
        $keyword_value = $this->request->query['keyword'];
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
  <div class="row box-sort">
  <div class="col-sm-6 col-xs-12">
    <select name="orderby" class="form-control list_sort">
      <option value="1">お預かり日順 で</option>
      <option value="2">ボックスID順 で</option>
      <option value="3">ボックス名順 で</option>
    </select>
  </div>
  <div class="col-sm-3 col-xs-4">
    <select name="ordertype" class="form-control list_sort">
      <option value="asc">A〜Z</option>
      <option value="desc">Z〜A</option>
    </select>
  </div>
    <div class="col-sm-3 col-xs-8">
      <a class="btn btn-danger btn-block btn-sm btn-sort animsition-link" href="#">表示する</a>
    </div>
  </div>
  <div class="row box-sort">
    <div class="col-sm-5 col-sm-offset-7 col-sm-12">
      <a class="btn btn-primary btn-block btn-xs btn-sort animsition-link" href="#">取り出し済みを表示する</a>
    </div>
  </div>
<?php echo $this->Form->end(); ?>
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
