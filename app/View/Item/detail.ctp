<?php $this->Html->css('lightbox.min', ['block' => 'css']); ?>
<?php $this->Html->script('lightbox.min', ['block' => 'scriptMinikura']); ?>
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-heart-o"></i> アイテム</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <h2>アイテムの詳細</h2>
            <div class="row box-list">
              <!--loop-->
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="item-detail ">
                          <a href="<?php echo $item['image_first']['image_url'] ?>" data-lightbox="item-photo" data-title="<?php echo $item['item_name'] ?>">
                              <img src="<?php echo $item['image_first']['image_url'] ?>" alt="<?php echo $item['item_id'] ?>" width="100px" height="100px" class="item"></a>
                        </div>
                        <h3><?php echo $item['item_name'] ?></h3>
                        <div class="box-list-caption">
                          <!-- <span>カテゴリ</span>スポーツ用品 -->
                        </div>
                        <span class="col-xs-12 col-lg-12">
                            <a class="btn btn-warning btn-md btn-block btn-detail btn-regist disabled">
                                <?php echo __('boxitem_status_' . $item['item_status']); ?>
                            </a>
                        </span>
                        <!--span class="col-xs-12 col-lg-12"><a class="btn btn-warning btn-md btn-block btn-detail btn-regist disabled">出庫済み</a>
                        </span-->
                      </div>
                      <div class="col-lg-6 col-md-6 col-xs-12">
                        <?php if (empty($denyOutboundList)) : ?>
                        <?php echo $this->Form->create(false, ['url' => '/outbound/item', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                        <?php echo $this->Form->hidden("item_id.${item['item_id']}", ['value' => '1']); ?>
                        <span class="col-xs-12 col-lg-12">
                            <button type="submit" class="btn btn-danger btn-md btn-block btn-detail btn-regist">取り出しリスト登録</button>
                        </span>
                        <?php echo $this->Form->end(); ?>
                        <?php endif; ?>
                        <?php if (!empty($linkToAuction)): ?>
                        <span class="col-xs-12 col-lg-12">
                            <a class="btn btn-yahoo btn-md btn-block btn-detail btn-regist" href="<?php echo $linkToAuction; ?>">ヤフオク!に出品</a>
                        </span>
                        <?php endif; ?>
                      </div>
                      <div class="col-lg-12 col-md-12 col-xs-12 item-detail-text">
                        <p class="box_note"><?php echo nl2br($item['item_note']); ?></p>
                      </div>
                    </div>
                  </div>
                  <?php echo $this->element('List/item_footer', ['item' => $item, 'box' => $box]); ?>
                </div>
                <!--loop end-->
              </div>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
            <a class="btn btn-primary btn-lg btn-block " href="/item/">アイテムの一覧に戻る</a>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
            <a class="btn btn-danger btn-lg btn-block " href="/item/detail/<?php echo $item['item_id'] ?>/edit">アイテム情報を編集する</a>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
