    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-suitcase"></i> minikura teburaTRAVEL</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <?php echo $this->Form->create('Travel', ['url' => '/travel/complete', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                  <?php if (!empty($itemList)) : ?>
                  <h2>取り出すアイテム</h2>
                  <?php endif; ?>
                  <div class="row box-list">
                    <?php foreach ($itemList as $item): ?>
                    <!--loop-->
                    <div class="col-lg-12">
                      <div class="panel panel-default">
                        <?php echo $this->element('List/item_body_none_link', ['item' => $item]); ?>
                        <?php echo $this->element('List/item_footer', ['item' => $item]); ?>
                      </div>
                    </div>
                    <!--loop end-->
                    <?php endforeach; ?>
                  </div>
                </div>
            </div>
            <?php if(!$customer->isSneaker()) : ?>
            <div class="form-group col-lg-12">
              <label>ご利用ポイント</label>
              <p class="form-control-static"><?php echo empty($pointUse['use_point']) ? '0' : $pointUse['use_point']; ?> ポイント</p>
            </div>
            <?php endif; ?>
            <div class="form-group col-lg-12">
              <label>お届け先住所</label>
              <p class="form-control-static"><?php echo h($address_text); ?></p>
            </div>
            <?php if ($isolateIsland) : ?>
            <!--航空機輸送禁止品目が含まれている場合、ここから表示-->
            <div class="form-group col-lg-12">
              <div class="panel panel-red">
                <div class="panel-body">
                  <label>航空機で輸送できないお預かり品</label>
                  <p class="form-control-static"><?php echo OUTBOUND_HAZMAT[$this->Form->data['OutboundLimit']['aircontent_select']] ?></p>
                  <?php if ($this->Form->data['OutboundLimit']['aircontent_select'] === OUTBOUND_HAZMAT_EXIST) : ?>
                  <label>お預かり品名</label>
                  <p class="form-control-static">
                    <?php echo nl2br(h($this->Form->data['OutboundLimit']['aircontent'])); ?>
                  </p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <!--航空機輸送禁止品目が含まれている場合、ここまで表示-->
            <?php endif; ?>
            <?php if (($isolateIsland && $this->Form->data['OutboundLimit']['aircontent_select'] === OUTBOUND_HAZMAT_NOT_EXIST) || !$isolateIsland) : ?>
            <div class="form-group col-lg-12">
              <label>お届け希望日時</label>
              <p class="form-control-static"><?php echo $datetime_text; ?></p>
            </div>
            <div class="form-group col-lg-12">
              <label>ご返却予定日</label>
              <p class="form-control-static"><?php echo $expiredate_text; ?></p>
            </div>
            <?php endif; ?>
            <span class="col-lg-6 col-md-6 col-xs-12">
            <a class="btn btn-primary btn-lg btn-block" href="/travel/?back=true">戻る</a>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
            <button type="submit" class="btn btn-danger btn-lg btn-block">この内容で取り出す</button>
            </span>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
