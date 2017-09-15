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
                <h4 class="form-control-static col-lg-12">お支払い状況をご確認ください</h4>
                <p class="form-control-static col-lg-12">
                  いつもminikuraをご利用いただき、ありがとうございます。<br>
                  現在、お客様とのご契約に基づき、保管品をお預かりしておりますが、お支払いが確認できておりません。<br>
                  「お支払い方法」の欄からご利用可能なカードをご登録ください。<br>
                  ※必ず、ご契約者様名義のクレジットカード情報をご登録ください。<br>
                  ※ご登録いただいたクレジットカード情報の反映には数日かかることがございます。</p>
                </div>
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

                <span class="col-lg-12 col-md-12 col-xs-12">
                  <a id="amazonPayLogout" class="btn btn-danger btn-lg btn-block" href="javascript:void(0);">ログアウトする</a>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php $this->Html->script('/js/credit_card/edit_amazon_pay', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->css('/css/credit_card/edit_amazon_pay.css', ['block' => 'css']); ?>
