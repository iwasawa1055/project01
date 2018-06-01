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
                <h2>クレジットカード変更</h2>
                <div class="form-group col-lg-12">
                  <p class="form-control-static">
                  クレジットカード情報の変更が完了しました。<br />
                  次回よりご登録いただいたクレジットカードに請求いたしますので、クレジットカードの利用明細をご確認ください。<br />
                  <?php if ($debt) : ?>
                  <br />
                  なお、お支払いが滞っているお客様につきましては、弊社からコンビニ払いの請求書をお送りしております。<br />
                  お支払いが完了するまでの間は、利用可能なカード情報にご変更頂いた場合でも、ログインの制限がかかっておりますので、予めご了承ください。<br />
                  <?php endif; ?>
                  </p>
                </div>
                <?php if ($debt) : ?>
                <span class="col-lg-12 col-md-12 col-xs-12">
                  <a id="AmazonPayLogoutButton" class="btn btn-danger btn-lg btn-block" href="/login/logout">ログアウト</a>
                </span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php $this->Html->script('/js/credit_card/complete_amazon_pay', ['block' => 'scriptMinikura']); ?>
