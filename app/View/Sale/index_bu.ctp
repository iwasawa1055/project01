    <?php $this->Html->script('minikura/sale', ['block' => 'scriptMinikura']); ?>
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
                <h2>販売機能設定</h2>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="row">
                <span class="col-sm-6 col-xs-12">
                <a class="btn btn-info btn-xs btn-block" href="<?php echo Configure::read('site.static_content_url'); ?>/use_agreement/" target="_blank">minikura利用規約</a>
                </span>
              </div>
              <div class="checkbox">
                <label>
                  <input class="agree-before-submit" type="checkbox">
                  minikura利用規約に同意する </label>
              </div>
            </div>
            <?php if ($customer_sales['sales_flag'] === '0' || empty($customer_sales)):?>
            <?php echo $this->Form->create('CustomerSales', ['url' => ['controller' => 'Sale', 'action' => 'edit']]); ?>
            <span class="col-sm-6 col-xs-12">
              <?php echo $this->Form->hidden('sales_flag', ['value' => '1']); ?>
              <button type="submit" class="btn btn-danger btn-block">販売機能をONにする</button>
            </span>
            <?php echo $this->Form->end(); ?>
            <?php endif;?>

            <?php if ($customer_sales['sales_flag'] === '1'):?>
            <?php echo $this->Form->create('CustomerSales', ['url' => ['controller' => 'Sale', 'action' => 'edit']]); ?>
            <span class="col-sm-6 col-xs-12">
              <?php echo $this->Form->hidden('sales_flag', ['value' => '0']); ?>
              <button type="submit" class="btn btn-danger  btn-block">販売機能をOFFにする</button>
            </span>
            <?php echo $this->Form->end(); ?>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>振込可能合計金額</h2>
                <div class="form-group col-lg-12">
                  ただいま振込可能な金額は <span class="point">50,000</span> 円
                </div>
                <h2>振込予定口座</h2>
                <div class="form-group col-lg-12">
                <?php if(! empty($customer_bank_account)):?>
                  <?php echo h($customer_bank_account['bank_name']);?>銀行　<?php echo h($customer_bank_account['bank_branch_name']);?>支店　<?php echo BANK_ACCOUNT_TYPE[$customer_bank_account['bank_account_type']];?>　<?php echo h($customer_bank_account['bank_account_number']);?>
                <?php endif;?>
                  <p class="form-control-static">金融機関情報の変更は<a class="animsition-link" href="/customer/account/index">「金融機関情報」</a>
                    から変更してください。 </p>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <a class="btn btn-danger btn-md" href="/sale/order/">振込を依頼する</a>
                </span>
                <div class="form-group col-lg-12 col-sm-12">
                  <h3>振込依頼履歴</h3>
                  <p class="form-control-static">対象月をクリックすると、振込明細をご確認いただけます。</p>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>振込依頼月</th>
                          <th>注文合計</th>
                          <th>手数料</th>
                          <th>振込額</th>
                          <th>振込手続日</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><a class="animsition-link" href="/sale/order_detail">2000/00</a></td>
                          <td>50,000円</td>
                          <td>5,000円</td>
                          <td>45,000円</td>
                          <td>2000/00/00</td>
                        </tr>
                        <tr>
                          <td><a class="animsition-link" href="/sale/order_detail">2000/00</a></td>
                          <td>50,000円</td>
                          <td>5,000円</td>
                          <td>45,000円</td>
                          <td>2000/00/00</td>
                        </tr>
                        <tr>
                          <td><a class="animsition-link" href="/sale/order_detail">2000/00</a></td>
                          <td>50,000円</td>
                          <td>5,000円</td>
                          <td>45,000円</td>
                          <td>2000/00/00</td>
                        </tr>
                      </tbody>
                    </table>
                    <a class="btn btn-info btn-md pull-right" href="/sale/order_list">振込依頼一覧を見る</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>販売履歴</h2>
                <div class="row box-sort">
                  <div class="col-sm-12 col-xs-12">
                    <select class="form-control list_sort">
                      <option value="1">販売中 を表示</option>
                      <option value="2">購入手続き中 を表示</option>
                      <option value="3">販売中 を表示</option>
                      <option value="4">購入手続き中 を表示</option>
                    </select>
                  </div>
                </div>
                <!--loop-->
                <div class="col-lg-12">
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-2 col-xs-3 item-detail">
                          <img src="/images/xxx_xxxx.jpg" alt="">
                        </div>
                        <div class="col-lg-7 col-md-7 col-xs-9">
                          <h3 class="box-item-name">販売した商品名販売した商品名販売した商品名販売した商品名販売した商品名販売した商品名販売した商品名</h3>
                          <p class="box-item-remarks">検索結果検索結果<b>検索結果</b>検索結果検索結果検索結果検索結果検索結果<b>検索結果</b>検索結果検索結果<b>検索結果</b><b>検索結果</b>検索結果検索結果<b>検索結果</b>検索結果検索結果検索結果<b>検索結果</b>検索結果検索結果検索結果</p>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-12">
                          <a class="btn btn-danger btn-md btn-detail pull-right animsition-link" href="/item/detail">アイテムを確認する</a>
                        </div>
                      </div>
                    </div>
                    <div class="panel-footer">
                      <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-12">
                          <p class="box-list-caption"><span>商品ステータス</span>送金済み</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                          <p class="box-list-caption"><span>販売日</span>0000/00/00</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--loop end-->
              </div>
              <?php echo $this->element('paginator'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>