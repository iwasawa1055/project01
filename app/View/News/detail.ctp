<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-newspaper-o"></i> ニュース</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
        <?php if (!empty($news[0])): ?>
          <div class="col-lg-12">
            <div class="col-lg-12 announcement">
              <div class="row">
                <div class="col-lg-12">
                    <h3><?php echo $news[0]['title'];?></h3>
                    <h4 class="date"><?php echo $news[0]['date'];?></h4>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="row body">
                  <?php echo $news[0]['detail'];?>
                </div>
              </div>
            </div>
            <span class="col-lg-12 col-md-12 col-xs-12">
              <a class="btn btn-primary btn-lg btn-block animsition-link" href="/news">ニュース一覧に戻る</a>
            </span>
          </div>
        <?php endif;?>
        </div>
      </div>
    </div>
  </div>
</div>
