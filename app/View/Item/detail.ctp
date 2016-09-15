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
                        <?php /*取出し*/ ?>
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

                        <?php /*ヤフオク*/ ?>
                        <?php if (!empty($linkToAuction)): ?>
                        <span class="col-xs-12 col-lg-12">
                            <?php if (empty($denyOutboundList) && empty($sales)) : ?>
                            <a class="btn btn-yahoo btn-md btn-block btn-detail btn-regist" href="<?php echo $linkToAuction; ?>" target="_blank">ヤフオク!に出品</a>
                            <?php else:?>
                            <button class="btn btn-yahoo btn-md btn-block btn-detail btn-regist" disabled="disabled" >ヤフオク!に出品</button>
                            <p class="error-message"><?php echo __('can_not_yahoo_auction'); ?></p>
                            <?php endif;?>
                        </span>
                        <?php endif; ?>
                      </div>

                      <?php /* sales */?>
                      <?php if(! empty($customer_sales) ) :?>
                          <div class="col-lg-6 col-md-6 col-xs-12 sale">
                            <div class="col-xs-12 col-lg-12">
                              <div class="panel panel-default">
                                <div class=" panel-heading">
                                  <h4>minikuraTRADE</h4>
                                </div>
                                <div class="panel-body">
                                  <div class="row">
                                    <div class="col-lg-12">
                                      <div class="row">
                                        <div class="col-lg-12">
                                        <?php /* 暫定 預け入れ(入庫中)以外はアイテム販売設定できない */ ?>
                                        <?php if ( $item['item_status'] !== (int)BOXITEM_STATUS_INBOUND_DONE ):?>
                                          <p class="error-message">
                                            <?php echo __('can_not_sales'); ?>
                                          </p>

                                        <?php elseif ( empty($sales) ):?>
                                        <?php echo $this->Form->create('Sales', ['url' => "/sale/item/edit/{$item['item_id']}", 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                                          <div class="form-group">
                                            <?php echo $this->Form->input('Sales.sales_title', ['class' => 'form-control', 'placeholder' => '販売名', 'error' => false, 'value' => $session_sales['sales_title']]);?>
                                            <?php echo $this->Form->error('Sales.sales_title', null, ['wrap' => 'p']);?>
                                          </div>
                                          <div class="form-group">
                                            <?php echo $this->Form->input('Sales.price', ['class' => 'form-control', 'placeholder' => '販売価格 (税込)', 'error' => false, 'value' => $session_sales['price']]);?>
                                            <?php echo $this->Form->error('Sales.price', null, ['wrap' => 'p']);?>
                                            <p class="sale-caption">※ご注意事項</p>
                                            <ul class="sale-caption">
                                              <li>金額は1,000円〜50,000円の範囲で設定ください。</li>
                                              <li>送料800円は出品者負担となりますのでご注意ください。</li>
                                              <li>設定金額は税込金額です。</li>
                                            </ul>
                                          </div>
                                          <div class="form-group">
                                            <?php echo $this->Form->textarea('Sales.sales_note', ['class' => 'form-control', 'rows' => 5, 'placeholder' => '商品説明', 'error' => false, 'value' => $session_sales['sales_note']]);?>
                                            <?php echo $this->Form->error('Sales.sales_note', null, ['wrap' => 'p']);?>
                                          </div>
                                          <button type="submit" class="btn btn-danger btn-md btn-block animsition-link" >この内容で確認する</button>
                                          <?php echo $this->Form->hidden('Sales.item_id', ['value' => $item['item_id']]); ?>
                                          <?php echo $this->Form->end(); ?>
                                        <?php endif;?>

                                        <?php /*販売中は 販売を止める&& snsでシェアするボタンを表示  */ ?>
                                        <?php if (! empty($sales['sales_status']) && $sales['sales_status'] === '1'):?>
                                        <?php echo $this->Form->create('Sales', ['url' => "/sale/item/cancel/", 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
                                          <div class="form-group">
                                            <label>販売名</label>
                                            <?php echo h( $sales['sales_title']);?>
                                          </div>
                                          <div class="form-group">
                                            <label>価格</label>
                                            <?php echo number_format(h( floor($sales['price'])));?>円 (税込)
                                          </div>
                                          <div class="form-group">
                                            <label>商品説明</label>
                                            <?php echo nl2br(h( $sales['sales_note']));?>
                                          </div>
                                          <button type="submit" class="btn btn-danger btn-xs btn-block animsition-link" >販売をやめる</button>

                                          <a class="btn btn-block btn-social btn-xs btn-facebook"
                                             href="https://www.facebook.com/sharer/sharer.php?u=<?php echo h($trade_url); ?>&t=" >
                                            <i class="fa fa-facebook"></i>Facebook でシェア
                                          </a>
                                          <a class="btn btn-block btn-social btn-xs btn-twitter"
                                             href="https://twitter.com/share?url=<?php echo h($trade_url); ?>&text=" >
                                            <i class="fa fa-twitter"></i>twitter でシェア
                                          </a>
                                          <?php /* sns貼り付け用 url作成 */ ?>
                                          <input class="form-control" id="copy-sns-url"  value="<?php echo h($trade_url);?>">
                                          <a class="btn btn-danger btn-md btn-copy-sns">リンクをコピー</a>
                                          <input class="form-control" id="copy-tag"  value='<iframe src = "<?php echo h($widget_url);?>" scrolling="no" frameborder="0" height="640"></iframe>'>
                                          <a class="btn btn-danger btn-md btn-copy-tag">タグをコピー</a>
                                          <?php echo $this->Form->hidden('Sales.sales_id', ['value' => $sales['sales_id']]); ?>
                                          <?php echo $this->Form->hidden('Sales.item_id', ['value' => $item['item_id']]); ?>
                                          <?php echo $this->Form->end(); ?>
                                        <?php endif;?>

                                        <?php /*購入中〜送金処理中 */ ?>
                                        <?php if (! empty($sales) && ($sales['sales_status'] >= SALES_STATUS_IN_PURCHASE && $sales['sales_status'] <= SALES_STATUS_REMITTANCE_COMPLETED )):?>
                                          <p class="error-message">
                                            <?php echo __('sales_status_' . $sales['sales_status']); ?>
                                          </p>
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
                      <?php /* sales */?>

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
