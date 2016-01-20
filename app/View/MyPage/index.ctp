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
              <div class="col-lg-12">
                <div class="col-lg-12 announcement">

                <?php foreach ($notice_announcements as $data): ?>
                  <?php $url = '/announcement/detail/' . $data['announcement_id']; ?>
                  <div class="row list">
                    <div class="col-xs-12 col-md-3 col-lg-3">
                      <?php echo $data['date']; ?>
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
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <h2>最近預けたアイテム</h2>
              <div class="form-group col-lg-12">
                <ul class="tile">
                  <!--loop-->
                  <li class="panel panel-default"><a class="animsition-link" href="item/detail.html"><img src="img/xxx_xxxx.jpg" alt="xxx_xxxx"></a>
                    <div class="panel-footer">
                      <p class="box-list-caption"><span>アイテム名</span>S.H.Figuarts ファースト・オー…</p>
                      <p class="box-list-caption"><span>アイテムID</span>xxx-xxx</p>
                    </div>
                  </li>
                  <li class="panel panel-default"><a class="animsition-link" href="item/detail.html"><img src="img/xxx_xxxx.jpg" alt="xxx_xxxx"></a>
                    <div class="panel-footer">
                      <p class="box-list-caption"><span>アイテム名</span>S.H.Figuarts ファースト・オー…</p>
                      <p class="box-list-caption"><span>アイテムID</span>xxx-xxx</p>
                    </div>
                  </li>
                  <li class="panel panel-default"><a class="animsition-link" href="item/detail.html"><img src="img/xxx_xxxx.jpg" alt="xxx_xxxx"></a>
                    <div class="panel-footer">
                      <p class="box-list-caption"><span>アイテム名</span>S.H.Figuarts ファースト・オー…</p>
                      <p class="box-list-caption"><span>アイテムID</span>xxx-xxx</p>
                    </div>
                  </li>
                  <li class="panel panel-default"><a class="animsition-link" href="item/detail.html"><img src="img/xxx_xxxx.jpg" alt="xxx_xxxx"></a>
                    <div class="panel-footer">
                      <p class="box-list-caption"><span>アイテム名</span>S.H.Figuarts ファースト・オー…</p>
                      <p class="box-list-caption"><span>アイテムID</span>xxx-xxx</p>
                    </div>
                  </li>
                  <li class="panel panel-default"><a class="animsition-link" href="item/detail.html"><img src="img/xxx_xxxx.jpg" alt="xxx_xxxx"></a>
                    <div class="panel-footer">
                      <p class="box-list-caption"><span>アイテム名</span>S.H.Figuarts ファースト・オー…</p>
                      <p class="box-list-caption"><span>アイテムID</span>xxx-xxx</p>
                    </div>
                  </li>
                  <li class="panel panel-default"><a class="animsition-link" href="item/detail.html"><img src="img/xxx_xxxx.jpg" alt="xxx_xxxx"></a>
                    <div class="panel-footer">
                      <p class="box-list-caption"><span>アイテム名</span>S.H.Figuarts ファースト・オー…</p>
                      <p class="box-list-caption"><span>アイテムID</span>xxx-xxx</p>
                    </div>
                  </li>
                  <li class="panel panel-default"><a class="animsition-link" href="item/detail.html"><img src="img/xxx_xxxx.jpg" alt="xxx_xxxx"></a>
                    <div class="panel-footer">
                      <p class="box-list-caption"><span>アイテム名</span>S.H.Figuarts ファースト・オー…</p>
                      <p class="box-list-caption"><span>アイテムID</span>xxx-xxx</p>
                    </div>
                  </li>
                  <li class="panel panel-default"><a class="animsition-link" href="item/detail.html"><img src="img/xxx_xxxx.jpg" alt="xxx_xxxx"></a>
                    <div class="panel-footer">
                      <p class="box-list-caption"><span>アイテム名</span>S.H.Figuarts ファースト・オー…</p>
                      <p class="box-list-caption"><span>アイテムID</span>xxx-xxx</p>
                    </div>
                  </li>
                  <!--loop end-->
                </ul>
                <div class="col-lg-12 col-md-12 col-xs-12">
                  <a class="btn btn-info btn-md animsition-link pull-right" href="/item">アイテム一覧を見る</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <h2>最近預けたボックス</h2>
              <div class="row box-list">
                <!--loop-->
                <div class="col-lg-12">
                  <div class="panel panel-default">
                    <div class="panel-body mono-box">
                      <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12">
                          <h3><a href="mono/detail.html">79期見積書79期見積書79…</a>
                          </h3>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12">
                          <a class="btn btn-danger btn-md btn-block btn-detail animsition-link" href="mono/detail.html">詳細を確認する</a>
                        </div>
                      </div>
                    </div>
                    <div class="panel-footer">
                      <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-12">
                          <p class="box-list-caption"><span>商品名</span>minikuraMONO</p>
                          <p class="box-list-caption"><span>ボックスID</span>xxx-xxx</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                          <p class="box-list-caption"><span>入庫日</span>0000/00/00</p>
                          <p class="box-list-caption"><span>出庫日</span>0000/00/00</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--loop end-->
                <!--loop-->
                <div class="col-lg-12">
                  <div class="panel panel-default">
                    <div class="panel-body mono-box">
                      <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12">
                          <h3><a href="mono/detail.html">79期見積書79期見積書79…</a>
                          </h3>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12">
                          <a class="btn btn-danger btn-md btn-block btn-detail animsition-link" href="mono/detail.html">詳細を確認する</a>
                        </div>
                      </div>
                    </div>
                    <div class="panel-footer">
                      <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-12">
                          <p class="box-list-caption"><span>商品名</span>minikuraMONO</p>
                          <p class="box-list-caption"><span>ボックスID</span>xxx-xxx</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                          <p class="box-list-caption"><span>入庫日</span>0000/00/00</p>
                          <p class="box-list-caption"><span>出庫日</span>0000/00/00</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--loop end-->
                <!--loop-->
                <div class="col-lg-12">
                  <div class="panel panel-default">
                    <div class="panel-body hako-box">
                      <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12">
                          <h3><a href="hako/detail.html">夏服①②夏服①②夏服①②夏服①…</a>
                          </h3>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12">
                          <a class="btn btn-danger btn-md btn-block btn-detail animsition-link" href="hako/detail.html">詳細を確認する</a>
                        </div>
                      </div>
                    </div>
                    <div class="panel-footer">
                      <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-12">
                          <p class="box-list-caption"><span>商品名</span>minikuraHAKO</p>
                          <p class="box-list-caption"><span>ボックスID</span>xxx-xxx</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                          <p class="box-list-caption"><span>入庫日</span>0000/00/00</p>
                          <p class="box-list-caption"><span>出庫日</span>0000/00/00</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--loop end-->
                <!--loop-->
                <div class="col-lg-12">
                  <div class="panel panel-default">
                    <div class="panel-body hako-box">
                      <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12">
                          <h3><a href="hako/detail.html">夏服①②夏服①②夏服①②夏服①…</a>
                          </h3>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12">
                          <a class="btn btn-danger btn-md btn-block btn-detail animsition-link" href="hako/detail.html">詳細を確認する</a>
                        </div>
                      </div>
                    </div>
                    <div class="panel-footer">
                      <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-12">
                          <p class="box-list-caption"><span>商品名</span>minikuraHAKO</p>
                          <p class="box-list-caption"><span>ボックスID</span>xxx-xxx</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                          <p class="box-list-caption"><span>入庫日</span>0000/00/00</p>
                          <p class="box-list-caption"><span>出庫日</span>0000/00/00</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--loop end-->
                <!--loop-->
                <div class="col-lg-12">
                  <div class="panel panel-default">
                    <div class="panel-body cleaning-box">
                      <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12">
                          <h3><a href="cleaning/detail.html">衣類（箱のタイトルが入ります）…</a>
                          </h3>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-12">
                          <a class="btn btn-danger btn-md btn-block btn-detail animsition-link" href="cleaning/detail.html">詳細を確認する</a>
                        </div>
                      </div>
                    </div>
                    <div class="panel-footer">
                      <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-12">
                          <p class="box-list-caption"><span>商品名</span>クリーニングパック</p>
                          <p class="box-list-caption"><span>ボックスID</span>xxx-xxx</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                          <p class="box-list-caption"><span>入庫日</span>0000/00/00</p>
                          <p class="box-list-caption"><span>出庫日</span>0000/00/00</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--loop end-->
                <div class="col-lg-12 col-md-12 col-xs-12">
                  <a class="btn btn-info btn-md animsition-link pull-right" href="/box">ボックス一覧を見る</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
