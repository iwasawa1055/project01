    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-truck"></i> お届け先追加・変更</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <?php echo $this->Form->create('CustomerAddress', ['url' => ['controller' => 'address', 'action' => $action, 'step' => 'complete']]); ?>
            <div class="row">
              <div class="col-lg-12">
                <h2>お届け先追加</h2>
                <p class="form-control-static col-lg-12">以下の内容でお届け先情報を保存します。</p>
                <div class="form-group col-lg-12">
                  <label>郵便番号</label>
                  <p>
                      <?php echo $this->Form->data['CustomerAddress']['postal'] ?>
                  </p>
                </div>
                <div class="form-group col-lg-12">
                  <label>住所</label>
                  <p>
                      <?php echo $this->Form->data['CustomerAddress']['pref'] . $this->Form->data['CustomerAddress']['address1'] ?>
                  </p>
                </div>
                <div class="form-group col-lg-12">
                  <label>番地</label>
                  <p>
                      <?php echo $this->Form->data['CustomerAddress']['address2'] ?>
                  </p>
                </div>
                <div class="form-group col-lg-12">
                  <label>建物名</label>
                  <p>
                      <?php echo $this->Form->data['CustomerAddress']['address3'] ?>
                  </p>
                </div>
                <div class="form-group col-lg-12">
                  <label>電話番号</label>
                  <p>
                      <?php echo $this->Form->data['CustomerAddress']['tel1'] ?>
                  </p>
                </div>
                <div class="form-group col-lg-12">
                  <label>名前</label>
                  <p>
                      <?php echo $this->Form->data['CustomerAddress']['lastname'] . ' ' . $this->Form->data['CustomerAddress']['firstname'] ?>
                  </p>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                    <a class="btn btn-primary btn-lg btn-block animsition-link" href="/customer/address/add?back=true"> 戻る </a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                    <button type="submit" class="btn btn-danger btn-lg btn-block page-transition-link">保存する</button>
                </span>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
