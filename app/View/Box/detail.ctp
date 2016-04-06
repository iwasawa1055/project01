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
                <h2><?php echo h($box['product_name']); ?></h2>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="panel panel-default">
                <div class="panel-body">
                  <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-12">
                      <h3 class="boxitem-name"><?php echo h($box['box_name']); ?></h3>
                      <div class="box-list-caption">
                        <!-- <span>写真撮影</span>あり -->
                      </div>
                      <span class="col-xs-12 col-lg-12">
                          <a class="btn btn-warning btn-md btn-block btn-detail btn-regist disabled">
                              <?php echo __('boxitem_status_' . $box['box_status']); ?>
                          </a>
                      </span>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12">
                      <?php if (empty($denyOutboundList)) : ?>
                      <?php echo $this->Form->create(false, ['url' => '/outbound/box', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                      <?php echo $this->Form->hidden("box_id.${box['box_id']}", ['value' => '1']); ?>
                      <span class="col-xs-12 col-lg-12">
                          <button type="submit" class="btn btn-danger btn-md btn-block btn-detail btn-regist">取り出しリスト登録</button>
                      </span>
                      <?php echo $this->Form->end(); ?>
                      <?php else : ?>
                        <span class="col-xs-12 col-lg-12">
                          <button type="submit" class="btn btn-danger btn-md btn-block btn-detail btn-regist" disabled="disabled">取り出しリスト登録</button>
                          <p class="error-message"><?php echo $denyOutboundList; ?></p>
                        </span>
                      <?php endif; ?>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12">
                      <p class="box_note"><?php echo nl2br(h($box['box_note'])); ?></p>
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
                  <?php echo $this->element('List/item_icon_body', ['item' => $item]); ?>
                  <?php echo $this->element('List/item_icon_footer', ['item' => $item]); ?>
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
