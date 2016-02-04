    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> 取り出し</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <?php echo $this->Form->create('Outbound', ['url' => '/outbound/complete', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>取り出すボックス</h2>
                <div class="row box-list">
                  <?php foreach ($boxList as $box): ?>
                  <?php $url = '/box/detail/' . $box['box_id']; ?>
                  <!--loop-->
                  <div class="col-lg-12">
                    <div class="panel panel-default">
                      <div class="panel-body <?php echo $this->MyPage->kitCdToClassName($box['kit_cd']); ?>">
                        <div class="row">
                          <div class="col-lg-8 col-md-8 col-sm-12">
                            <h3><a href="<?php echo $url ?>"><?php echo $box['box_name'] ?></a>
                            </h3>
                          </div>
                          <div class="col-lg-4 col-md-4 col-xs-12">
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
                  <!--loop end-->
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <div class="form-group col-lg-12">
              <label>お届け先住所</label>
              <p class="form-control-static"><?php echo $address_text; ?></p>
            </div>
            <div class="form-group col-lg-12">
              <label>お届け希望日時</label>
              <p class="form-control-static"><?php echo $datetime; ?></p>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
            <a class="btn btn-primary btn-lg btn-block" href="/outbound/?back=true">戻る</a>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
            <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">この内容で取り出す</button>
            </span>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
