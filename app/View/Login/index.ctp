    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-sign-in"></i> ログイン</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="col-lg-12 col-md-12 none-title">
            <form action="/login/doing" method="post">
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
            </form>
              <a class="btn btn-block btn-social btn-lg btn-facebook"><i class="fa fa-facebook"></i>Facebook アカウントでログイン</a>
              <a class="btn btn-info btn-xs btn-block animsition-link" href="/customer/password_reset/add">パスワードを忘れた方はこちら</a>
              <a class="btn btn-primary btn-xs btn-block animsition-link" href="/customer/register/add">ユーザー登録はこちら</a>
              <a class="btn btn-default btn-xs btn-block animsition-link" href="credit_card_reset/index.html">※債務クレジットカードの場合</a>
            </div>
          </div>
        </div>
      </div>
    </div>
