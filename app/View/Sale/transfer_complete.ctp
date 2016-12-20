    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-exchange"></i> minikuraTRADE</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
              <?php if (!empty($transfer_result)): ?>
                <h2>振込依頼完了</h2>
                <div class="form-group col-lg-12">
                振り込み依頼が完了しました。
                </div>
              <?php endif;?>

              <?php if (!empty($error_message)): ?>
                <h2>振込依頼失敗</h2>
                <div class="form-group col-lg-12">
                <p>振り込み依頼が失敗しました。</p>
                <p><?php echo h($error_message);?></p>
                </div>
              <?php endif;?>
                <span class="col-lg-12 col-md-12 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/sale/index/">戻る</a>
                </span>
              </div>  
            </div>
          </div>
        </div>
      </div>
    </div>
