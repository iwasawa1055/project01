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
                <h2><?php echo $productName; ?></h2>

                <!-- 開発用暫定 -->
                <?php echo $this->Form->create('BoxSearch', ['type' => 'get','id' => 'box-search', 'url' => ['controller' => 'box', 'action' => 'index'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                  <?php echo $this->Form->text('product', ['type' => 'hidden', 'value' => $product]);?>
                  <?php echo $this->Form->text('hide_outbound', ['type' => 'hidden', 'value' => $hide_outbound]);?>
                  <div class="row box-sort">
                    <div class="col-xs-12">
                      <div class="input-group custom-search-form">
                      <?php 
                      echo $this->Form->text("keyword", ['class' => 'form-control', 'error' => false, 'placeholder' => '検索する', 'value' => $keyword_value]); ?>
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
                  <?php echo $this->Form->text('keyword', ['type' => 'hidden', 'value' => $keyword_value]);?>
                  <div class="row box-sort">
                    <div class="col-sm-6 col-xs-12">
                    <?php echo $this->Form->select('order', SORT_ORDER, ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
                    </div>
                    <div class="col-sm-3 col-xs-4">
                    <?php echo $this->Form->select('direction', SORT_DIRECTION, ['class' => 'form-control', 'empty' => false, 'error' => false, 'value' => '']); ?>
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
                    <?php echo $this->Html->link('取り出し済みを表示する', $hideOutboundSwitchUrl, ['class' => 'btn btn-primary btn-block btn-xs btn-sort animsition-link']); ?>
                    <?php endif; ?>
                  </div>
                </div>
                <!-- /開発用暫定設定 -->

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
