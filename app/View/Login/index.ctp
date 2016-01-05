<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ログイン・ユーザー登録</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">

            <form action="/login/doing" method="post">
              <div class="col-lg-6 col-md-6">
                <h2>ログイン</h2>
                <div class="form-group">
                  <input class="form-control" placeholder="メールアドレス" name="email" type="email" autofocus>
                </div>
                <div class="form-group">
                  <input class="form-control" placeholder="パスワード" name="password" type="password" value="">
                </div>
                <div class="checkbox">
                  <label>
                    <input name="remember" type="checkbox" value="Remember Me">
                    次回ログイン時に入力を省く </label>
                </div>
                <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link" >ログイン</button>
              </div>
            </form>

            <div class="col-lg-6 col-md-6">
              <h2>会員登録</h2>
              <div class="form-group">
                <input class="form-control" placeholder="メールアドレス" name="email" type="email" autofocus>
              </div>
              <div class="form-group">
                <input class="form-control" placeholder="パスワード" name="password" type="password" value="">
              </div>
              <div class="form-group">
                <input class="form-control" placeholder="パスワード（確認用）" name="password" type="password" value="">
              </div>
              <div class="form-group">
                <input class="form-control" placeholder="ニックネーム" name="" type="text" value="">
              </div>
              <div class="form-group">
                <label>お知らせメール</label>
                <select class="form-control">
                  <option>受信する</option>
                  <option>受信しない</option>
                </select>
              </div>
              <a class="btn btn-default btn-xs btn-block animsition-link" href="/terms" target="_blank">利用規約</a>
              <a class="btn btn-danger btn-lg btn-block animsition-link" href="/customer/register/">利用規約に同意して会員登録</a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
