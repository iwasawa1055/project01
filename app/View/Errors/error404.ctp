<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-home"></i> マイページ</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <h2>お探しのページがみつかりません。</h2>
            <p class="form-control-static col-lg-12">一時的にアクセスできない状態か、移動もしくは削除されてしまった可能性があります。</p>
            <p class="col-lg-12 error-number"></p>
            <?php
            if (Configure::read('debug') > 0) {
                echo $this->element('exception_stack_trace');
            }
            ?>
            <span class="col-lg-12 col-md-12 col-xs-12">
            <a class="btn btn-danger btn-lg btn-block" href="/">マイページへ戻る</a>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
