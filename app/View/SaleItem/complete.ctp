<?php $this->Html->script('minikura/sns', ['block' => 'scriptMinikura']); ?>
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
                              この内容で販売を開始しました
                            </div>
                          </div>
                          <div class="col-lg-6 col-md-6 col-xs-12 sale">
                            <div class="col-xs-12 col-lg-12">
                              <div class="panel panel-default">
                                <div class=" panel-heading">
                                  <h4>minikuraTRADE</h4>
                                </div>
                                <div class="panel-body">
                                  <div class="row">
                                    <div class="col-lg-12">
                                      <div class="row">
                                        <div class="col-lg-12">
                                          <label>販売名</label>
                                          <p class="form-control-static"><?php echo h($sales['sales_title']);?></p>
                                          <label>販売価格</label>
                                          <p class="form-control-static"><?php echo h(floor($sales['price']));?>円(税込)</p>
                                          <label>商品説明</label>
                                          <p class="form-control-static"><?php echo nl2br(h($sales['sales_note']));?></p>
                                          <?php /* sns */ ?>
                                          <a class="btn btn-block btn-social btn-xs btn-facebook"
                                             href="https://www.facebook.com/sharer/sharer.php?u=<?php echo h($market_url); ?>&t=" >
                                            <i class="fa fa-facebook"></i>Facebook でシェア
                                          </a>
                                          <a class="btn btn-block btn-social btn-xs btn-twitter"
                                             href="https://twitter.com/share?url=<?php echo h($market_url); ?>&text=" >
                                            <i class="fa fa-twitter"></i>twitter でシェア
                                          </a>
                                          <input class="form-control" id="copy-sns-url"  value="<?php echo h($market_url); ?>">
                                          <a class="btn btn-danger btn-md btn-copy-sns">リンクをコピー</a>
                                          <input class="form-control" id="copy-tag"  value='<iframe src = "<?php echo h($market_url);?>"></iframe>'>
                                          <a class="btn btn-danger btn-md btn-copy-tag">タグをコピー</a>

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
