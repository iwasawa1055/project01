  <?php echo $this->Form->create('V5Box', ['url' => ['controller' => 'inbound_history', 'action' => 'edit'], 'novalidate' => true]); ?>
    <div id="page-wrapper" class="wrapper l-history-dtl">
      <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> お申し込み内容変更</h1>
      <ul class="l-caution-box">
        <li class="l-ttl">注意：</li>
        <li class="l-content">ボックス名・外装の取り外し・保管方法のみ変更可能です。<br>
          外装の取り外しと保管方法は倉庫に到着すると変更ができかねますのでご了承ください。
        </li>
      </ul>
      <ul class="l-lst-dtl">
        <li class="l-lst-item">
          <ul class="l-lst-item-upper">
            <li class="l-img-item">
              <?php if (!empty($box['kit_cd']) && in_array($box['kit_cd'], array_keys(KIT_IMAGE))) : ?>
                <img src="<?php echo KIT_IMAGE[$box['kit_cd']]; ?>" alt="<?php echo KIT_NAME[$box['kit_cd']]; ?>" class="img-item">
              <?php elseif (!empty($box['product_cd']) && in_array($box['product_cd'], array_keys(PRODUCT_IMAGE))) : ?>
                <img src="<?php echo PRODUCT_IMAGE[$box['product_cd']]; ?>" alt="<?php echo PRODUCT_NAME[$box['product_cd']]; ?>" class="img-item">
              <?php else : ?>
                <img src="/images/box-other.png" alt="その他の画像" class="img-item">
              <?php endif; ?>
            </li>
            <li class="l-txt-box-name">
              <label class="headline">ボックス名</label>
              <?php echo $this->Form->textarea('V5Box.box_name', ['class' => "input-box-name"]); ?>
              <?php echo $this->Form->error('V5Box.box_name', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            </li>

            <li class="l-txt-trade-name">
              <?php if(!empty($box['kit_name'])): ?>
              <label class="headline">商品名</label>
              <p class="txt-detail"><?php echo h($box['kit_name']); ?></p>
              <?php endif;?>
            </li>
            <li class="l-lst-item-lower">
              <ul class="l-lst-item-lower-dtl">
                <li class="l-txt-box-id">
                  <label class="headline">ボックス ID</label>
                  <p class="txt-detail"><?php echo h($box['box_id']); ?></p>
                  <?php echo $this->Form->input('box_id', ['type' => 'hidden', 'value' => $box['box_id']]); ?>
                </li>
                <li class="l-txt-box-status">
                  <label class="headline">ステータス</label>
                  <p class="txt-detail"><?php echo BOX_STATUS_LIST[$box['box_status']]; ?></p>
                </li>
                <?php if($box['box_status'] == BOXITEM_STATUS_INBOUND_START): ?>
                  <?php if(in_array($box['product_cd'], WRAPPING_TYPE_PRODUCT_CD_LIST, true)): ?>
                  <li class="l-txt-box-outer">
                    <label class="headline">外装の取り外し</label>
                      <?php echo $this->Form->input("V5Box.wrapping_type", ['type' => 'hidden', 'value' => '0']); ?>
                    <label class="input-check">
                      <?php
                      echo $this->Form->input(
                          'V5Box.wrapping_type',
                          [
                              'class' => 'cb-square',
                              'label' => false,
                              'error' => false,
                              'type' => 'checkbox',
                              'div' => false,
                              'hiddenField' => false,
                          ]
                      );
                      ?>
                      <span class="icon"></span>
                      <span class="label-txt">外装を外す</span>
                    </label>
                  </li>
                  <?php endif;?>
                  <?php if(in_array($box['product_cd'], KEEPING_TYPE_PRODUCT_CD_LIST, true)): ?>
                  <li class="l-txt-box-storage">
                    <label class="headline">保管方法</label>
                    <ul class="list-slct-num">
                      <li>
                        <label class="input-check">
                          <?php
                          echo $this->Form->input(
                              'V5Box.keeping_type',
                              [
                                  'id'    => '',
                                  'class' => 'rb',
                                  'label' => false,
                                  'error' => false,
                                  'options' => [
                                      '1' => '<span class="icon"></span><span class="label-txt">タタミ保管</span>',
                                  ],
                                  'type' => 'radio',
                                  'div' => false,
                                  'hiddenField' => false,
                              ]
                          );
                          ?>
                        </label>
                      </li>
                      <li>
                        <label class="input-check">
                            <?php
                            echo $this->Form->input(
                                'V5Box.keeping_type',
                                [
                                    'id'    => '',
                                    'class' => 'rb',
                                    'label' => false,
                                    'error' => false,
                                    'options' => [
                                        '2' => '<span class="icon"></span><span class="label-txt">ハンガー保管</span>',
                                    ],
                                    'type' => 'radio',
                                    'div' => false,
                                    'hiddenField' => false,
                                ]
                            );
                            ?>
                        </label>
                      </li>
                    </ul>
                  </li>
                  <?php endif;?>
                <?php else:?>
                  <?php if(in_array($box['product_cd'], WRAPPING_TYPE_PRODUCT_CD_LIST, true)): ?>
                  <li class="l-txt-box-outer">
                    <label class="headline">外装の取り外し</label>
                    <?php if ($box['wrapping_type'] !== '' && in_array($box['wrapping_type'], array_keys(BOX_WRAPPING_TYPE_LIST))) : ?>
                    <p class="txt-detail"><?php echo h(BOX_WRAPPING_TYPE_LIST[$box['wrapping_type']]);?></p>
                    <?php else : ?>
                    <p class="txt-detail">-</p>
                    <?php endif; ?>
                  </li>
                  <?php endif;?>
                  <?php if(in_array($box['product_cd'], KEEPING_TYPE_PRODUCT_CD_LIST, true)): ?>
                  <li class="l-txt-box-storage">
                    <label class="headline">保管方法</label>
                    <?php if ($box['keeping_type'] !== '' && in_array($box['keeping_type'], array_keys(BOX_KEEPING_TYPE_LIST))) : ?>
                    <p class="txt-detail"><?php echo h(BOX_KEEPING_TYPE_LIST[$box['keeping_type']]);?></p>
                    <?php else : ?>
                    <p class="txt-detail">-</p>
                    <?php endif; ?>
                  </li>
                  <?php endif;?>
                <?php endif;?>
              </ul>
            </li>
          </ul>
        </li>

      </ul>

    </div>
    <div class="nav-fixed">
      <ul>
        <li>
          <a class="btn-d-gray" href="/inbound_history/detail?w_id=<?php echo $work_id; ?>">戻る</a>
        </li>
        <li><button class="btn-red" type="submit">内容を確認する</button>
        </li>
      </ul>
    </div>
  <?php echo $this->Form->end(); ?>