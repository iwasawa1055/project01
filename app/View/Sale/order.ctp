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
                  ただいま振込可能な金額は <span class="point">000,000,000,000</span> 円
                </div>
                <h2>振込予定口座</h2>
                <div class="form-group col-lg-12">
                  xxxxxxxx銀行　xxxxxxxx支店　普通　0000000000
                  <p class="form-control-static">金融機関情報の変更は<a class="animsition-link" href="/sale/account/">「金融機関情報」</a>
                    から変更してください。 </p>
                </div>
                <p class="form-control-static point col-lg-12">上記の内容で振込依頼をしますか？</p>
                <?php echo $this->Form->create('Sale', ['url' => ['controller' => 'Sale', 'action' => 'order_complete']]); ?>
                <span class="col-lg-12 col-md-12 col-xs-12">
                  <?php echo $this->Form->hidden('order_setting', ['value' => 'true']); ?>
                  <button type="submit" class="btn btn-danger btn-lg btn-block">振込依頼する</button>
                </span>
                <span class="col-lg-12 col-md-12 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/sale/index/">戻る</a>
                </span>
                <?php echo $this->Form->end(); ?>
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
