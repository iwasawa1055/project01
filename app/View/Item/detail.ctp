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
                          <a href="" data-lightbox="item-photo" data-title="<?php echo $item['item_name'] ?>">
                              <img src="<?php echo $item['images_item']['image_url'] ?>" alt="<?php echo $item['item_id'] ?>" width="100px" height="100px" class="item"></a>
                        </div>
                        <h3><?php echo $item['item_name'] ?></h3>
                        <div class="box-list-caption">
                          <span>カテゴリ</span>スポーツ用品
                        </div>
                        <span class="col-xs-12 col-lg-12"><a class="btn btn-warning btn-md btn-block btn-detail btn-regist disabled">預け入れ中</a>
                        </span>
                        <!--span class="col-xs-12 col-lg-12"><a class="btn btn-warning btn-md btn-block btn-detail btn-regist disabled">出庫済み</a>
                        </span-->
                      </div>
                      <div class="col-lg-6 col-md-6 col-xs-12">
                        <span class="col-xs-12 col-lg-12"><a class="btn btn-danger btn-md btn-block btn-detail btn-regist " href="../outbound/index.html">取り出しリストに登録</a>
                        </span>

                        <span class="col-xs-12 col-lg-12">
                            <a class="btn btn-yahoo btn-md btn-block btn-detail btn-regist " href="../outbound/index.html">ヤフオク!に出品</a>
                        </span>
                      </div>
                      <div class="col-lg-12 col-md-12 col-xs-12 item-detail-text">
                        <p class="box_note"><?php echo $box['box_note']; ?></p>
                      </div>
                    </div>
                  </div>
                  <div class="panel-footer">
                    <div class="row">
                      <div class="col-lg-10 col-md-10 col-sm-12">
                        <p class="box-list-caption"><span>アイテムID</span><?php echo $item['item_name'] ?></p>
                        <p class="box-list-caption"><span>ボックスID</span><?php echo $item['item_id'] ?></p>
                      </div>
                      <div class="col-lg-2 col-md-2 col-sm-12">
                        <p class="box-list-caption"><span>入庫日</span><?php echo $box['inbound_date']; ?></p>
                        <p class="box-list-caption"><span>出庫日</span><?php echo $box['outbound_date']; ?></p>
                      </div>
                    </div>
                  </div>
                </div>
                <!--loop end-->
              </div>
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
