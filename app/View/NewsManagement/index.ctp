  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"><i class="fa fa-cog"></i> ニュース編集</h1>
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
                <?php if (empty($news)) :?>
                  <div class="row">
                    <p>ニュースが取得できませんでした。xml ファイルが存在するかご確認ください。</p>
                  </div>
                <?php else:?>
                  <?php foreach ($news as $data): ?>
                    <div class="row list">
                      <div class="col-xs-12 col-md-3 col-lg-3">
                          <?php echo $this->Html->formatYmdKanji($data['disp_date']); ?>
                      </div>
                      <div class="col-xs-12 col-md-8 col-lg-8">
                        <span class="detail"><a href="/news_management/detail/<?php echo $data['id'];?>/edit"><?php echo h($data['title']); ?></a></span>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php endif;?>
                </div>
              </div>
              <?php echo $this->element('paginator'); ?>
            <?php if (! empty($news)) :?>
              <div class="col-lg-12">
                <span class="col-lg-12 col-md-12 col-xs-12">
                  <a class="btn btn-danger btn-lg btn-block" href="/news_management/add">登録する</a>
                </span>
              </div>
            <?php endif;?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
