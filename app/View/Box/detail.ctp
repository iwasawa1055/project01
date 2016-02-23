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
                      <span class="col-xs-12 col-lg-12">
                          <a class="btn btn-warning btn-md btn-block btn-detail btn-regist disabled">預け入れ中</a>
                      </span>
                    </div>
                    <?php echo $this->Form->create(false, ['url' => '/outbound/box', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                    <?php echo $this->Form->hidden("box_id.${box['box_id']}", ['value' => '1']); ?>
                    <div class="col-lg-4 col-md-4 col-xs-12">
                      <span class="col-xs-12 col-lg-12">
                          <button type="submit" class="btn btn-danger btn-md btn-block btn-detail btn-regist">取り出しリスト登録</button>
                      </span>
                    </div>
                    <?php echo $this->Form->end(); ?>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                      <p class="box_note"><?php echo $box['box_note']; ?></p>
                    </div>
                  </div>
                </div>
                <?php echo $this->element('List/box_footer', ['box' => $box]); ?>
              </div>
            </div>

            <?php if ($box['product_cd'] === PRODUCT_CD_MONO || $box['product_cd'] === PRODUCT_CD_CLEANING_PACK): ?>
            <div class="col-lg-12">
              <h3>ボックスの内容</h3>
              <ul class="tile">
                <!--loop-->
                <?php foreach($itemList as $item): ?>
                <li class="panel panel-default">
                    <a href="/item/detail/<?php echo $item['item_id'] ?>">
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
            <?php endif; ?>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-primary btn-lg btn-block" href="/box">ボックスの一覧に戻る</a>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-danger btn-lg btn-block" href="/box/detail/<?php echo $box['box_id']; ?>/edit">ボックス情報を編集する</a>
            </span>
          </div>
        </div>
      </div>
    </div>
