    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-shopping-basket"></i> アイテム販売</h1>
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
                  ただいま振込可能な金額は <span class="point"><?php echo number_format($transfer_price);?></span> 円
                </div>
                <h2>振込予定口座</h2>
                <div class="form-group col-lg-12">
                <?php if(! empty($customer_bank_account)):?>
                  <?php echo h($customer_bank_account['bank_name']);?>銀行　<?php echo h($customer_bank_account['bank_branch_name']);?>支店　<?php echo BANK_ACCOUNT_TYPE[$customer_bank_account['bank_account_type']];?>　<?php echo h($customer_bank_account['bank_account_number']);?>
                <?php endif;?>
                  <p class="form-control-static">金融機関情報の変更は<a class="animsition-link" href="/customer/account/index">「金融機関情報」</a> から変更してください。 </p>
                </div>
                <p class="form-control-static point col-lg-12">上記の内容で振込依頼をしますか？</p>
                <?php echo $this->Form->create('Sale', ['url' => ['controller' => 'Sale', 'action' => 'transfer_complete']]); ?>
                <span class="col-lg-12 col-md-12 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">振込依頼する</button>
                </span>
                <span class="col-lg-12 col-md-12 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/sale/index/">戻る</a>
                </span>
                <?php echo $this->Form->end(); ?>

                <?php if (! empty($sales)):?>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>販売日</th>
                          <th>商品名</th>
                          <th>金額</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($sales as $key => $val ):?>
                        <tr>
                          <td><?php echo($this->Time->format($val['sales_end'], '%Y-%m-%d'));?></td>
                          <td><?php echo h($val['sales_title']);?></td>
                          <td><?php echo number_format($val['price']);?>円</td>
                        </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                  </div>
                <?php endif;?>

              </div>  
              <div class="col-lg-12">
              <!--  
              </div>  
              <div class="col-lg-12">
              -->
              
              <!--  
                <div class="form-group col-lg-12">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>販売日</th>
                          <th>商品名</th>
                          <th>金額</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>2016/09/25</td>
                          <td>商品名11111111</td>
                          <td>1,000円</td>
                        </tr>
                        <tr>
                          <td>2016/09/25</td>
                          <td>商品名11111111</td>
                          <td>1,000円</td>
                        </tr>
                        <tr>
                          <td>2016/09/25</td>
                          <td>商品名11111111</td>
                          <td>1,000円</td>
                        </tr>
                        <tr>
                          <td>2016/09/25</td>
                          <td>商品名11111111</td>
                          <td>1,000円</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
               --> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
