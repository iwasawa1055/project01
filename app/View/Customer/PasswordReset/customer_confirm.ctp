    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-lock"></i> パスワード再発行</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
            <?php echo $this->Form->create(false, ['url' => ['action' => 'complete']]); ?>
              <div class="col-lg-12">
                <div class="form-group col-lg-12">
                  <label>メールアドレス</label>
                  <p class="form-control-static"><?php echo $this->Form->data['CustomerPasswordReset']['email'] ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>新しいパスワード</label>
                  <p class="form-control-static">入力したパスワード</p>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block animsition-link" href="/customer/password_reset/add?back=true">戻る</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">パスワードを設定する</button>
                </span>
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
