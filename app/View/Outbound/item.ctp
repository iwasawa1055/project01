    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> アイテムを取り出す</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <?php echo $this->Form->create('OutboundBox', ['url' => '/outbound/item', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>取り出すアイテムを選択</h2>
                <p class="form-control-static col-lg-12">選択した専用ボックスに収納されているアイテムの一覧です。<br />
                  取り出すアイテムにチェックを入れて「取り出しリストを確認する」にすすんでください。</p>
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
                      <div class="panel-body">
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
                              <?php echo $this->Form->checkbox("item_list.${i}.checkbox", ['checked' => $item['outbound_list']]); ?>
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
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-primary btn-lg btn-block" href="/outbound/itembox">ボックス一覧に戻る</a>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
                <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">取り出しリストを確認する</button>
            </span>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>