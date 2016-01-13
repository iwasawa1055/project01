<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"><i class="fa fa-home"></i> マイページ</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <h2>お知らせ</h2>
              <hr>
              <div class="col-lg-12 index-news">

              <?php foreach ($notice_announcements as $data): ?>
                <?php $url = '/announcement/detail/' . $data['announcement_id']; ?>
                <div class="row">
                  <div class="col-xs-12 col-md-2 col-lg-2">
                      <?php echo $data['date']; ?>
                  </div>
                  <div class="col-xs-12 col-md-9 col-lg-9">
                      <a href="<?php echo $url; ?>" class="animsition-link"><?php echo $data['title']; ?></a>
                  </div>
                  <div class="col-xs-12 col-md-1 col-lg-1">
                    <?php if ($data['read']): ?>
                    <a class="btn btn-success btn-xs animsition-link" href="<?php echo $url; ?>">既読</a>
                    <?php else: ?>
                    <a class="btn btn-danger btn-xs animsition-link" href="<?php echo $url; ?>">未読</a>
                    <?php endif; ?>
                  </div>
                </div>
                <hr>
              <?php endforeach; ?>
                <div class="col-lg-12 col-md-12 col-xs-12 text-center">
                  <a class="btn btn-default btn-lg animsition-link" href="/announcement/">お知らせ一覧を見る</a>
                </div>
                <hr>
              </div>
              <h2>最近預けたアイテム</h2>
              <hr>
              <div class="row box-list">
                <ul class="tile">
                  <!--loop-->
                  <li><a class="animsition-link" href="item/item_detail.html"><img src="img/xxx_xxxx.jpg" alt="xxx_xxxx"></a></li>
                  <li><a class="animsition-link" href="item/item_detail.html"><img src="img/xxx_xxxx.jpg" alt="xxx_xxxx"></a></li>
                  <li><a class="animsition-link" href="item/item_detail.html"><img src="img/xxx_xxxx.jpg" alt="xxx_xxxx"></a></li>
                  <li><a class="animsition-link" href="item/item_detail.html"><img src="img/xxx_xxxx.jpg" alt="xxx_xxxx"></a></li>
                  <!--loop end-->
                </ul>
                <div class="col-lg-12 col-md-12 col-xs-12 text-center"> <a class="btn btn-default btn-lg animsition-link" href="/item">アイテム一覧を見る</a> </div>
              </div>
              <h2>最近預けたボックス</h2>
              <hr>
              <div class="form-group">
                <select class="form-control">
                  <option>xxx-xxxxx</option>
                  <option>xxx-xxxxx</option>
                  <option>xxx-xxxxx</option>
                  <option>xxx-xxxxx</option>
                  <option>xxx-xxxxx</option>
                  <option>xxx-xxxxx</option>
                </select>
              </div>
              <div class="row box-list">
                <!--loop-->
                <hr>
                <div class="row box">
                  <div class="col-lg-2 col-md-2"> <i class="fa fa-cube"></i> </div>
                  <div class="col-lg-2 col-md-2">
                    <label>ボックスID</label>
                    <p class="form-control-static">xxx-xxx </p>
                  </div>
                  <div class="col-lg-4 col-md-4">
                    <label>ボックスタイトル</label>
                    <p class="form-control-static">XXXXXXXXXXXXXXXX</p>
                  </div>
                  <div class="col-lg-4 col-md-4"> <span class="col-xs-12 col-lg-12"> <a class="btn btn-default btn-md btn-block btn-detail animsition-link" href="/box/detail/1">詳細確認</a> </span> </div>
                </div>
                <!--loop end-->
                <!--loop-->
                <hr>
                <div class="row box">
                  <div class="col-lg-2 col-md-2"> <i class="fa fa-cube"></i> </div>
                  <div class="col-lg-2 col-md-2">
                    <label>ボックスID</label>
                    <p class="form-control-static">xxx-xxx </p>
                  </div>
                  <div class="col-lg-4 col-md-4">
                    <label>ボックスタイトル</label>
                    <p class="form-control-static">XXXXXXXXXXXXXXXX</p>
                  </div>
                  <div class="col-lg-4 col-md-4"> <span class="col-xs-12 col-lg-12"> <a class="btn btn-default btn-md btn-block btn-detail animsition-link" href="/box/detail/1">詳細確認</a> </span> </div>
                </div>
                <!--loop end-->
                <!--loop-->
                <hr>
                <div class="row box">
                  <div class="col-lg-2 col-md-2"> <i class="fa fa-cube"></i> </div>
                  <div class="col-lg-2 col-md-2">
                    <label>ボックスID</label>
                    <p class="form-control-static">xxx-xxx </p>
                  </div>
                  <div class="col-lg-4 col-md-4">
                    <label>ボックスタイトル</label>
                    <p class="form-control-static">XXXXXXXXXXXXXXXX</p>
                  </div>
                  <div class="col-lg-4 col-md-4"> <span class="col-xs-12 col-lg-12"> <a class="btn btn-default btn-md btn-block btn-detail animsition-link" href="/box/detail/1">詳細確認</a> </span> </div>
                </div>
                <!--loop end-->
                <div class="col-lg-12 col-md-12 col-xs-12 text-center"> <a class="btn btn-default btn-lg animsition-link" href="/box">ボックス一覧を見る</a> </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
