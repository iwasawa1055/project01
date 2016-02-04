<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i>取り出し</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
    <?php echo $this->Form->create('Outbound', ['url' => '/outbound/confirm', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
      <div class="panel-body">
        <div class="row">
        <div class="col-lg-12">
          <h2>取り出すアイテム</h2>
          <div class="row box-list">
            <?php foreach ($itemList as $item): ?>
            <?php
            $i = $item['item_id'];
            $url = '/item/detail/' . $item['item_id'];
            echo $this->Form->hidden("item_list.${i}.item_id", ['value' => $item['item_id']]);
            ?>
            <!--loop-->
            <div class="col-lg-12">
              <div class="panel panel-default">
                <div class="panel-body <?php echo $this->MyPage->kitCdToClassName($item['kit_cd']); ?>">
                  <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-12">
                      <a href="<?php echo $url ?>">
                        <img src="<?php echo $item['images_item']['image_url'] ?>" alt="<?php echo $item['item_id'] ?>" width="100px" height="100px" class="item">
                      </a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <h3><a href="<?php echo $url ?>"><?php echo $item['item_name'] ?></a>
                      </h3>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 outbound_select_checkbox">
                        <input type="checkbox">
                        <?php echo $this->Form->checkbox("item_list.${i}.checkbox", ['checked' => 'checked']); ?>
                        <button class="btn btn-danger btn-md btn-block btn-detail"></button>
                    </div>
                  </div>
                </div>
                <div class="panel-footer">
                  <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-12">
                      <p class="box-list-caption"><span>収納ボックスID</span><?php echo $item['box_id']; ?></p>
                      <p class="box-list-caption"><span>アイテムID</span><?php echo $item['item_name']; ?></p>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-12">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!--loop end-->
            <?php endforeach; ?>
          </div>
        </div>
          <div class="col-lg-12">
            <h2>取り出すボックス</h2>
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
                          <?php echo $this->Form->checkbox("box_list.${i}.checkbox", ['checked' => 'checked']); ?>
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
        <div class="form-group col-lg-12">
          <label>お届け先住所</label>
          <?php echo $this->Form->select("Outbound.address_id", $this->order->setAddress($addressList), ['class' => 'form-control', 'empty' => '以下からお選びください', 'error' => false]); ?>
          <?php echo $this->Form->error("Outbound.address_id", null, ['wrap' => 'p']) ?>
        </div>
        <div class="form-group col-lg-12">
          <label>お届け希望日と時間帯</label>
          <?php echo $this->Form->select("Outbound.datetime_cd", $this->order->setDatetime($dateItemList, 'datetime_cd', 'text'), ['class' => 'form-control', 'empty' => false, 'error' => false]); ?>
          <?php echo $this->Form->error("Outbound.datetime_cd", null, ['wrap' => 'p']) ?>
        </div>
        <span class="col-lg-6 col-md-6 col-xs-6">
        <a class="btn btn-primary btn-lg btn-block" href="/outbound/item">アイテムを選択に戻る</a>
        </span>
        <span class="col-lg-6 col-md-6 col-xs-6">
        <a class="btn btn-primary btn-lg btn-block" href="/outbound/box">ボックスを選択に戻る</a>
        </span>
        <span class="col-lg-12 col-md-12 col-xs-12">
            <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">この内容で確認する</button>
        </span>
      </div>
      <?php echo $this->Form->end(); ?>
    </div>
  </div>
</div>
