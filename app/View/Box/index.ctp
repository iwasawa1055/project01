<?php $this->Html->script('minikura/box', ['block' => 'scriptMinikura']); ?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-cube"></i> ボックスリスト</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2><?php echo $productName; ?></h2>

                <div class="col-lg-12">
                  <ul class="sp-navi">
                    <?php if (! empty($summary_all)):?>
                      <li>
                        <a href="/box?product=" class="btn btn-success btn-block btn-xs btn-sort<?php if($active_status['box']['all']):?> on<?php endif;?> animsition-link">すべての<br />
                        ボックス</a>
                      </li>
                    <?php endif;?>
                    <?php
                    if ($customer->isSneaker()) {
                      $inUseService = IN_USE_SERVICE['sneakers'];
                    } else {
                      $inUseService = IN_USE_SERVICE['minikura'];
                    }
                    ?>
                    <?php foreach($inUseService as $k => $v): ?>
                      <?php if(hash::get($summary_all, $v['product_cd'], '0') > 0) : ?>
                        <li>
                          <a href="/box?product=<?php echo $v['product'];?>" class="btn btn-success btn-block btn-xs btn-sort<?php if ($active_status['box'][$v['product']]):?> on<?php endif;?> animsition-link"><?php echo $v['name_mobile'];?></a>
                        </li>
                      <?php endif;?>
                    <?php endforeach;?>
                  </ul>
                </div>

                <?php echo $this->Form->create('BoxSearch', ['type' => 'get','id' => 'box-search', 'url' => ['controller' => 'box', 'action' => 'index'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                  <?php echo $this->Form->text('product', ['type' => 'hidden', 'value' => $product]);?>
                  <?php echo $this->Form->text('hide_outbound', ['type' => 'hidden', 'value' => $hide_outbound]);?>
                  <div class="row box-sort">
                    <div class="col-xs-12">
                      <div class="input-group custom-search-form">
                      <?php echo $this->Form->text("keyword", ['class' => 'form-control', 'error' => false, 'placeholder' => '検索する', 'value' => $keyword]); ?>
                        <span class="input-group-btn">
                          <?php echo $this->Form->button('<i class="fa fa-search"></i>',['class' => 'btn btn-default btn-search', 'value' => 'search', 'type' => 'submit']);?>
                        </span>
                      </div>
                    </div>
                  </div>
                <?php echo $this->Form->end(); ?>

                <?php echo $this->Form->create('BoxSort', ['type' => 'get','id' => 'box-sort', 'url' => ['controller' => 'box', 'action' => 'index'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                  <?php echo $this->Form->text('product', ['type' => 'hidden', 'value' => $product]);?>
                  <?php echo $this->Form->text('hide_outbound', ['type' => 'hidden', 'value' => $hide_outbound]);?>
                  <?php echo $this->Form->text('keyword', ['type' => 'hidden', 'value' => $keyword]);?>
                  <div class="row box-sort">
                    <div class="col-sm-6 col-xs-12">
                    <?php echo $this->Form->select('order', SORT_ORDER['box'], ['class' => 'form-control', 'empty' => false, 'error' => false, 'value' => $order]); ?>
                    </div>
                    <div class="col-sm-3 col-xs-4">
                    <?php echo $this->Form->select('direction', SORT_DIRECTION, ['class' => 'form-control', 'empty' => false, 'error' => false, 'value' => $direction]); ?>
                    </div>
                    <div class="col-sm-3 col-xs-8">
                      <?php echo $this->Form->button('表示する',['class' => 'btn btn-danger btn-block btn-sm btn-sort', 'value' => 'sort', 'type' => 'submit']);?>
                    </div>
                  </div>
                <?php echo $this->Form->end(); ?>

                <div class="row box-sort">
                  <div class="col-sm-5 col-sm-offset-7 col-sm-12">
                    <?php if ($hideOutbound): ?>
                    <?php echo $this->Html->link('取り出し済み以外を表示する', $hideOutboundSwitchUrl, ['class' => 'btn btn-primary btn-block btn-xs btn-sort animsition-link']); ?>
                    <?php else: ?>
                    <?php echo $this->Html->link('取り出し済みのみを表示する', $hideOutboundSwitchUrl, ['class' => 'btn btn-primary btn-block btn-xs btn-sort animsition-link']); ?>
                    <?php endif; ?>
                  </div>
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
