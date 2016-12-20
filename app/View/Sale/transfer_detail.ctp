
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
                <h2>振込依頼明細</h2>
                <div class="form-group col-lg-12 col-sm-12">
                  <?php if(! empty($transfer_detail)):?>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>商品名</th>
                          <th>商品価格</th>
                          <!--<th>送料</th>-->
                          <th>注文合計</th>
                          <th>販売日</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($transfer_detail as $transfer):?>
                        <tr>
                          <td class="product-name"><?php echo h($transfer['sales_title']);?></td>
                          <td><?php echo number_format(h($transfer['price']));?></td>
                          <!--<td>5,000円</td>-->
                          <td><?php echo number_format(h($total_price));?>円</td>
                          <td><?php echo($this->Time->format($transfer['purchased'], '%Y-%m-%d'));?></td>
                        </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                    <a class="btn btn-info btn-md pull-right" href="/sale/index">minikuraTRADEへ戻る</a>
                    <?php echo $this->element('paginator'); ?>
                  </div>
                  <?php endif;?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
