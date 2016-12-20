    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-exchange"></i> minikuraTRADE</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
		<?php /* on  */ ?>
		<?php if ($is_customer_sales === true):?>
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group col-lg-12">
                  <p class="form-control-static">minikuraTRADE機能をONにしました。</br>
                  アイテム一覧ページよりお好きなアイテムを選択し、商品情報・出品金額を決めて販売設定を行ってください。</p>
                </div>
                <span class="col-lg-12 col-md-12 col-xs-12">
                <a class="btn btn-danger btn-lg btn-block animsition-link" href="/item/">アイテム一覧に移動する</a>
                </span>
              </div>
            </div>
          </div>
        </div>
		<?php elseif ($is_customer_sales === false ):?>
		<?php /* off  */ ?>
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group col-lg-12">
                  <p class="form-control-static">minikuraTRADE機能をOFFにしました。</p>
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
