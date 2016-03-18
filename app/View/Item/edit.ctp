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
            <?php echo $this->Form->create('Item', ['url' => '/item/detail/'.$item['item_id'].'/edit', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
              <div class="col-lg-12">
                <h2>アイテム情報の編集</h2>
                <div class="row box-list">
                  <!--loop-->
                  <div class="col-lg-12">
                    <div class="panel panel-default">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="item-detail">
                              <?php if (!empty(Hash::get($item, 'image_first.image_url'))) : ?>
                              <img src="<?php echo Hash::get($item, 'image_first.image_url'); ?>" alt="<?php echo $item['item_id'] ?>" width="100px" height="100px" class="item">
                              <?php endif; ?>
                            </div>
                            <h3>
                              <?php echo $this->Form->input('Item.item_name', ['class' => "form-control", 'maxlength' => 400, 'placeholder'=> __('item_name'), 'error' => false]); ?>
                            </h3>
                              <?php echo $this->Form->error('Item.item_name', null, ['wrap' => 'p']) ?>
                            <span class="col-xs-12 col-lg-12"><a class="btn btn-warning btn-md btn-block btn-detail disabled">預け入れ中</a>
                            </span>
                          </div>
                          <div class="col-lg-12 col-md-12 col-sm-12 item-detail-text">
                            <?php echo $this->Form->textarea('Item.item_note', ['class' => "form-control", 'rows' => 5, 'error' => false]); ?>
                            <?php echo $this->Form->error('Item.item_note', null, ['wrap' => 'p']) ?>
                          </div>
                        </div>
                      </div>
                      <?php echo $this->element('List/item_footer', ['item' => $item, 'box' => $box]); ?>
                    </div>
                    <!--loop end-->
                    
                  </div>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/item/detail/<?php echo $item['item_id'] ?>">アイテムの詳細に戻る</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">アイテム情報を保存する</button>
                </span>
              </div>
              <?php echo $this->Form->hidden('Item.item_id'); ?>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
