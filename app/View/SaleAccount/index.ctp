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
                <h2>ご登録済みの金融機関情報</h2>
                <div class="form-group col-lg-12">
                  <p class="form-control-static">xxxxxxxx銀行　xxxxxxxx支店　普通　0000000000</p>
                </div>
                <?php $update_flag = true;  /* todo 分岐*/    ?>
                <?php if ( $update_flag ): ?>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <a class="btn btn-danger btn-lg btn-block animsition-link" href="/sale/account/edit">変更する</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                <a class="btn btn-danger btn-lg btn-block animsition-link" href="/sale/account/add">新規作成する</a>
                </span>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
