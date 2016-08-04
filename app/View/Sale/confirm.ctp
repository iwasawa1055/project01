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
                            <div class="col-lg-12 sale-complete-text">
                              この内容で販売情報を変更しますか？
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
                                          <label>販売価格</label>
                                          <p class="form-control-static">***,***,***,***円</p>
                                          <label>商品説明</label>
                                          <p class="form-control-static">これから社員の個性光る記事がどしどし掲載されていく予定ですので、ぜひブックマークしておいてくださいね！ そして今号のメルマガは、立命館大学事例・行政のオープンデータを活用し市民が地域課題に挑む新しい試み「チャレンジ!! オープンガバナンス2016」や各種イベントのお知らせなどなど、盛りだくさんでお届けします。</p>
                                          <a class="btn btn-info btn-xs btn-block animsition-link" href="https://minikura.com/use_agreement/" target="_blank">minikura利用規約</a>
                                          <a class="btn btn-danger btn-md btn-block animsition-link" href="sale_complete.html">利用規約に同意して販売する</a>
                                          <a class="btn btn-danger btn-md btn-block animsition-link" href="sale_complete.html">販売情報を変更する</a>
                                          <a class="btn btn-danger btn-xs btn-block animsition-link" href="detail.html">販売をやめる</a>
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
