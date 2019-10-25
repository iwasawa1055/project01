<?php $this->Html->script('minikura/item', ['block' => 'scriptMinikura']); ?>
<style type="text/css">
<!--
#page-wrapper > div:nth-child(3) > div > div > div > div > div > div.col-lg-12.item-list > ul > li {
    min-width: 0;
}
@media (max-width:768px) {
    #page-wrapper > div:nth-child(3) > div > div > div > div > div > div.col-lg-12.item-list > ul > li {
        min-width: 48%;
    }
}
-->
</style>
  <div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-diamond"></i> アイテムリスト</h1>
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
                        <a href="/item?product=" class="btn btn-success btn-block btn-xs btn-sort<?php if ($active_status['item']['all']):?> on<?php endif;?> animsition-link">すべての<br />
                        アイテム</a>
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
                      <?php if ($v['product'] === 'hako') continue; ?>
                      <?php if(hash::get($summary_all, $v['product_cd'], '0') > 0) : ?>
                        <li>
                          <a href="/item?product=<?php echo $v['product'];?>" class="btn btn-success btn-block btn-xs btn-sort<?php if ($active_status['item'][$v['product']]):?> on<?php endif;?> animsition-link"><?php echo $v['name_mobile'];?></a>
                        </li>
                      <?php endif;?>
                    <?php endforeach;?>
                  </ul>
                </div>
                <?php echo $this->Form->create('ItemSearch', ['type' => 'get','id' => 'box-search', 'url' => ['controller' => 'item', 'action' => 'index'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
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

                <?php echo $this->Form->create('ItemSort', ['type' => 'get','id' => 'item-sort', 'url' => ['controller' => 'item', 'action' => 'index'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                <?php echo $this->Form->text('product', ['type' => 'hidden', 'value' => $product]);?>
                <?php echo $this->Form->text('hide_outbound', ['type' => 'hidden', 'value' => $hide_outbound]);?>
                <?php echo $this->Form->text('keyword', ['type' => 'hidden', 'value' => $keyword]);?>
                <div class="row box-sort">
                  <div class="col-sm-6 col-xs-12">
                    <?php echo $this->Form->select('order', SORT_ORDER['item'], ['class' => 'form-control', 'empty' => false, 'error' => false, 'value' => $order]); ?>
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
              <?php if ($item_all_count === 0) : ?>
                <?php echo $this->element('List/empty'); ?>
              <?php else: ?>
            <?php endif; ?>
              <div class="col-lg-12 item-list">
                <ul class="tile">
                  <!--loop-->
                  <?php foreach ($itemList as $item): ?>
                  <li class="panel panel-default">
                    <?php echo $this->element('List/item_icon_body', ['item' => $item]); ?>
                    <?php echo $this->element('List/item_icon_footer', ['item' => $item]); ?>
                  </li>
                  <?php endforeach; ?>
                  <!--loop end-->
                </ul>
              </div>
              <?php echo $this->element('paginator'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
