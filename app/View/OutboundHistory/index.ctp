
<div id="page-wrapper" class="wrapper l-history">
    <?php $data_error = $this->Flash->render('data_error');?>
    <?php if (isset($data_error)) : ?>
      <p class="valid-bl"><?php echo $data_error; ?></p>
    <?php endif; ?>
  <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> お申し込み履歴</h1>
  <p class="page-caption">3ヶ月以内の取り出しお申込み内容を表示しております。<br>
    それ以前のお申込みについてはマイページTOPのメッセージよりご確認ください。
  </p>
    <?php echo $this->Form->create('InboundAndOutboundHistory', ['url' => ['controller' => 'outbound_history', 'action' => 'index'], 'novalidate' => true]); ?>
  <div class="l-search-group l-search-history">
    <ul class="l-word-search">
      <li>
          <?php echo $this->Form->input(
              'InboundAndOutboundHistory.keyword',
              [
                  'type' => 'search',
                  'class' => "search",
                  'placeholder' => '例）ボックスID、アイテムID、配送先名、配送先',
                  'error' => false,
                  'label' => false,
                  'div' => false
              ]); ?>
      </li>
      <li>
        <button class="btn-submit">検索</button>
      </li>
    </ul>
  </div>
    <?php echo $this->Form->end(); ?>
    <?php if(!empty($list)): ?>
      <ul class="l-lst-history">
        <li class="l-lst-itm l-lst-head">
          <ul class="l-lst-dtl">
            <li class="l-data">取り出し申込日</li>
            <li class="l-status-outbound">ステータス</li>
            <li class="l-name">配送先名</li>
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
                      <li class="l-status-outbound" title="ステータス">キャンセル</li>
                      <?php else: ?>
                      <li class="l-status-outbound" title="ステータス">出庫依頼中</li>
                      <?php endif;?>
                  <?php else: ?>
                      <?php if($history_data['works_progress_type'] == WORKS_PROGRESS_TYPE_COMPLETE): ?>
                      <li class="l-status-outbound" title="ステータス">完了</li>
                      <?php else: ?>
                      <li class="l-status-outbound" title="ステータス">出庫依頼中</li>
                      <?php endif;?>
                  <?php endif;?>
                <li class="l-name" title="配送先名"><?php echo $history_data['delivery_name'];?></li>
                <li class="l-address" title="配送先"><?php echo $history_data['delivery_state'] . $history_data['delivery_city'] . $history_data['delivery_street_address'] . ' ' . $history_data['delivery_suburb'];?></li>
                  <?php if (isset($history_data['work_linkage_id'])) : ?>
                    <li class="l-view"><a class="btn" href="/outbound_history/detail?wl_id=<?php echo $history_data['work_linkage_id']; ?>">詳細を見る</a></li>
                  <?php else : ?>
                    <li class="l-view"><a class="btn" href="/outbound_history/detail?w_id=<?php echo $history_data['work_id']; ?>">詳細を見る</a></li>
                  <?php endif; ?>
              </ul>
            </li>
          <?php endforeach; ?>
      </ul>
      <div class="l-pgn">
      <?php echo $this->element('paginator_new'); ?>
      </div>
    <?php else: ?>
    <ul class="l-lst-history">
      <p class="dev-m1">該当するデータが存在いたしません。</p>
    </ul>
    <?php endif;?>
</div>
