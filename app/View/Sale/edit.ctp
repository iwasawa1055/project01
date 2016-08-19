    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-shopping-basket"></i> アイテム販売</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
		<?php /* on  */ ?>
		<?php if (CakeSession::read('CustomerSales.sales_flag') === '1'):?>
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group col-lg-12">
                  <p class="form-control-static">アイテム販売機能をONにしました。</p>
                </div>
                <span class="col-lg-12 col-md-12 col-xs-12">
                <a class="btn btn-danger btn-lg btn-block animsition-link" href="/item/">アイテム一覧に移動する</a>
                </span>
                <?php /* todo 口座情報 分岐 */ ?>
                <span class="col-lg-12 col-md-12 col-xs-12">
                <a class="btn btn-danger btn-lg btn-block animsition-link" href="/customer/account/add">金融機関情報を登録する（初回のみ）</a>
                </span>
              </div>
            </div>
          </div>
        </div>
		<?php elseif (CakeSession::read('CustomerSales.sales_flag') === '0'):?>
		<?php /* off  */ ?>
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group col-lg-12">
                  <p class="form-control-static">アイテム販売機能をOFFにしました。</p>
                </div>
                <span class="col-lg-12 col-md-12 col-xs-12">
                <a class="btn btn-danger btn-lg btn-block animsition-link" href="/">マイページへ戻る</a>
                </span>
              </div>
            </div>
          </div>
        </div>
		<?php endif;?>
      </div>
    </div>
