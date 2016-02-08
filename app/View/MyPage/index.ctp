  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"><i class="fa fa-home"></i> マイページ</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 col-xs-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <h2>お知らせ</h2>
          <div class="col-lg-12">
            <div class="col-lg-12 announcement">
            <?php foreach ($notice_announcements as $data): ?>
              <?php $url = '/announcement/detail/' . $data['announcement_id']; ?>
              <div class="row list">
                <div class="col-xs-12 col-md-3 col-lg-3">
                  <?php echo $this->Html->formatYmdKanji($data['date']); ?>
                </div>
                <div class="col-xs-12 col-md-8 col-lg-8">
                  <span class="detail"><a href="<?php echo $url; ?>" class="animsition-link"><?php echo $data['title']; ?></a></span>
                </div>
                <div class="col-xs-12 col-md-1 col-lg-1">
                <?php if ($data['read']): ?>
                  <a class="btn btn-success btn-xs animsition-link" href="<?php echo $url; ?>">既読</a>
                <?php else: ?>
                  <a class="btn btn-danger btn-xs animsition-link" href="<?php echo $url; ?>">未読</a>
                <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
            </div>
          </div>
          <div class="col-lg-12 col-md-12 col-xs-12">
            <a class="btn btn-info btn-md animsition-link pull-right" href="/announcement/">お知らせ一覧を見る</a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12 col-xs-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <h2>最近預けたアイテム</h2>
          <div class="col-lg-12">
            <div class="col-lg-12 announcement">
              <ul class="tile">
            <?php foreach ($itemList as $item): ?>
                <?php $url = '/item/detail/' . $item['item_id']; ?>
                <!--loop-->
                <li class="panel panel-default"><a class="animsition-link" href="<?php echo $url; ?>">
                    <img src="<?php echo $item['images_item']['image_url']; ?>" alt="<?php echo $item['item_name']; ?>">
                </a>
                  <div class="panel-footer">
                    <p class="box-list-caption"><span>アイテム名</span><?php echo $item['item_name']; ?></p>
                    <p class="box-list-caption"><span>アイテムID</span><?php echo $item['item_id']; ?></p>
                  </div>
                </li>
                <!--loop end-->
            <?php endforeach; ?>
              </ul>
              <div class="col-lg-12 col-md-12 col-xs-12">
                <a class="btn btn-info btn-md animsition-link pull-right" href="/item">アイテム一覧を見る</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12 col-xs-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <h2>最近預けたボックス</h2>
          <div class="col-lg-12">
            <div class="row box-list">
              <?php foreach ($boxList as $box): ?>
                <?php $url = '/box/detail/' . $box['box_id']; ?>
                <!--loop-->
                <div class="col-lg-12">
                  <div class="panel panel-default">
                    <div class="panel-body <?php echo $this->MyPage->kitCdToClassName($box['kit_cd']); ?>">
                      <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12">
                          <h3><a href="<?php echo $url; ?>"><?php echo $box['box_name']; ?></a>
                          </h3>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12">
                          <a class="btn btn-danger btn-md btn-block btn-detail animsition-link" href="<?php echo $url; ?>">詳細を確認する</a>
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
                <!--loop end-->
              <?php endforeach; ?>
              <div class="col-lg-12 col-md-12 col-xs-12">
                <a class="btn btn-info btn-md animsition-link pull-right" href="/box">ボックス一覧を見る</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
