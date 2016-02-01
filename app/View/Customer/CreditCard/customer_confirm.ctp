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
              <?php echo $this->Form->create(false, ['url' => ['action' => 'complete']]); ?>
                <div class="form-group col-lg-12">
                  <label>クレジットカード番号</label>
                  <p><?php echo $security_card['card_no']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>セキュリティコード</label>
                  <p><?php echo $security_card['security_cd']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>有効期限</label>
                  <p><?php echo $security_card['expire_month']; ?>月/<?php echo $security_card['expire_year_disp']; ?>年</p>
                </div>
                <div class="form-group col-lg-12">
                  <label>クレジットカード名義</label>
                  <p><?php echo $security_card['holder_name']; ?></p>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block animsition-link" href="/customer/credit_card/edit?back=true">戻る</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">変更する</button>
                </span>
              <?php echo $this->Form->end(); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
