<?php $this->Html->script('minikura/announcement', ['block' => 'scriptMinikura']); ?>
<div id="page-wrapper">
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"><i class="fa fa-bell"></i> お知らせ</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <h2>お知らせ一覧</h2>
              <hr>
              <div class="col-lg-12 index-news">
                <?php foreach ($announcements as $data): ?>
                  <?php $url = '/announcement/detail/' . $data['announcement_id']; ?>
                  <div class="row">
                      <div class="col-xs-12 col-md-2 col-lg-2">
                          <?php echo $data['date']; ?>
                      </div>
                      <div class="col-xs-12 col-md-9 col-lg-9">
                          <a href="<?php echo $url; ?>" class="animsition-link"><?php echo $data['title']; ?></a>
                      </div>
                      <div class="col-xs-12 col-md-1 col-lg-1">
                      <?php if ($data['read']): ?>
                      <a class="btn btn-success btn-xs animsition-link" href="<?php echo $url; ?>">既読</a>
                      <?php else: ?>
                      <a class="btn btn-danger btn-xs animsition-link" href="<?php echo $url; ?>">未読</a>
                      <?php endif; ?>
                    </div>
                  </div>
                  <hr>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
