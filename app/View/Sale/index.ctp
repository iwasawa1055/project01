    <?php $this->Html->script('minikura/sale', ['block' => 'scriptMinikura']); ?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-shopping-basket"></i> minikuraTRADE</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>minikuraTRADE機能とは？</h2>
              </div>
            </div>
            <div class="form-group col-lg-12">
              minikuraTRADE機能とは、「minikuraMONO」でお預かりただいている商品をFacebook、TwitterなどのSNSやブログなど、お好きなサイトで販売できる販売機能サービスです。商品が購入されると、minikuraから購入者へ商品を匿名で配送しますので、手間がかからないラクちんサービスです。
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
                <h2>販売機能設定</h2>
              </div>
            </div>
            <?php if ($customer_sales['sales_flag'] === '0' || empty($customer_sales)):?>
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
                  ただいま振込可能な金額は <span class="point"><?php echo number_format($transfer_price);?></span> 円
                </div>
                <h2>振込予定口座</h2>
                <div class="form-group col-lg-12">
                <?php if(! empty($customer_bank_account)):?>
                  <?php echo h($customer_bank_account['bank_name']);?>　<?php echo h($customer_bank_account['bank_branch_name']);?>　<?php echo BANK_ACCOUNT_TYPE[$customer_bank_account['bank_account_type']];?>　<?php echo h($customer_bank_account['bank_account_number']);?>
                <?php endif;?>
                  <p class="form-control-static">金融機関情報の変更は<a class="animsition-link" href="/customer/account/index">「金融機関情報」</a>から変更してください。 </p>
                </div>

                <h2>お振込みについて</h2>
                <div class="form-group col-lg-12">
                  販売代金を受け取るには、「振込みを依頼する」より振込申請を行ってください。<br>
                  実際のお振込みに関しては以下費用を差し引いた金額が指定口座に振込されます。<br>
                  <br>
                  販売手数料：販売代金の２０％<br>
                  振込手数料：１回の振込につき３24円(税込)<br>
                  <br>
                  販売代金の受け取りは最大６ヶ月まで繰り越すことができます。<br>
                  まとめて振込申請をすることで、振込手数料の負担軽減につながります。<br>
                  なお６ヶ月以上経過した場合は、毎月のminikura請求金額から相殺して請求させていただきます。<br>
                  請求金額がない場合は、minikuraポイントで売上金相当分のポイントを還元いたします。
                </div>
                
                <span class="col-lg-6 col-md-6 col-xs-12">
                <?php if (floor($transfer_price) > 0 ): ?>
                <a class="btn btn-danger btn-md" href="/sale/transfer/">振込を依頼する</a>
                <?php else:?>
                <a class="btn btn-danger btn-md" disabled="disabled" >振込を依頼する</a>
                <?php endif;?>
                </span>

                <div class="form-group col-lg-12 col-sm-12">
                  <h3>振込依頼履歴</h3>
                  <?php if (empty($transfer_completed)):?>
                  <p class="form-control-static">振込明細はありません。</p>
                  <?php else:?>
                  <p class="form-control-static">対象月をクリックすると、振込明細をご確認いただけます。</p>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>売買完了日</th>
                          <th>注文合計</th>
                          <th>引落し手数料</th>
                          <th>販売手数料</th>
                          <th>送金額</th>
                          <th>振込手続日</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($transfer_completed as $completed): ?>
                        <tr>
                          <td><a class="animsition-link" href="/sale/transfer_detail/<?php echo $completed['transfer_id'];?>"><?php echo($this->Time->format($completed['transfer_requested'], '%Y-%m'));?></a></td>
                          <td><?php echo number_format(h(floor($completed['subtotal_price'])));?>円</td>
                          <td><?php echo number_format(h(floor($completed['charge_price'])));?>円</td>
                          <td><?php /* 販売手数料　キャンペーン中でしばらく0円 */ ;?>0円</td>
                          <td><?php echo number_format(h(floor($completed['total_price'])));?>円</td>
                          <td><?php echo($this->Time->format($completed['transfer_completed'], '%Y-%m-%d'));?></td>
                        </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                    <a class="btn btn-info btn-md pull-right" href="/sale/transfer_list">振込依頼一覧を見る</a>
                  </div>
                  <?php endif;?>
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
                  <?php echo $this->Form->select('jquery_sales_status', $master_sales_status_array,  ['class' => 'form-control', 'value' => $sales_status ,  'empty' => false, 'error' => false]);?>
                  </div>
                </div>
                <!--loop-->
                <div class="col-lg-12">
                <?php if (!empty($sales)):?>
                  <?php foreach($sales as $sales_history):?>
                  <div class="panel panel-default">
                    <?php echo $this->element('List/sale_item_icon_body', ['sales_history' => $sales_history]); ?>
                    <?php echo $this->element('List/sale_item_icon_footer', ['sales_history' => $sales_history]); ?>
                  </div>
                  <?php endforeach;?>
                <?php else:?>
                  <p class="form-control-static col-lg-12">該当する販売履歴がありません</p>
                <?php endif;?>
                </div>
                <!--loop end-->
              </div>
              <?php echo $this->element('paginator'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
