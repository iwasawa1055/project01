
    <div id="page-wrapper" class="wrapper l-history">
      <?php $data_error = $this->Flash->render('data_error');?>
      <?php if (isset($data_error)) : ?>
        <p class="valid-bl"><?php echo $data_error; ?></p>
      <?php endif; ?>
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> 取り出し履歴</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <?php echo $this->Form->create('InboundAndOutboundHistory', ['url' => ['controller' => 'outbound_history', 'action' => 'index'], 'novalidate' => true]); ?>
              <div class="l-search-group l-search-history">
                <ul class="l-word-search">
                  <li>
                    <?php echo $this->Form->input('InboundAndOutboundHistory.keyword', ['type' => 'search', 'class' => "search", 'error' => false, 'label' => false, 'div' => false]); ?>
                  </li>
                  <li>
                    <button class="btn-submit">検索</button>
                  </li>
                </ul>
              </div>
              <?php echo $this->Form->end(); ?>
              <ul class="l-lst-history">
                <?php if(!empty($list)): ?>
                <li class="l-lst-itm l-lst-head">
                  <ul class="l-lst-dtl">
                    <li class="l-data">取り出し申込日</li>
                    <li class="l-status-outbound">ステータス</li>
                    <li class="l-address">配送先</li>
                    <li class="l-view"></li>
                  </ul>
                </li>
                <?php foreach($list as $history_data): ?>
                <li class="l-lst-itm">
                  <ul class="l-lst-dtl">
                    <li class="l-data" title="取り出し申込日"><?php echo $this->Html->formatYmdKanjiDatetime($history_data['create_date']); ?></li>
                    <?php if (isset($history_data['link_status'])) : ?>
                        <?php if($history_data['link_status'] == WORKS_LINKAGE_LINK_STATUS_CANCEL): ?>
                        <li class="l-method-dtl" title="ステータス">キャンセル済み</li>
                        <?php else: ?>
                        <li class="l-method-dtl" title="ステータス">出庫依頼中</li>
                        <?php endif;?>
                    <?php else: ?>
                        <?php if($history_data['works_progress_type'] == WORKS_PROGRESS_TYPE_COMPLETE): ?>
                        <li class="l-method-dtl" title="ステータス">完了</li>
                        <?php else: ?>
                        <li class="l-method-dtl" title="ステータス">出庫依頼中</li>
                        <?php endif;?>
                    <?php endif;?>
                    <li class="l-status" title="配送先"><?php echo $history_data['delivery_state'] . $history_data['delivery_city'] . $history_data['delivery_street_address'] . ' ' . $history_data['delivery_suburb'];?></li>
                    <li class="l-view"><a class="btn" href="/outbound_history/detail?announcement_id=<?php echo $history_data['announcement_id']; ?>">詳細を見る</a></li>
                  </ul>
                </li>
                <?php endforeach; ?>
                <?php endif;?>
              </ul>
              <?php if(empty($list)): ?>
              <p class="dev-no-item-caution">該当するデータが存在しません。</p>
              <?php else: ?>
              <?php echo $this->element('paginator'); ?>
              <?php endif;?>
            </div>
          </div>
        </div>
      </div>
    </div>
