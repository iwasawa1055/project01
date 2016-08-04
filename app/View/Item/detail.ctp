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
                          <?php if (!empty(Hash::get($item, 'image_first.image_url'))) : ?>
                          <a href="<?php echo Hash::get($item, 'image_first.image_url'); ?>" data-lightbox="item-photo" data-title="<?php echo h($item['item_name']); ?>">
                              <img src="<?php echo Hash::get($item, 'image_first.image_url'); ?>" alt="<?php echo $item['item_id']; ?>" width="100px" height="100px" class="item"></a>
                          <?php endif; ?>
                        </div>
                        <h3 class="boxitem-name"><?php echo h($item['item_name']); ?></h3>
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
                        <?php else : ?>
                        <span class="col-xs-12 col-lg-12">
                            <button type="submit" class="btn btn-danger btn-md btn-block btn-detail btn-regist" disabled="disabled">取り出しリスト登録</button>
                            <p class="error-message"><?php echo $denyOutboundList; ?></p>
                        </span>
                        <?php endif; ?>
                        <?php if (!empty($linkToAuction)): ?>
                        <span class="col-xs-12 col-lg-12">
                            <a class="btn btn-yahoo btn-md btn-block btn-detail btn-regist" href="<?php echo $linkToAuction; ?>" target="_blank">ヤフオク!に出品</a>
                        </span>
                        <?php endif; ?>
                      </div>

                      <?php /* test item sale*/?>
                      <?php if (!empty($validErrors)) { $this->validationErrors['Sale'] = $validErrors; debug($validErrors); } ?>
                      <?php if(! empty($sale) && $sale['setting'] === 'on'):?>
                      <?php echo $this->Form->create('Sale', ['url' => '/sale/item/confirm', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                          <div class="col-lg-6 col-md-6 col-xs-12 sale">
                            <div class="col-xs-12 col-lg-12">
                              <div class="panel panel-default">
                                <div class=" panel-heading">
                                  <h4>アイテム販売</h4>
                                </div>
                                <div class="panel-body">
                                  <div class="row">
                                    <div class="col-lg-12">
                                      <div class="row">
                                        <div class="col-lg-12">
                                          <?php echo $this->Form->input('Sale.price', ['class' => 'form-control', 'placeholder' => '販売価格', 'error' => false]);?>
                                          <?php echo $this->Form->error('Sale.price', null, ['wrap' => 'p']);?>
                                          <textarea class="form-control" rows="5" placeholder="商品説明"></textarea>
                                          <button type="submit" class="btn btn-danger btn-md btn-block animsition-link" >この内容で確認する（開始）</button>
                                          <button type="submit" class="btn btn-danger btn-md btn-block animsition-link" >この内容で確認する（変更）</button>
                                          <a class="btn btn-block btn-social btn-xs btn-facebook"><i class="fa fa-facebook"></i>Facebook でシェア</a>
                                          <a class="btn btn-block btn-social btn-xs btn-twitter"><i class="fa fa-twitter"></i>twitter でシェア</a>
                                          <input class="form-control" value="http://mock23.minikura.com/item/detail.html">
                                          <a class="btn btn-danger btn-md">リンクをコピー</a>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                      <?php echo $this->Form->hidden('Sale.item_id', ['value' => $item['item_id']]); ?>
                      <?php echo $this->Form->end(); ?>
                      <?php endif;?>
                      <?php /* test item sale*/?>

                      <div class="col-lg-12 col-md-12 col-xs-12 item-detail-text">
                        <p class="box_note"><?php echo nl2br(h($item['item_note'])); ?></p>
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
