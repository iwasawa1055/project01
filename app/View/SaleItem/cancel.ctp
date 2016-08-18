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
                              販売を中止しました
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
