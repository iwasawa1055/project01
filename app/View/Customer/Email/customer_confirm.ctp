    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-envelope"></i> メールアドレス変更</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
            <?php echo $this->Form->create(false, ['url' => ['controller' => 'email', 'action' => 'complete']]); ?>
              <div class="col-lg-12 none-title">
                <div class="form-group col-lg-12">
                  <label>古いメールアドレス</label>
                  <p class="form-control-static">email@example.com</p>
                </div>
                <div class="form-group col-lg-12">
                  <label>新しいメールアドレス</label>
                  <p class="form-control-static"><?php echo $this->Form->data['CustomerEmail']['email']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>新しいメールアドレス（再入力）</label>
                  <p class="form-control-static"><?php echo $this->Form->data['CustomerEmail']['email_confirm']; ?></p>
                </div>
                <p class="form-control-static col-lg-12">変更いただいたメールアドレスにご確認メールをお送りします。</p>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block animsition-link" href="/customer/email/edit?back=true">戻る</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">メールを送信</button>
                </span>
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
