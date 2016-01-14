  <div id="page-wrapper">
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
              <div class="col-lg-12">
                  <div class="form-group">
                    <label>新しいメールアドレス</label>
                    <p class="form-control-static"><?php echo $customer_email['email']; ?></p>
                  </div>
                  <div class="form-group">
                    <label>新しいメールアドレス（再入力）</label>
                    <p class="form-control-static"><?php echo $customer_email['email_confirm']; ?></p>
                  </div>
                  <p class="help-block">変更いただいたメールアドレスにご確認メールをお送りします。</p>
                  <span class="col-lg-6 col-md-6 col-xs-12"> <a class="btn btn-primary btn-lg btn-block animsition-link" href="javascript:history.back();">戻る</a> </span>
                <form action="/customer/email/complete" method="post">
                  <span class="col-lg-6 col-md-6 col-xs-12"> <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link" href="/customer/email/complete">メールを送信</button> </span>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
