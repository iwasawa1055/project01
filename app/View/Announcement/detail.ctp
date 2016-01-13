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
                <h2>お知らせ詳細</h2>
                <hr>
                <div class="col-lg-12 index-news">
                  <div class="row">
                    <div class="col-xs-12 col-md-2 col-lg-2"><?php echo $announcement['date'] ?></div>
                    <div class="col-xs-12 col-md-10 col-lg-10"><?php echo $announcement['title'] ?></div>
                  </div>
                  <div class="row">
                    <div class="col-xs-12 col-md-12 col-lg-12"><?php echo nl2br($announcement['text']) ?></div>
                    <hr>
                  </div>
                  <span class="col-lg- col-md-6 col-xs-12">
                  	<a class="btn btn-primary btn-lg btn-block animsition-link" href="/contact_us/add/<?php echo $announcement['announcement_id'] ?>">このお知らせについて問い合わせる</a>
                  </span>
                  <span class="col-lg-6 col-md-6 col-xs-12">
                  	<a class="btn btn-primary btn-lg btn-block animsition-link" href="/announcement/">戻る</a>
                  </span>
			    </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
