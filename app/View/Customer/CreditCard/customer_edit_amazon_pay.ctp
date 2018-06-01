    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-credit-card"></i> クレジットカード変更</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <form name="form" action="<?php echo $action; ?>" method="post">
                  <?php if($amazon_pay_info = $this->Flash->render('amazon_pay_info')) : ?>
                  <div class="dsn-form">
                    <p class="error-message-red">
                      <?php echo $amazon_pay_info;?>
                    </p>
                  </div>
                  <?php endif; ?>

                  <?php if($debt) : ?>
                  <h4 class="form-control-static col-lg-12">お支払い状況をご確認ください</h4>
                  <p class="form-control-static col-lg-12">
                    いつもminikuraをご利用いただき、ありがとうございます。<br>
                    現在、お客様とのご契約に基づき、保管品をお預かりしておりますが、お支払いが確認できておりません。<br>
                    ※ご登録いただいたクレジットカード情報の反映には数日かかることがございます。
                  </p>
                  <?php endif; ?>
                  <div id="dsn-amazon-pay" class="form-group col-lg-12">
                    <div class="dsn-address">
                      <div id="addressBookWidgetDiv">
                      </div>
                    </div>
                    <div class="dsn-credit">
                      <div id="walletWidgetDiv">
                      </div>
                    </div>
                  </div>
                  <div id="dsn-payment" class="form-group col-lg-12">
                    <div id="consentWidgetDiv">
                    </div>
                  </div>
                  <input type="hidden" name="amazon_billing_agreement_id" id="amazon_billing_agreement_id" value="<?php echo $baid; ?>">
                  <input type="hidden" name="regist_user_flg" id="regist_user_flg" value="<?php echo $regist_user_flg; ?>">
                  <span class="col-lg-12 col-md-12 col-xs-12">
                    <a id="amazonPayComplete" class="btn btn-danger btn-lg btn-block" href="javascript:void(0);">変更する</a>
                  </span>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php $this->Html->script('/js/credit_card/edit_amazon_pay', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->css('/css/credit_card/edit_amazon_pay', ['block' => 'css']); ?>
