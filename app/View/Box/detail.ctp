    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-tag"></i> ご利用中のサービス</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2><?php echo $box['product_name']; ?></h2>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="panel panel-default">
                <div class="panel-body">
                  <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12">
                      <h3><?php echo $box['box_name']; ?></h3>
                      <div class="box-list-caption">
                        <span>写真撮影</span>あり
                      </div>
                      <span class="col-xs-12 col-lg-12"><a class="btn btn-warning btn-md btn-block btn-detail btn-regist disabled">預け入れ中</a>
                      </span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12">
                      <span class="col-xs-12 col-lg-12"><a class="btn btn-danger btn-md btn-block btn-detail btn-regist animsition-link" href="../outbound/index.html">取り出しリスト登録</a>
                      </span>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                      <p class="box_note"><?php echo $box['box_note']; ?></p>
                    </div>
                  </div>
                </div>
                <div class="panel-footer">
                  <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-12">
                      <p class="box-list-caption"><span>商品名</span><?php echo $box['product_name']; ?></p>
                      <p class="box-list-caption"><span>ボックスID</span><?php echo $box['box_id']; ?></p>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                      <p class="box-list-caption"><span>入庫日</span><?php echo $box['inbound_date']; ?></p>
                      <p class="box-list-caption"><span>出庫日</span><?php echo $box['outbound_date']; ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-12">
              <h3>ボックスの内容</h3>
              <ul class="tile">
                <!--loop-->
                <?php foreach($itemList as $item):  ?>
                <li class="panel panel-default">
                    <a class="animsition-link" href="/item/detail/<?php echo $item['item_id'] ?>">
                        <img src="<?php echo $item['images_item']['image_url'] ?>" alt="<?php echo $item['item_id'] ?>"></a>
                  <div class="panel-footer">
                    <p class="box-list-caption"><span>アイテム名</span><?php echo $item['item_name'] ?></p>
                    <p class="box-list-caption"><span>アイテムID</span><?php echo $item['item_id'] ?></p>
                  </div>
                </li>
                <?php endforeach; ?>
                <!--loop end-->
              </ul>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-primary btn-lg btn-block animsition-link" href="/box">ボックスの一覧に戻る</a>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-danger btn-lg btn-block animsition-link" href="/box/detail/<?php echo $box['box_id']; ?>/edit">ボックス情報を編集する</a>
            </span>
          </div>
        </div>
      </div>
    </div>
