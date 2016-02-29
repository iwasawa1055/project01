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
            <?php if (empty($itemList)) : ?>
              <?php echo $this->element('List/empty'); ?>
            <?php else: ?>
              <div class="col-lg-3 col-lg-offset-9">
                <?php echo $this->Form->input(false, ['type' => 'select', 'options' => $sortSelectList, 'selected' => $select_sort_value, 'id' => 'select_sort', 'class' => 'form-control sort-form', 'empty' => '並べ替え', 'label'=>false, 'div'=>false]); ?>
              </div>
            <?php endif; ?>
              <div class="col-lg-12">
                <ul class="tile">
                  <!--loop-->
                  <?php foreach ($itemList as $item): ?>
                  <li class="panel panel-default">
                      <a href="/item/detail/<?php echo $item['item_id'] ?>">
                          <img src="<?php echo $item['images_item']['image_url'] ?>" alt="<?php echo $item['item_id'] ?>">
                      </a>
                    <div class="panel-footer">
                      <p class="box-list-caption"><span>アイテム名</span><?php echo $item['item_name']; ?></p>
                      <p class="box-list-caption"><span>アイテムID</span><?php echo $item['item_id']; ?></p>
                    </div>
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
