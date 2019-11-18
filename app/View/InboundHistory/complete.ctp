    <div id="page-wrapper" class="wrapper inbound">
      <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> 預け入れ内容変更</h1>
      <p class="page-caption">預け入れ内容の変更を受け付けました。</p>
    </div>
    <div class="nav-fixed">
      <ul>
        <li>
        <?php if (isset($work_data['work_linkage_id'])) : ?>
          <a class="btn-red" href="/inbound_history/detail?wl_id=<?php echo $work_data['work_linkage_id']; ?>">預け入れ詳細に戻る</a>
        <?php else : ?>
          <a class="btn-red" href="/inbound_history/detail?w_id=<?php echo $work_data['work_id']; ?>">預け入れ詳細に戻る</a>
        <?php endif; ?>
        </li>
      </ul>
    </div>