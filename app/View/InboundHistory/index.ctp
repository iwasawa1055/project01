
<div id="page-wrapper" class="wrapper l-history">
    <?php $data_error = $this->Flash->render('data_error');?>
    <?php if (isset($data_error)) : ?>
      <p class="valid-bl"><?php echo $data_error; ?></p>
    <?php endif; ?>
  <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> お申し込み履歴</h1>
  <p class="page-caption">預け入れ申込み中の内容を確認できます。<br>
    預け入れ完了または、キャンセルしたものは反映されません。<br>
    （お預け入れ申込み後、お荷物が到着しない場合は自動でキャンセルする場合がございます。）<br>
    お預け入れ入れ完了状況はマイページTOPのメッセージよりご確認ください。<br>
    「詳細を見る」ボタンよりお荷物が倉庫に到着するまでの間、撮影方法および保管方法等の変更が可能です。<br>
    また、集荷情報についても、処理状況によって変更できます。
  </p>

    <?php if(!empty($inbound_history_list)): ?>
      <ul class="l-lst-history">
        <li class="l-lst-itm l-lst-head">
          <ul class="l-lst-dtl">
            <li class="l-data">預け入れ申込日</li>
            <li class="l-method-dtl">入庫方法</li>
            <li class="l-status l-left">ボックス種別</li>
            <li class="l-view"></li>
          </ul>
        </li>
          <?php foreach($inbound_history_list as $history_data): ?>
            <li class="l-lst-itm">
              <ul class="l-lst-dtl">
                <li class="l-data" title="預け入れ申込日"><?php echo $this->Html->formatYmdKanjiDatetime($history_data['create_date']); ?></li>
                  <?php if(!empty($history_data['box_delivery_type'])): ?>
                    <?php if($history_data['box_delivery_type'] == BOX_DELIVERY_TYPE_YOURSELF): ?>
                    <li class="l-method-dtl" title="入庫方法">自分で申込</li>
                    <?php else: ?>
                    <li class="l-method-dtl" title="入庫方法">集荷で申込</li>
                    <?php endif;?>
                  <?php else: ?>
                    <li class="l-method-dtl" title="入庫方法">-</li>
                  <?php endif;?>
                <li class="l-status l-left">
                <?php
                  $prefix = substr($history_data['box_ids'], 0, 2);
                  if (in_array($prefix, array_keys(BOX_PREFIX_PRODUCT_CD))) {
                    echo PRODUCT_NAME[BOX_PREFIX_PRODUCT_CD[$prefix]];
                  } else {
                    echo 'その他';
                  }
                ?>
                </li>
                <li class="l-view"><a class="btn" href="/inbound_history/detail?w_id=<?php echo $history_data['work_id']; ?>">詳細を見る</a></li>
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
