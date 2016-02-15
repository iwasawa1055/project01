    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="col-lg-12 col-xs-12 order">
              <p class="notice">下記の内容で注文を承りました。</p>
              <div class="form-group col-lg-12">
                <div class="row list">
                  <div class="col-xs-12 col-md-8 col-lg-8">
                    minikuraMONO
                  </div>
                  <div class="col-xs-12 col-md-2 col-lg-2">
                    <?php echo $data['PaymentGMOKitCard']['mono_num']; ?>箱
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
                    <?php echo $data['PaymentGMOKitCard']['hako_num']; ?>箱
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
                    <?php echo $data['PaymentGMOKitCard']['cleaning_num']; ?>箱
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
              <div class="form-group col-lg-12">
                <label>カード情報</label>
                <p class="form-control-static"><?php echo $default_payment_text; ?></p>
              </div>
              <div class="form-group col-lg-12">
                <label>お届け先</label>
                <p class="form-control-static"><?php echo $address_text; ?></p>
              </div>
              <div class="col-lg-12">
                <label>お届け希望日</label>
                <p class="form-control-static"><?php echo $datetime; ?></p>
              </div>
            </div>
            <span class="col-lg-12 col-md-12 col-xs-12">
            <a class="btn btn-danger btn-lg btn-block animsition-link" href="/">マイページへ戻る</a>
            </span>
          </div>
        </div>
      </div>
    </div>
