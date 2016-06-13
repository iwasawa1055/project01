  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"><i class="fa fa-bell"></i> ニュース</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <h2>ニュース一覧</h2>
              <div class="col-lg-12">
                <div class="col-lg-12 announcement">
              <?php foreach ($news as $data): ?>
                  <div class="row list">
                    <div class="col-xs-12 col-md-3 col-lg-3">
                        <?php echo $this->Html->formatYmdKanji($data['date']); ?>
                    </div>
                    <div class="col-xs-12 col-md-8 col-lg-8">
                      <span class="detail"><a href="/news/detail/<?php echo $data['id'];?>"><?php echo h($data['title']); ?></a></span>
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
