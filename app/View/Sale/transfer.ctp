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
                <h2>振込依頼</h2>
                <div class="form-group col-lg-12">
                  ただいま振込可能な金額は <span class="point"><?php echo number_format($transfer_price_all);?></span> 円<br>
                </div>
                <div class="form-group col-lg-12">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>振込可能金額</th>
                          <th>振込手数料</th>
                          <th>振込金額</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><?php echo number_format(h($transfer_price_all));?>円</td>
                          <td><?php echo number_format(h($transfer_charge_price));?>円</td>
                          <td><?php echo number_format(h($transfer_price));?>円</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <h2>振込予定口座</h2>
                <div class="form-group col-lg-12">
                <?php if(! empty($customer_bank_account)):?>
                  <?php echo h($customer_bank_account['bank_name']);?>　<?php echo h($customer_bank_account['bank_branch_name']);?>　<?php echo BANK_ACCOUNT_TYPE[$customer_bank_account['bank_account_type']];?>　<?php echo h($customer_bank_account['bank_account_number']);?>
                <?php endif;?>
                  <p class="form-control-static">金融機関情報の変更は<a class="animsition-link" href="/customer/account/index">「金融機関情報」</a> から変更してください。 </p>
                </div>

                <?php if (floor($transfer_price) > '0' && !empty($customer_bank_account) ):?>
                <p class="form-control-static point col-lg-12">上記の内容で振込依頼をしますか？</p>
                <?php echo $this->Form->create('Sale', ['url' => ['controller' => 'Sale', 'action' => 'transfer_complete']]); ?>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">振込依頼する</button>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/sale/index/">戻る</a>
                </span>
                <?php echo $this->Form->end(); ?>
                <?php else:?>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block" disabled="disabled">振込依頼する</button>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/sale/index/">戻る</a>
                </span>
                <?php endif;?>
              </div>  
            </div>
          </div>
        </div>
      </div>
    </div>
