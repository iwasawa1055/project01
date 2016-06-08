  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"><i class="fa fa-bell"></i> メッセージ</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <h2>メッセージ一覧</h2>
              <div class="col-lg-12">
                <div class="col-lg-12 announcement">
              <?php foreach ($announcements as $data): ?>
                <?php $url = '/announcement/detail/' . $data['announcement_id']; ?>
                  <div class="row list">
                    <div class="col-xs-12 col-md-3 col-lg-3">
                        <?php echo $this->Html->formatYmdKanji($data['date']); ?>
                    </div>
                    <div class="col-xs-12 col-md-8 col-lg-8">
                      <span class="detail"><a href="<?php echo $url; ?>"><?php echo h($data['title']); ?></a></span>
                    </div>
                    <div class="col-xs-12 col-md-1 col-lg-1">
                    <?php if ($data['read']): ?>
                      <a class="btn btn-success btn-xs" href="<?php echo $url; ?>">既読</a>
                    <?php else: ?>
                      <a class="btn btn-danger btn-xs" href="<?php echo $url; ?>">未読</a>
                    <?php endif; ?>
                    </div>
                  </div>
              <?php endforeach; ?>
                </div>
              </div>
              <?php echo $this->element('paginator'); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
