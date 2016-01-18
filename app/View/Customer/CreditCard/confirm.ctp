  <div id="page-wrapper">
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
            <?php echo $this->Form->create(false, ['url' => ['action' => 'complete']]); ?>
              <div class="col-lg-12">
                <div class="form-group">
                  <label>クレジットカード番号</label>
                  <p class="form-control-static"><?php echo $security_card['card_no']; ?></p>
                </div>
                <div class="form-group">
                  <label>セキュリティコード</label>
                  <p class="form-control-static"><?php echo $security_card['security_cd']; ?></p>
                </div>
                <div class="form-group">
                  <label>有効期限</label>
                  <p class="form-control-static"><?php echo $security_card['expire_month']; ?>月</p>
                </div>
                <div class="form-group">
                  <p class="form-control-static"><?php echo $security_card['expire_year_disp']; ?>年</p>
                </div>
                <div class="form-group">
                  <label>クレジットカード名義</label>
                  <p class="form-control-static"><?php echo $security_card['holder_name']; ?></p>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12"> <a class="btn btn-primary btn-lg btn-block animsition-link" href="/customer/credit_card/edit?back=true">戻る</a> </span>
                <span class="col-lg-6 col-md-6 col-xs-12"> <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">変更する</button> </span>
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
