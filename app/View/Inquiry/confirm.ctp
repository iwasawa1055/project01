    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-pencil-square-o"></i> お問い合わせ</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
            <?php echo $this->Form->create('Inquiry', ['url' => ['controller' => 'inquiry', 'action' => 'complete']]); ?>
              <div class="col-lg-12 none-title">
                <div class="form-group col-lg-12">
                  <label>お名前</label>
                  <p class="form-control-static"><?php echo $this->CustomerInfo->setName($this->Form->data['Inquiry']); ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>メールアドレス</label>
                  <p class="form-control-static"><?php echo $this->Form->data['Inquiry']['email']; ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの種別</label>
                  <p class="form-control-static"><?php echo INQUIRY_DIVISION[$this->Form->data['Inquiry']['division']] ?></p>
                </div>
                <div class="form-group col-lg-12">
                  <label>お問い合わせの内容</label>
                  <p class="form-control-static">
                    <?php echo nl2br(h($this->Form->data['Inquiry']['text'])); ?>
                  </p>
                </div>
                <div class="form-group col-lg-12">
                  <a class="btn btn-info btn-xs btn-block" href="/use_agreement/" target="_blank">minikura 利用規約</a>
                  <label>
                    <input class="agree-before-submit" type="checkbox">
                    minikura 利用規約に同意する
                  </label>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block" href="/inquiry/add?back=true">戻る</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <button type="submit" class="btn btn-danger btn-lg btn-block">この内容で問い合わせる</button>
                </span>
              </div>
            <?php echo $this->Form->end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
