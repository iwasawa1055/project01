
    <div id="page-wrapper" class="wrapper l-history">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> 預け入れ履歴</h1>
          <p class="page-caption">3ヶ月以内の預け入れお申込み内容を表示しております。<br>
            それ以前のお申込みについてはマイページTOPのメッセージよりご確認ください。<br>
            また、詳細画面よりお荷物が倉庫に到着するまでの間、撮影方法および保管方法等の変更が可能です。<br>
            集荷情報については、一部変更が可能ですが、処理状況によっては変更できませんのでご注意ください。
          </p>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <ul class="l-lst-history">
                <li class="l-lst-itm l-lst-head">
                  <ul class="l-lst-dtl">
                    <li class="l-data">預け入れ申込日</li>
                    <li class="l-method-dtl">入庫方法</li>
                    <li class="l-status l-left">ステータス</li>
                    <li class="l-view"></li>
                  </ul>
                </li>
                <?php foreach($inbound_history_list as $history_data): ?>
                <li class="l-lst-itm">
                  <ul class="l-lst-dtl">
                    <li class="l-data" title="預け入れ申込日"><?php echo $this->Html->formatYmdKanjiDatetime($history_data['create_date']); ?></li>
                    <?php if($history_data['box_delivery_type'] == BOX_DELIVERY_TYPE_YOURSELF): ?>
                    <li class="l-method-dtl" title="入庫方法">自分で申込</li>
                    <?php else: ?>
                    <li class="l-method-dtl" title="入庫方法">集荷で申込</li>
                    <?php endif;?>
                    <?php if($history_data['works_progress_type'] == WORKS_PROGRESS_TYPE_COMPLETE): ?>
                    <li class="l-status l-left" title="ステータス">完了</li>
                    <?php else: ?>
                    <li class="l-status l-left" title="ステータス">入庫依頼中</li>
                    <?php endif;?>
                    <li class="l-view"><a class="btn" href="/inbound_history/detail?announcement_id=<?php echo $history_data['announcement_id']; ?>">詳細を見る</a></li>
                  </ul>
                </li>
                <?php endforeach; ?>
              </ul>
              <?php echo $this->element('paginator'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
