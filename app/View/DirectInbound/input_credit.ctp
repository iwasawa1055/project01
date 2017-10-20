<?php $this->Html->script('direct_inbound/input_credit.js', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script(Configure::read("app.gmo.token_url"), ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('libGmoCreditCardPayment', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('gmoCreditCardPayment', ['block' => 'scriptMinikura']); ?>

<?php $this->Html->css('/css/app.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/direct_inbound/dsn-boxless.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/direct_inbound/direct_inbound_dev.css', ['block' => 'css']); ?>

    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i>預け入れ</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default dev-panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>クレジットカード情報の入力</h2>
                <form method="post" action="/direct_inbound/complete_credit" novalidate>
                <div  id="gmo_validate_error" class="form-group col-lg-12">
                  <?php echo $this->Flash->render('gmo_token'); ?>
                </div>
                <div  id="gmo_credit_card_info" class="form-group col-lg-12">
                </div>
                <input type="hidden" id="shop_id" value="<?php echo Configure::read('app.gmo.shop_id'); ?>">
                <div class="form-group col-lg-12">
                  <input type="tel" class="form-control" name="card_no" id="cardno" placeholder="0000-0000-0000-0000" size="20" maxlength="20" value="<?php echo CakeSession::read('Credit.card_no');?>">
                  <?php echo $this->Flash->render('card_no');?>
                  <?php echo $this->Flash->render('customer_card_info');?>
                  <?php echo $this->Flash->render('customer_kit_card_info');?>
                  <p class="help-block">ハイフン有り無しどちらでもご入力いただけます。</p>
                </div>
                <div class="form-group col-lg-12">
                  <input type="tel" class="form-control" name="security_cd" id="securitycode" placeholder="0123" size="6" maxlength="6" value="">
                  <?php echo $this->Flash->render('security_cd');?>
                  <p class="help-block">カード裏面に記載された 3〜4桁の番号をご入力ください。</p>
                  <p class="security_code"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">※セキュリティコードとは？</a></p>
                  <div id="collapseOne" class="panel-collapse collapse panel panel-default">
                    <div class="panel-body">
                      <p>セキュリティコードとは、クレジットカード番号とは異なる、3桁または4桁の番号で、第三者がお客様のクレジットカードを不正利用出来ないようにする役割があります。</p>
                      <p>カードの種類によってセキュリティコードの記載位置が異なりますので、下記をご覧ください。</p>
                      <h4>Visa、Mastercard等の場合</h4>
                      <p>カードの裏面の署名欄に記入されている3桁の番号です。</p>
                      <p>カード番号の下3桁か、その後に記載されています。</p>
                      <p><img src="/images/cvv2visa.gif" alt="" /></p>
                      <h4>American Expressの場合</h4>
                      <p>カードの表面に記入されている4桁の番号です。</p>
                      <p>カード番号の下4桁か、その後に記載されています。</p>
                      <p><img src="/images/cvv2amex.gif" alt="" /></p>
                    </div>
                  </div>
                </div>
                <div class="form-group col-lg-12">
                  <label>有効期限</label>
                  <select class="form-control" name="expire_month" id="expiremonth">
                    <?php foreach ( $this->Html->creditcardExpireMonth() as $value => $string ) :?>
                    <option value="<?php echo $value;?>"<?php if ( $value === substr(CakeSession::read('Credit.expire'),0,2) ) echo " SELECTED";?>><?php echo $string;?></option>
                    <?php endforeach ?>
                  </select>
                </div>
                <div class="form-group col-lg-12">
                  <select class="form-control" name="expire_year" id="expireyear">
                    <?php foreach ( $this->Html->creditcardExpireYear() as $value => $string ) :?>
                    <option value="<?php echo $value;?>"<?php if ( (string) $value === substr(CakeSession::read('Credit.expire'),2,2) ) echo " SELECTED";?>><?php echo $string;?></option>
                    <?php endforeach ?>
                  </select>
                  <?php echo $this->Flash->render('expire');?>
                </div>
                <div class="form-group col-lg-12">
                  <input type="url" class="form-control dev-holder_name" name="holder_name" id="holdername" placeholder="TERRADA MINIKURA" size="28" maxlength="30" value="<?php echo CakeSession::read('Credit.holder_name');?>" novalidate>
                  <?php echo $this->Flash->render('holder_name');?>
                  <p class="help-block">（※半角大文字英数字、半角スペース）</p>
                </div>
               <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/direct_inbound/input">戻る</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                   <button type="button" class="btn btn-danger btn-lg btn-block">次へ</button>
                </span>
            </div>
          </div>
        </div>
      </div>
    </div>
