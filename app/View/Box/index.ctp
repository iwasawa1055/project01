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
                  <?php
                  $productName = '';
                    if ($product === 'mono') {
                        $productName = 'minikuraMONO';
                    } else if ($product === 'hako') {
                        $productName = 'minikuraHAKO';
                    } else if ($product === 'cleaning') {
                        $productName = 'クリーニングパック';
                    } else
                  ?>
                <h2><?php echo $productName; ?></h2>
                <div class="row box-list">
                  <div class="col-lg-2 col-lg-offset-10">
                    <select class="form-control sort-form">
                      <option>並べ替え</option>
                      <option value="">箱NO（降順）</option>
                      <option value="">箱NO（昇順）</option>
                      <option value="">箱タイトル（降順）</option>
                      <option value="">箱タイトル（昇順）</option>
                      <option value="">個品タイトル（降順）</option>
                      <option value="">個品タイトル（昇順）</option>
                      <option value="">ステータス（降順）</option>
                      <option value="">ステータス（昇順）</option>
                      <option value="">オプション（降順）</option>
                      <option value="">オプション（昇順）</option>
                    </select>
                  </div>
                  <?php foreach ($boxList as $box): ?>
                  <?php $url = '/box/detail/' . $box['box_id']; ?>
                  <!--loop-->
                  <div class="col-lg-12">
                    <div class="panel panel-default">
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-lg-8 col-md-8 col-sm-12">
                            <h3><?php echo $box['box_name'] ?></h3>
                          </div>
                          <div class="col-lg-4 col-md-4 col-xs-12">
                            <a class="btn btn-danger btn-md btn-block btn-detail animsition-link" href="<?php echo $url; ?>">ボックスの内容を確認</a>
                          </div>
                        </div>
                      </div>
                      <div class="panel-footer">
                        <div class="row">
                          <div class="col-lg-10 col-md-10 col-sm-12">
                            <p class="box-list-caption"><span>商品名</span><?php echo $box['product_name'] ?></p>
                            <p class="box-list-caption"><span>ボックスID</span><?php echo $box['box_id'] ?></p>
                          </div>
                          <div class="col-lg-2 col-md-2 col-sm-12">
                            <p class="box-list-caption"><span>入庫日</span><?php echo $box['inbound_date'] ?></p>
                            <p class="box-list-caption"><span>出庫日</span><?php echo $box['outbound_date'] ?></p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php endforeach; ?>
                  <!--loop end-->
                </div>
                <?php echo $this->element('paginator'); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
