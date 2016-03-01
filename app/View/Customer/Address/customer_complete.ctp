<?php
$actionName = '追加';
if ($action === 'customer_edit') {
    $actionName = '変更';
} else if ($action === 'customer_delete') {
    $actionName = '削除';
}
?>
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-truck"></i> お届け先<?php echo $actionName; ?></h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <h2>お届け先<?php echo $actionName; ?></h2>
            <p class="form-control-static col-lg-12">お届け先情報の<?php echo $actionName; ?>が完了しました。</p>
            <span class="col-lg-12 col-md-12 col-xs-12">
              <a class="btn btn-danger btn-lg btn-block" href="/">マイページへ戻る</a>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
