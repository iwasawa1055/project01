    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <?php echo $this->Form->create(false, ['url' => ['controller' => 'order', 'action' => 'complete']]); ?>
          <div class="panel-body">
            <div class="col-lg-12 col-xs-12 order none-title none-float">
              <div class="form-group col-lg-12">
                <?php foreach ($productKitList as $productCd => $product): ?>
                  <?php foreach ($product['kitList'] as $kitCd => $num): ?>
                    <div class="row list">
                      <div class="col-xs-12 col-md-8 col-lg-8">
                        <?php echo KIT_NAME[$kitCd]; ?>
                      </div>
                      <div class="col-xs-12 col-md-2 col-lg-2">
                        <?php echo number_format($num); ?> 箱
                      </div>
                      <div class="col-xs-12 col-md-2 col-lg-2">
                      </div>
                    </div>
                  <?php endforeach; ?>
                  <div class="row list">
                    <div class="col-xs-12 col-md-8 col-lg-8">
                      <?php echo PRODUCT_NAME[$productCd]; ?> 小計
                    </div>
                    <div class="col-xs-12 col-md-2 col-lg-2">
                      <?php echo number_format($product['subtotal']['num']); ?> 箱
                    </div>
                    <div class="col-xs-12 col-md-2 col-lg-2">
                      <?php echo number_format($product['subtotal']['price']); ?> 円
                    </div>
                  </div>
                <?php endforeach; ?>
                <div class="row list">
                  <div class="col-xs-12 col-md-8 col-lg-8">
                    合計
                  </div>
                  <div class="col-xs-12 col-md-2 col-lg-2">
                    <?php echo number_format($total['num']); ?> 箱
                  </div>
                  <div class="col-xs-12 col-md-2 col-lg-2">
                    <?php echo number_format($total['price']); ?> 円
                  </div>
                </div>
              </div>
            <?php if (!$customer->isEntry() && !$customer->isCustomerCreditCardUnregist() && !$customer->isCorprateCreditCardUnregist()) : ?>
              <?php if ($customer->isPrivateCustomer() || !$customer->getCorporatePayment()) : ?>
              <div class="col-lg-12">
                <label>カード情報</label>
                <p class="form-control-static"><?php echo h($default_payment_text); ?></p>
              </div>
              <?php endif; ?>
              <div class="col-lg-12">
                <label>お届け先</label>
                <p class="form-control-static"><?php echo h($address_text); ?></p>
              </div>
              <div class="form-group col-lg-12">
                <label>お届け希望日時</label>
                <p class="form-control-static"><?php echo h($datetime); ?></p>
              </div>
            <?php endif; ?>
            </div>
          <?php if (!$customer->isEntry() && !$customer->isCustomerCreditCardUnregist() && !$customer->isCorprateCreditCardUnregist()) : ?>
            <div class="form-group col-lg-12">
              <div class="panel panel-red">
                <div class="panel-heading">
                  <h4>注意事項（ご確認の上、チェックしてください）</h4>
                </div>
                <div class="panel-body">
                  <p>ご購入いただいたキットは、弊社の保管サービス専用の梱包資材になります。専用の梱包キット以外でのサービスのご利用はできません。</p>
                  <p>お預け入れの際は、画面上部の「預ける」より別途お申し込みください。</p>
                  <p>月額保管料はお荷物が弊社に到着した翌月より発生いたします。</p>
                  <p><a href="<?php echo Configure::read('site.static_content_url'); ?>/use_agreement/" target="_blank">利用規約</a>
                    をご確認ください。</p>
                </div>
                <div class="panel-footer">
                  <label>
                    <input type="checkbox" class="agree-before-submit">
                    利用規約に同意する。</label>
                </div>
              </div>
            </div>
          <?php endif; ?>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-primary btn-lg btn-block" href="/order/add?back=true">戻る</a>
            </span>
        <?php if ($customer->isEntry()) : ?>
          <?php if (empty($default_payment)) : ?>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-danger btn-lg btn-block" href="/customer/credit_card/add">会員登録して注文する</a>
            </span>
          <?php else: ?>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-danger btn-lg btn-block" href="/customer/info/add">会員登録して注文する</a>
            </span>
          <?php endif; ?>
        <?php elseif ($customer->isCustomerCreditCardUnregist() || $customer->isCorprateCreditCardUnregist()) : ?>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-danger btn-lg btn-block" href="/customer/credit_card/add">クレジットカード登録して注文する</a>
            </span>
        <?php else: ?>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <button type="submit" class="btn btn-danger btn-lg btn-block">注文を確定する</button>
            </span>
        <?php endif; ?>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
