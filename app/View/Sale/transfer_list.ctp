
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
                <h2>振込依頼一覧</h2>
                <div class="form-group col-lg-12 col-sm-12">
                  <?php if (empty($transfer_completed)):?>
                  <p class="form-control-static">振込明細はありません。</p>
                  <?php else:?>
                  <p class="form-control-static">対象月をクリックすると、振込明細をご確認いただけます。</p>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>振込依頼月</th>
                          <th>注文合計</th>
                          <th>手数料</th>
                          <th>送金額</th>
                          <th>振込手続日</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($transfer_completed as $completed): ?>
                        <tr>
                          <td><a class="animsition-link" href="/sale/transfer_detail"><?php echo($this->Time->format($completed['transfer_requested'], '%Y-%m'));?></a></td>
                          <td><?php echo number_format(h(floor($completed['subtotal_price'])));?>円</td>
                          <td><?php echo number_format(h(floor($completed['charge_price'])));?>円</td>
                          <td><?php echo number_format(h(floor($completed['total_price'])));?>0円</td>
                          <td><?php echo($this->Time->format($completed['transfer_completed'], '%Y-%m-%d'));?></td>
                        </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                    <a class="btn btn-info btn-md pull-right" href="/sale/index">アイテム販売へ戻る</a>
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
