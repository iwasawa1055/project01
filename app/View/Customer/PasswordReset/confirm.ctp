  <div id="page-wrapper">
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
              <div class="col-lg-12">
                <?php echo $this->Form->create(false, ['url' => ['action' => 'complete'], 'inputDefaults' => ['label' => false, 'div' => false]]); ?>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <?php echo $this->Form->data['email'] ?>
                    </div>
                    <p class="help-block">登録されたメールアドレスにご確認メールをお送りします。</p>
                    <span class="col-lg-6 col-md-6 col-xs-12"> <a class="btn btn-primary btn-lg btn-block animsition-link" href="javascript:history.back();">戻る</a> </span>
                    <span class="col-lg-6 col-md-6 col-xs-12"> <a class="btn btn-danger btn-lg btn-block animsition-link" href="/customer/password_reset/complete">メールを送信する</a></span>
                  </div>
                <!-- </form> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
