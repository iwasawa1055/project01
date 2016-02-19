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
                <div class="row list">
                  <div class="col-xs-12 col-md-8 col-lg-8">
                    minikuraMONO
                  </div>
                  <div class="col-xs-12 col-md-2 col-lg-2">
                    <?php echo $this->Form->data['OrderKit']['mono_num']; ?>箱
                  </div>
                  <div class="col-xs-12 col-md-2 col-lg-2">
                    00円
                  </div>
                </div>
                <div class="row list">
                  <div class="col-xs-12 col-md-8 col-lg-8">
                    minikuraHAKO
                  </div>
                  <div class="col-xs-12 col-md-2 col-lg-2">
                    <?php echo $this->Form->data['OrderKit']['hako_num']; ?>箱
                  </div>
                  <div class="col-xs-12 col-md-2 col-lg-2">
                    00円
                  </div>
                </div>
                <div class="row list">
                  <div class="col-xs-12 col-md-8 col-lg-8">
                    クリーニングパック
                  </div>
                  <div class="col-xs-12 col-md-2 col-lg-2">
                    <?php echo $this->Form->data['OrderKit']['cleaning_num']; ?>箱
                  </div>
                  <div class="col-xs-12 col-md-2 col-lg-2">
                    00円
                  </div>
                </div>
                <div class="row list">
                  <div class="col-xs-12 col-md-8 col-lg-8">
                    合計
                  </div>
                  <div class="col-xs-12 col-md-2 col-lg-2">
                    00箱
                  </div>
                  <div class="col-xs-12 col-md-2 col-lg-2">
                    00円
                  </div>
                </div>
              </div>
            <?php if (!$isEntry) : ?>
              <?php if ($isPrivateCustomer || empty($corporatePayment))  : ?>
              <div class="col-lg-12">
                <label>カード情報</label>
                <p class="form-control-static"><?php echo $default_payment_text; ?></p>
              </div>
              <?php endif; ?>
              <div class="col-lg-12">
                <label>お届け先</label>
                <p class="form-control-static"><?php echo $address_text; ?></p>
              </div>
              <div class="form-group col-lg-12">
                <label>お届け希望日時</label>
                <p class="form-control-static"><?php echo $datetime; ?></p>
              </div>
            <?php endif; ?>
            </div>
          <?php if (!$isEntry) : ?>
            <div class="form-group col-lg-12">
              <div class="panel panel-red">
                <div class="panel-heading">
                  <h4>注意事項（ご確認の上、チェックしてください）</h4>
                </div>
                <div class="panel-body">
                  <p>ご購入いただいたキットは、弊社の保管サービス専用の梱包資材になります。専用の梱包キット以外でのサービスのご利用はできません。</p>
                  <p>お預け入れの際は、画面上部の「預ける」より別途お申し込みください。</p>
                  <p>月額保管料はお荷物が弊社に到着した翌月より発生いたします。</p>
                  <p><a class="animsition-link" href="/terms" target="_blank">利用規約</a>
                    をご確認ください。</p>
                </div>
                <div class="panel-footer">
                  <label>
                    <input type="checkbox">
                    利用規約に同意する。</label>
                </div>
              </div>
            </div>
          <?php endif; ?>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-primary btn-lg btn-block animsition-link" href="/order/add?back=true">戻る</a>
            </span>
        <?php if ($isEntry) : ?>
          <?php if (empty($default_payment)) : ?>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-danger btn-lg btn-block animsition-link" href="/customer/credit_card/add">会員登録して注文する</a>
            </span>
          <?php else: ?>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-danger btn-lg btn-block animsition-link" href="/customer/info/add">会員登録して注文する</a>
            </span>
          <?php endif; ?>
        <?php else: ?>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">注文を確定する</button>
            </span>
        <?php endif; ?>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
