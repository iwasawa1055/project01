    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> ボックスを取り出す</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <?php echo $this->Form->create('OutboundBox', ['url' => '/outbound/box', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>取り出すボックスを選択</h2>
                <p class="form-control-static col-lg-12">お預かり済みの専用ボックスの一覧です。<br />
                  「取り出しリストに登録」にチェックを入れて「取り出すボックスの確認」にすすんでください。</p>
                <div class="row box-list">
                  <?php foreach ($boxList as $box): ?>
                  <?php
                  $i = $box['box_id'];
                  $url = '/box/detail/' . $box['box_id'];
                  echo $this->Form->hidden("box_list.${i}.box_id", ['value' => $box['box_id']]);
                  ?>
                  <!--loop-->
                  <div class="col-lg-12">
                    <div class="panel panel-default">
                      <div class="panel-body <?php echo $this->MyPage->kitCdToClassName($box['kit_cd']); ?>">
                        <div class="row">
                          <div class="col-lg-8 col-md-8 col-sm-12">
                            <h3><a href="<?php echo $url ?>"><?php echo $box['box_name'] ?></a>
                            </h3>
                          </div>
                          <div class="col-lg-4 col-md-4 col-xs-12 outbound_select_checkbox">
                              <input type="checkbox">
                              <?php echo $this->Form->checkbox("box_list.${i}.checkbox", ['checked' => $box['outbound_list']]); ?>
                              <button class="btn btn-danger btn-md btn-block btn-detail"></button>
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
            <span class="col-lg-12 col-md-12 col-xs-12">
                <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">取り出しリストを確認する</button>
            </span>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
