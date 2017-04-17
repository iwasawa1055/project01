<?php $this->Html->css('/css/app.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/direct_inbound/dsn-boxless.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/direct_inbound/direct_inbound_dev.css', ['block' => 'css']); ?>

    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i>預け入れ</h1>
      </div>
    </div>
    <div class="row">
      <form method="post" class="select-add-address-form" action="/direct_inbound/complete_credit" novalidate>
        <div class="col-lg-12 none-title none-float">
          <h2>クレジットカード情報の確認</h2>
          <div class="form-group col-lg-12">
            <label>クレジットカード番号</label>
            <p class="form-control-static"><?php echo CakeSession::read('Credit.disp_card_no'); ?></p>
          </div>
          <div class="form-group col-lg-12">
            <label>セキュリティコード</label>
            <p class="form-control-static"><?php echo CakeSession::read('Credit.security_cd'); ?></p>
          </div>
          <div class="form-group col-lg-12">
            <label>有効期限</label>
            <p class="form-control-static"><?php echo CakeSession::read('Credit.expire_month'); ?>月<?php echo CakeSession::read('Credit.expire_year'); ?>年</p>
          </div>
          <div class="form-group col-lg-12">
            <label>クレジットカード名義</label>
            <p class="form-control-static"><?php echo CakeSession::read('Credit.holder_name'); ?></p>
          </div>
          <span class="col-lg-6 col-md-6 col-xs-12">
            <a class="btn btn-primary btn-lg btn-block" href="/direct_inbound/input_credit">戻る</a>
          </span>
          <span class="col-lg-6 col-md-6 col-xs-12">
            <button class="btn btn-danger btn-lg btn-block" type="submit">
              この内容で登録する
              <i class="fa fa-chevron-circle-right"></i>
            </button>
          </span>
        </div>
      </form>
    </div>


