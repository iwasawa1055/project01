<div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> ボックス預け入れ</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>預け入れボックスを選択</h2>
                <p class="form-control-static col-lg-12">ご購入済みの専用ボックスの一覧です。<br />
                  預け入れるボックスのタイトルを入力してボックスを選択しましたら「預け入れボックスの確認」にすすんでください。</p>
                <div class="row box-list">
                  <?php foreach ($boxList as $box): ?>
                  <!--loop-->
                  <div class="col-lg-12">
                    <div class="panel panel-default">
                      <div class="panel-body <?php echo $this->MyPage->kitCdToClassName($box['kit_cd']); ?>">
                        <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-12">
                            <input class="form-control text-form" placeholder="ボックスタイトルを入力してください">
                          </div>
                          <div class="col-lg-3 col-md-3 col-sm-12">
                            <select class="form-control text-form">
                              <option>オプションをお選びください</option>
                              <option>あんしんオプション</option>
                            </select>
                          </div>
                          <div class="col-lg-3 col-md-3 col-xs-12">
                            <a class="btn btn-danger btn-md btn-block btn-detail animsition-link inbound-btn" href="#">ボックス選択</a>
                          </div>
                        </div>
                      </div>
                      <div class="panel-footer">
                        <div class="row">
                          <div class="col-lg-12 col-md-12 col-sm-12">
                            <p class="box-list-caption"><span>商品名</span><?php echo $box['product_name'] ?></p>
                            <p class="box-list-caption"><span>ボックスID</span><?php echo $box['box_id'] ?></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--loop end-->
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <div class="form-group col-lg-12">
              <label>預け入れ方法</label>
              <select class="form-control">
                <option>以下からお選びください</option>
                <option>ヤマト運輸に集荷依頼する</option>
                <option>日本郵便に集荷依頼する</option>
                <option>自分で発送する</option>
              </select>
            </div>
            <div class="form-group col-lg-12">
              <label>集荷の住所</label>
              <select class="form-control">
                <option>以下からお選びください</option>
                <option>〒000-0000 東京都品川区東品川2-2-33 Nビル 5階　市川　倫之介</option>
                <option>〒000-0000 東京都品川区東品川2-2-33 Nビル 5階　市川　倫之介</option>
                <option>お届先を追加する</option>
              </select>
            </div>
            <div class="form-group col-lg-12">
              <label>集荷の日程</label>
              <select class="form-control">
                <option>00月00日</option>
                <option>00月00日</option>
                <option>00月00日</option>
                <option>00月00日</option>
                <option>00月00日</option>
              </select>
            </div>
            <div class="form-group col-lg-12">
              <label>集荷の時間</label>
              <select class="form-control">
                <option>午前中</option>
                <option>12時〜</option>
                <option>14時〜</option>
                <option>16時〜</option>
                <option>18時〜</option>
              </select>
            </div>
            <span class="col-lg-12 col-md-12 col-xs-12">
            <a class="btn btn-danger btn-lg btn-block animsition-link" href="/inbound/box/confirm">預け入れボックスの確認</a>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
