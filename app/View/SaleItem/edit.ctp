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
                <h2>アイテムの詳細</h2>
                <div class="row box-list">
                  <!--loop-->
                  <div class="col-lg-12">
                    <div class="panel panel-default">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="item-detail ">
                              <?php if (!empty(Hash::get($item, 'image_first.image_url'))) : ?>
                              <a href="<?php echo Hash::get($item, 'image_first.image_url'); ?>" data-lightbox="item-photo" data-title="<?php echo h($item['item_name']); ?>">
                                <img src="<?php echo Hash::get($item, 'image_first.image_url'); ?>" alt="<?php echo $item['item_id']; ?>" width="100px" height="100px" class="item"></a>
                              <?php endif; ?>
                            </div>
                            <h3 class="boxitem-name"><?php echo h($item['item_name']); ?></h3>
                            <div class="box-list-caption">
                              <!-- <span>カテゴリ</span>スポーツ用品 -->
                            </div>
                            <span class="col-xs-12 col-lg-12">
                                <a class="btn btn-warning btn-md btn-block btn-detail btn-regist disabled">
                                    <?php echo __('boxitem_status_' . $item['item_status']); ?>
                                </a>
                            </span>
                            <!--span class="col-xs-12 col-lg-12"><a class="btn btn-warning btn-md btn-block btn-detail btn-regist disabled">出庫済み</a>
                            </span-->
                          </div>
                          <div class="col-lg-6 col-md-6 col-xs-12">
                            <div class="col-lg-12 sale-complete-text">
                              この内容で販売を開始しますか？
                            </div>
                          </div>
                          <div class="col-lg-6 col-md-6 col-xs-12 sale">
                            <div class="col-xs-12 col-lg-12">
                              <div class="panel panel-default">
                                <div class=" panel-heading">
                                  <h4>アイテム販売</h4>
                                </div>
                                <div class="panel-body">
                                  <div class="row">
                                    <div class="col-lg-12">
                                      <div class="row">
                                        <div class="col-lg-12">
                                        <?php /*  if ($validErrors)  edit() */ ?>
                                        <?php if (!empty($validErrors)): ?>
                                        <?php echo $this->Form->create('SaleItem', ['url' => "/sale/item/edit/", 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                                          <div class="form-group">
                                            <?php echo $this->Form->input('SaleItem.title', ['class' => 'form-control', 'placeholder' => '販売名', 'error' => false]);?>
                                            <?php echo $this->Form->error('SaleItem.title', null, ['wrap' => 'p']);?>
                                          </div>
                                          <div class="form-group">
                                            <?php echo $this->Form->input('SaleItem.price', ['class' => 'form-control', 'placeholder' => '販売価格', 'error' => false]);?>
                                            <?php echo $this->Form->error('SaleItem.price', null, ['wrap' => 'p']);?>
                                          </div>
                                          <div class="form-group">
                                            <?php echo $this->Form->textarea('SaleItem.note', ['class' => 'form-control', 'rows' => 5, 'placeholder' => '商品説明', 'error' => false]);?>
                                            <?php echo $this->Form->error('SaleItem.note', null, ['wrap' => 'p']);?>
                                          </div>
                                          <button type="submit" class="btn btn-danger btn-md btn-block animsition-link" >この内容で確認する</button>
                                          <?php /* item　表示用 */ ?>
                                          <?php echo $this->Form->hidden('SaleItem.item_id', ['value' => $item['item_id']]); ?>
                                        <?php /* 必要次第 hiddenでid APIでき次第 */ ?>
                                        <?php echo $this->Form->end(); ?>

                                        <?php /* elseif (empty($validErrors))  complete() */ ?>
                                        <?php elseif (empty($validErrors)): ?>

                                        <?php echo $this->Form->create('SaleItem', ['url' => "/sale/item/complete/", 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                                          <label>販売名</label>
                                          <p class="form-control-static"><?php echo $this->Form->data['SaleItem']['title'];?></p>
                                          <label>販売価格</label>
                                          <p class="form-control-static"><?php echo $this->Form->data['SaleItem']['price'];?>円</p>
                <?php echo $this->Form->error('SaleItem.price', null, ['wrap' => 'p']) ?>
                                          <label>商品説明</label>
                                          <p class="form-control-static"><?php echo $this->Form->data['SaleItem']['note'];?></p>
                                          <a class="btn btn-info btn-xs btn-block animsition-link" href="<?php echo Configure::read('site.static_content_url'); ?>/use_agreement/" target="_blank">minikura利用規約</a>
                                          <button type="submit" class="btn btn-danger btn-md btn-block animsition-link" >利用規約に同意して販売する</button>
                                        <?php /* 必要次第 hiddenでid APIでき次第 */ ?>
                                        <?php echo $this->Form->end(); ?>
                                        <?php endif; ?>

                                        <?php echo $this->Form->create('SaleItem', ['url' => "/sale/item/cancel/", 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                                          <button type="submit" class="btn btn-danger btn-xs btn-block animsition-link" >販売をやめる</button>
                                          <?php /* item　表示用 */ ?>
                                          <?php echo $this->Form->hidden('SaleItem.item_id', ['value' => $item['item_id']]); ?>
                                        <?php /* 必要次第 hiddenでid APIでき次第 販売cancel処理用 */ ?>
                                        <?php echo $this->Form->end(); ?>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-12 col-md-12 col-xs-12 item-detail-text">
                            <p class="box_note"><?php echo nl2br(h($item['item_note'])); ?></p>
                          </div>
                        </div>
                      </div>
                      <?php echo $this->element('List/item_footer', ['item' => $item, 'box' => $box]); ?>
                  </div>
                  <!--loop end-->
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block " href="/item/">アイテムの一覧に戻る</a> 
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-danger btn-lg btn-block " href="/item/detail/<?php echo $item['item_id'] ?>/edit">アイテム情報を編集する</a>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
