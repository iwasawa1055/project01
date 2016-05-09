<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-truck"></i> 法人ユーザー登録</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <h2>法人ユーザー登録</h2>
            <p class="form-control-static col-lg-12">法人ユーザー登録が完了しました。</p>
            <span class="col-lg-12 col-md-12 col-xs-12">
            <?php if (empty($alliance_cd)) : ?>
              <a class="btn btn-danger btn-lg btn-block" href="/">マイページへ</a>
            <?php else : ?>
              <a class="btn btn-danger btn-lg btn-block" href="/order/add">ボックス購入へ</a>
            <?php endif; ?>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
