    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-exchange"></i> minikuraTRADE</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>ご登録済みの金融機関情報</h2>
                <div class="form-group col-lg-12">
                  <?php if(! empty($customer_account)):?>
                  <p class="form-control-static"><?php echo h($customer_account['bank_name']);?>　<?php echo h($customer_account['bank_branch_name']);?>　<?php echo BANK_ACCOUNT_TYPE[$customer_account['bank_account_type']];?>　<?php echo h($customer_account['bank_account_number']);?></p>
                  <?php endif;?>
                </div>
                <?php if(! empty($customer_account)):?>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <a class="btn btn-danger btn-lg btn-block animsition-link" href="/customer/account/edit">変更する</a>
                </span>
                <?php else:?>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <a class="btn btn-danger btn-lg btn-block animsition-link" href="/customer/account/add">新規作成する</a>
                </span>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
