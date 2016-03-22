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
            <h2>お探しのページはサーバ側のエラーにより表示ができません。</h2>
            <p class="form-control-static col-lg-12">また、実行された内容がサーバへ反映されていない可能性あります。<br />
            お手数ですが、しばらく経ってから再度アクセスしてください。</p>
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
