<?php $this->Html->css('/css/app.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/direct_inbound/dsn-boxless.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/direct_inbound/direct_inbound_dev.css', ['block' => 'css']); ?>
<?php $this->validationErrors['OrderKit'] = $validErrors; ?>

    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i>預け入れ</h1>
      </div>
    </div>
    <div class="row">
      <form method="post" class="select-add-address-form" action="/direct_inbound/complete" novalidate>
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-12">
                  <h2>minikuraダイレクト</h2>
                  <?php echo $this->Flash->render('inbound_direct');?>
                  <p class="form-control-static col-lg-12">預け入れる個数を確認して「この内容でボックスを預け入れ」にすすんでください。</p>
                  <div class="row box-list">
                    <!--loop-->
                    <div class="col-lg-12">
                      <div class="panel panel-default">
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-lg-10 col-md-10 col-xs-12">
                              <label> 個数</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12">
                              <label> <?php echo CakeSession::read('Order.direct_inbound.direct_inbound') ?>個</label>
                            </div>
                          </div>
                        </div>
                        <div class="panel-footer">
                          <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                              <p class="box-list-caption"><span>商品名</span>minikuraダイレクト</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <?php if (CakeSession::read('OrderKit.cargo') !== "着払い") : ?>
                <div class="form-group col-lg-12">
                  <label>預け入れ方法</label>
                  <p class="form-control-static">ヤマト運輸</p>
                </div>
                <div class="form-group col-lg-12">
                  <label>集荷の住所</label>
                  <p class="form-control-static">〒<?php echo CakeSession::read('DispAddress.postal');?> <?php echo CakeSession::read('DispAddress.pref');?><?php echo CakeSession::read('DispAddress.address1');?><?php echo CakeSession::read('DispAddress.address2');?> <?php echo CakeSession::read('DispAddress.address3');?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>集荷の日時</label>
                  <p><?php echo CakeSession::read('OrderKit.select_delivery_text') ?></p>
                </div>
              <?php else: ?>
                <div class="form-group col-lg-12">
                  <label>預け入れ方法</label>
                  <p class="form-control-static">自分で送る（持ち込みで着払い）</p>
                </div>
              <?php endif; ?>

              <div class="form-group col-lg-12">
                <div class="panel panel-red">
                  <div class="panel-heading">
                    <h4>注意事項（ご確認の上、チェックしてください）</h4>
                  </div>
                  <div class="panel-body">
                    <p>
                      <label>
                        <input type="checkbox" name="check_weight" value="Remember Me" class="agree-before-submit">
                        重量は15kg（おおよそ1人で持ち運びできる程度）までを目安に梱包してください。</label>
                    </p>
                    <p>※明らかに20kgを超えた場合は保管料を別途頂戴することがございます。あらかじめご了承ください。</p>
                    <?php echo $this->Flash->render('check_weight');?>
                    <p>
                      <label>
                        <input type="checkbox" name="check_hazardous_material" value="Remember Me" class="agree-before-submit">
                        発火性・引火性のある危険物、液体・生物・その他<a href="/terms" target="_blank">利用規約</a>
                        で定められたものはお預かりできません。 </label>
                    </p>
                    <?php echo $this->Flash->render('check_hazardous_material');?>
                  </div>
                  <div class="panel-footer">
                    <label>
                      <input type="checkbox" name="remember" value="Remember Me" class="agree-before-submit">
                      利用規約に同意する。</label>
                    <?php echo $this->Flash->render('remember');?>

                  </div>
                </div>
              </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
            <a class="btn btn-primary btn-lg btn-block" href="/direct_inbound/input">戻る</a>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <button class="btn-next page-transition-link" type="submit" disabled="disabled">
                この内容で申し込みをする
                <i class="fa fa-chevron-circle-right"></i>
              </button>
            </span>
            </div>
          </div>
        </div>
        </form>
      </div>

