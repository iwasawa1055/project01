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
                  Amazonのサイトからお支払いに使用するクレジットカードの変更を行ってください。<br>
                  ※ご登録いただいたクレジットカード情報の反映には数日かかることがございます。
                </p>

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
