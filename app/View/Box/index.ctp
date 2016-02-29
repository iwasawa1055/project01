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
                    } else if ($product === 'cleaning') {
                        $productName = 'クリーニングパック';
                    } else
                  ?>
                <h2><?php echo $productName; ?></h2>
                <div class="row box-list">
                  <div class="col-lg-3 col-lg-offset-6">
                    <?php if ($hideOutboud): ?>
                    <?php echo $this->Html->link('出庫済みを隠す', $hideOutboudSwitchUrl, ['class' => 'btn btn-primary btn-block']); ?>
                    <?php else: ?>
                    <?php echo $this->Html->link('出庫済みを表示', $hideOutboudSwitchUrl, ['class' => 'btn btn-primary btn-block']); ?>
                    <?php endif; ?>
                  </div>
                  <div class="col-lg-3">
                    <?php echo $this->Form->input(false, ['type' => 'select', 'options' => $sortSelectList, 'selected' => $select_sort_value, 'id' => 'select_sort', 'class' => 'form-control sort-form', 'empty' => '並べ替え', 'label'=>false, 'div'=>false]); ?>
                  </div>
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
