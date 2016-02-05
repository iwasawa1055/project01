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
                <div class="col-lg-12 announcement">
                  <div class="row">
                    <div class="col-lg-12">
                      <h3><?php echo $announcement['title'] ?></h3>
                      <h4 class="date"><?php echo $this->Html->formatYmdKanji($announcement['date']); ?></h4>
                      <h5 class="date">お知らせID：<?php echo $announcement['announcement_id'] ?></h5>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="row body">
                      <?php echo nl2br($announcement['text']) ?>
                    </div>
                  </div>
                </div>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-primary btn-lg btn-block animsition-link" href="/announcement/">お知らせ一覧に戻る</a>
                </span>
                <span class="col-lg-6 col-md-6 col-xs-12">
                  <a class="btn btn-danger btn-lg btn-block animsition-link" href="/contact_us/add/<?php echo $announcement['announcement_id'] ?>">この内容について問い合わせる</a>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
