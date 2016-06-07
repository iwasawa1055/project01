<?php $this->Html->script('minikura/item', ['block' => 'scriptMinikura']); ?>
  <div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-heart-o"></i> アイテム</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <h2>アイテムの一覧</h2>
              <?php if ($item_all_count === 0) : ?>
                <?php echo $this->element('List/empty'); ?>
              <?php else: ?>
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
