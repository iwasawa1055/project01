    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-list-alt"></i> 契約情報</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>ご契約者情報</h2>
                <div class="form-group col-lg-12">
                  <label>郵便番号</label>
                  <p><?php echo $data['postal']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>住所</label>
                  <p><?php echo $data['pref'].$data['address1'].$data['address2'].$data['address3']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>電話番号</label>
                  <p><?php echo $data['tel1']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>お名前</label>
                  <p class="form-control-static"><?php echo $data['firstname']; ?>　<?php echo $data['firstname']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>お名前（カナ）</label>
                  <p><?php echo $data['lastname_kana']; ?>　<?php echo $data['firstname_kana']; ?></p>
                </div>
                <div class="col-lg-12 col-md-12 col-xs-12">
                  <a class="btn btn-info btn-md animsition-link pull-right" href="/customer/info/edit">情報を変更する</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>契約情報一覧</h2>
                <div class="col-lg-12 col-xs-12 agreement">
                  <div class="form-group col-lg-12">
                    <div class="row list">
                      <div class="col-xs-12 col-md-10 col-lg-10">
                        minikuraMONO
                      </div>
                      <div class="col-xs-12 col-md-2 col-lg-2">
                        <?php echo array_key_exists(PRODUCT_CD_MONO, $product_summary) ? ($product_summary[PRODUCT_CD_MONO]) : 0; ?>箱
                      </div>
                    </div>
                    <div class="row list">
                      <div class="col-xs-12 col-md-10 col-lg-10">
                        minikuraHAKO
                      </div>
                      <div class="col-xs-12 col-md-2 col-lg-2">
                        <?php echo array_key_exists(PRODUCT_CD_HAKO, $product_summary) ? ($product_summary[PRODUCT_CD_HAKO]) : 0; ?>箱
                      </div>
                    </div>
                    <div class="row list">
                      <div class="col-xs-12 col-md-10 col-lg-10">
                        クリーニングパック
                      </div>
                      <div class="col-xs-12 col-md-2 col-lg-2">
                        <?php echo array_key_exists(PRODUCT_CD_CLEANING_PACK, $product_summary) ? ($product_summary[PRODUCT_CD_CLEANING_PACK]) : 0; ?>箱
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
