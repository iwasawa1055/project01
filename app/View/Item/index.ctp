<?php $this->Html->script('minikura/item', ['block' => 'scriptMinikura']); ?>
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
                <h2>minikuraMONO</h2>
                <div class="col-lg-12">
                  <ul class="sp-navi">
                    <li>
                      <a href="/item?product=" class="btn btn-success btn-block btn-xs btn-sort<?php if ($active_status['item']['all']):?> on<?php endif;?> animsition-link">すべての<br />
                      アイテム</a>
                    </li>
                    <li>
                      <a href="/item?product=mono" class="btn btn-success btn-block btn-xs btn-sort<?php if ($active_status['item']['mono']):?> on<?php endif;?> animsition-link">minikura<br />
                      MONO</a>
                    </li>
                    <li>
                      <a href="/item?product=cargo01" class="btn btn-success btn-block btn-xs btn-sort<?php if ($active_status['item']['cargo01']):?> on<?php endif;?> animsition-link">CARGO <br />
                      じぶんで </a>
                    </li>
                    <li>
                      <a href="/item?product=cargo02" class="btn btn-success btn-block btn-xs btn-sort<?php if ($active_status['item']['cargo02']):?> on<?php endif;?> animsition-link">CARGO <br />
                      ひとまかせ </a>
                    </li>
                    <li>
                      <a href="/item?product=cleaning" class="btn btn-success btn-block btn-xs btn-sort<?php if ($active_status['item']['cleaning']):?> on<?php endif;?> animsition-link">クリーニング <br />
                      パック</a>
                    </li>
                    <li>
                      <a href="/item?product=shoes" class="btn btn-success btn-block btn-xs btn-sort<?php if ($active_status['item']['shoes']):?> on<?php endif;?> animsition-link">シューズ <br />
                      パック</a>
                    </li>
                  </ul>
                </div>
                <?php echo $this->Form->create('BoxSearch', ['type' => 'get','id' => 'box-search', 'url' => ['controller' => 'box', 'action' => 'index'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                  <?php echo $this->Form->text('product', ['type' => 'hidden', 'value' => $product]);?>
                  <?php echo $this->Form->text('hide_outbound', ['type' => 'hidden', 'value' => $hide_outbound]);?>
                  <div class="row box-sort">
                    <div class="col-xs-12">
                      <div class="input-group custom-search-form">
                        <input type="text" class="form-control" placeholder="検索する">
                        <span class="input-group-btn">
                        <?php echo $this->Form->text("keyword", ['class' => 'form-control', 'error' => false, 'placeholder' => '検索する', 'value' => $keyword]); ?>
                        </span>
                      </div>
                    </div>
                  </div>
                <?php echo $this->Form->end(); ?>
                <div class="row box-sort">
                  <div class="col-sm-6 col-xs-12">
                    <?php echo $this->Form->select('order', SORT_ORDER, ['class' => 'form-control', 'empty' => false, 'error' => false, 'value' => $order]); ?>
                  </div>
                  <div class="col-sm-3 col-xs-4">
                    <?php echo $this->Form->select('direction', SORT_DIRECTION, ['class' => 'form-control', 'empty' => false, 'error' => false, 'value' => $direction]); ?>
                  </div>
                  <div class="col-sm-3 col-xs-8">
                    <a href="#" class="btn btn-danger btn-block btn-sm btn-sort animsition-link">表示する</a>
                  </div>
                </div>
                <div class="row box-sort">
                  <div class="col-sm-5 col-sm-offset-7 col-sm-12">
                    <?php if ($hideOutboud): ?>
                      <?php echo $this->Html->link('取り出し済み以外を表示する', $hideOutboudSwitchUrl, ['class' => 'btn btn-primary btn-block btn-xs btn-sort animsition-link']); ?>
                    <?php else: ?>
                    <?php echo $this->Html->link('取り出し済みのみを表示する', $hideOutboudSwitchUrl, ['class' => 'btn btn-primary btn-block btn-xs btn-sort animsition-link']); ?>
                    <?php endif; ?>

                  </div>
                </div>
              <?php if ($item_all_count === 0) : ?>
                <?php echo $this->element('List/empty'); ?>
              <?php else: ?>
            <?php endif; ?>
              <div class="col-lg-12">
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
