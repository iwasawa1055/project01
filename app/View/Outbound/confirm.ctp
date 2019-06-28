    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> 取り出し</h1>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <?php echo $this->Form->create('Outbound', ['url' => '/outbound/complete', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
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
                        <?php echo $this->element('List/item_body', ['item' => $item]); ?>
                        <?php echo $this->element('List/item_footer', ['item' => $item]); ?>
                      </div>
                    </div>
                    <!--loop end-->
                    <?php endforeach; ?>
                  </div>
                </div>

              <div class="col-lg-12">
                <?php if (!empty($boxList)) : ?>
                <h2>取り出すボックス</h2>
                <?php endif; ?>
                <div class="row box-list">
                  <?php $early_retrieval_flag = false?>
                  <?php foreach ($boxList as $box): ?>
                  <?php $url = '/box/detail/' . $box['box_id']; ?>
                  <?php if($box['product_cd'] === PRODUCT_CD_MONO || $box['product_cd'] === PRODUCT_CD_HAKO): ?>
                      <?php $early_retrieval_flag = true?>
                  <?php endif;?>
                  <!--loop-->
                  <div class="col-lg-12">
                    <div class="panel panel-default">
                      <div class="panel-body <?php echo $this->MyPage->boxClassName($box); ?>">
                        <div class="row">
                          <div class="col-lg-12 col-md-12 col-sm-12">
                            <h3 class="boxitem-name"><a href="<?php echo $url; ?>"><?php echo h($box['box_name']); ?></a>
                            </h3>
                          </div>
                          <!-- <div class="col-lg-4 col-md-4 col-xs-12"></div> -->
                        </div>
                      </div>
                      <?php echo $this->element('List/box_footer', ['box' => $box]); ?>
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
                  <p class="form-control-static"><?php echo OUTBOUND_HAZMAT[$this->Form->data['Outbound']['aircontent_select']] ?></p>
                  <?php if ($this->Form->data['Outbound']['aircontent_select'] === OUTBOUND_HAZMAT_EXIST) : ?>
                  <label>お預かり品名</label>
                  <p class="form-control-static">
                    <?php echo nl2br(h($this->Form->data['Outbound']['aircontent'])); ?>
                  </p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <!--航空機輸送禁止品目が含まれている場合、ここまで表示-->
            <?php endif; ?>
            <?php if (($isolateIsland && $this->Form->data['Outbound']['aircontent_select'] === OUTBOUND_HAZMAT_NOT_EXIST) || !$isolateIsland) : ?>
            <div class="form-group col-lg-12">
              <label>お届け希望日時</label>
              <p class="form-control-static"><?php echo $datetime_text; ?></p>
            </div>
            <div class="form-group col-lg-12">
              <div class="panel panel-red">
                <div class="panel-heading">
                  <label>ご注意ください</label>
                  <ul>
                    <li>
                      お申込み完了後、日時を含む内容の変更はお受けすることができません。<br>
                      内容にお間違いないか再度ご確認の上、「この内容で取り出す」にお進みください。
                    </li>
                    <?php if($early_retrieval_flag): ?>
                    <li>
                      早期の取り出しについて、預け入れから1ヶ月以内の場合は月額保管料の2ヶ月分。2ヶ月以内の場合は月額保管料の1ヶ月分が料金として発生いたします。個品のお取り出しがある場合は適用致しません。
                    </li>
                    <?php endif;?>
                  </ul>
                </div>
              </div>
            </div>
            <?php endif; ?>
            <span class="col-lg-6 col-md-6 col-xs-12">
            <a class="btn btn-primary btn-lg btn-block" href="/outbound/?back=true">戻る</a>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
            <button type="submit" class="btn btn-danger btn-lg btn-block">この内容で取り出す</button>
            </span>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
