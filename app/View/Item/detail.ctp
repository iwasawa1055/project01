<?php $this->Html->css('lightbox.min', ['block' => 'css']); ?>
<?php $this->Html->script('lightbox.min', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/sns', ['block' => 'scriptMinikura']); ?>
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
                      <?php if(! empty($sale) && $sale['setting'] === 'on'):?>
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
                                        <?php /*test*/ $sale_item['sale_test_flg'] = 1;?>

                                        <?php if (!$sale_item['sale_test_flg']):?>
                                        <?php echo $this->Form->create('SaleItem', ['url' => "/sale/item/edit/{$item['item_id']}", 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                                          <div class="form-group">
                                            <?php echo $this->Form->input('SaleItem.title', ['class' => 'form-control', 'placeholder' => '販売名', 'error' => false]);?>
                                            <?php echo $this->Form->error('SaleItem.title', null, ['wrap' => 'p']);?>
                                          </div>
                                          <div class="form-group">
                                            <?php echo $this->Form->input('SaleItem.price', ['class' => 'form-control', 'placeholder' => '販売価格', 'error' => false]);?>
                                            <?php echo $this->Form->error('SaleItem.price', null, ['wrap' => 'p']);?>
                                          </div>
                                          <div class="form-group">
                                            <?php echo $this->Form->textarea('SaleItem.note', ['class' => 'form-control', 'rows' => 5, 'placeholder' => '商品説明', 'error' => false]);?>
                                            <?php echo $this->Form->error('SaleItem.note', null, ['wrap' => 'p']);?>
                                          </div>
                                          <?php /*販売中かstatus確認して、新規かキャンセルか分岐、 編集不可*/ ?>
                                          
                                          <button type="submit" class="btn btn-danger btn-md btn-block animsition-link" >この内容で確認する</button>
                                          <?php echo $this->Form->hidden('SaleItem.item_id', ['value' => $item['item_id']]); ?>
                                          <?php echo $this->Form->end(); ?>
                                        <?php endif;?>

                                        <?php /*販売中かstatus確認して、snsでシェアするボタンを表示  todo Form->inputではなく、DBの値をreadonly  */ ?>
                                        <?php if (! empty($sale_item['sale_test_flg'])):?>
                                        <?php echo $this->Form->create('SaleItem', ['url' => "/sale/item/cancel/{$item['item_id']}", 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                                          <div class="form-group">
                                            <?php echo $this->Form->input('SaleItem.title', ['class' => 'form-control', 'placeholder' => '販売名', 'error' => false]);?>
                                            <?php echo $this->Form->error('SaleItem.title', null, ['wrap' => 'p']);?>
                                          </div>
                                          <div class="form-group">
                                            <?php echo $this->Form->input('SaleItem.price', ['class' => 'form-control', 'placeholder' => '販売価格', 'error' => false]);?>
                                            <?php echo $this->Form->error('SaleItem.price', null, ['wrap' => 'p']);?>
                                          </div>
                                          <div class="form-group">
                                            <?php echo $this->Form->textarea('SaleItem.note', ['class' => 'form-control', 'rows' => 5, 'placeholder' => '商品説明', 'error' => false]);?>
                                            <?php echo $this->Form->error('SaleItem.note', null, ['wrap' => 'p']);?>
                                          </div>
                                          <button type="submit" class="btn btn-danger btn-xs btn-block animsition-link" >販売をやめる</button>

                                          <?php $url = "https://minikura.com"; ?>
                                          <a class="btn btn-block btn-social btn-xs btn-facebook"
                                             href="https://www.facebook.com/sharer/sharer.php?u=<?php echo h($url); ?>&t=" >
                                            <i class="fa fa-facebook"></i>Facebook でシェア
                                          </a>
                                          <?php $url = null; ?>
                                          <a class="btn btn-block btn-social btn-xs btn-twitter"
                                             href="https://twitter.com/share?url=<?php echo h($url); ?>&text=" >
                                            <i class="fa fa-twitter"></i>twitter でシェア
                                          </a>
                                          <?php /* sns貼り付け用 url作成 */ ?>
                                          <input class="form-control" id="copy-sns-url"  value="http://mock23.minikura.com/item/detail.html">
                                          <a class="btn btn-danger btn-md btn-copy-sns">リンクをコピー</a>
                                          <?php echo $this->Form->hidden('SaleItem.item_id', ['value' => $item['item_id']]); ?>
                                          <?php echo $this->Form->end(); ?>
                                        <?php endif;?>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
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
