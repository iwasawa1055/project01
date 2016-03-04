<?php
$actionName = '登録';
if ($action === 'customer_edit') {
    $actionName = '変更';
}
?>
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-credit-card"></i> クレジットカード<?php echo $actionName; ?></h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
                <h2>クレジットカード<?php echo $actionName; ?></h2>
                <div class="form-group col-lg-12">
                  <p class="form-control-static">クレジットカードの<?php echo $actionName; ?>が完了しました。</p>
                </div>
                <span class="col-lg-12 col-md-12 col-xs-12">
                  <a class="btn btn-danger btn-lg btn-block" href="/">マイページへ戻る</a>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
