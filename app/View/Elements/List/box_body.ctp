<?php $url = '/box/detail/' . $box['box_id']; ?>
<div class="panel-body <?php echo $this->MyPage->boxClassName($box); ?>">
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12">
      <h3>
        <a href="<?php echo $url; ?>"><?php echo h($box['box_name']); ?></a>
      </h3>
    </div>
    <div class="col-lg-4 col-md-4 col-xs-12">
      <a class="btn btn-danger btn-md btn-block btn-detail" href="<?php echo $url; ?>">ボックスの内容を確認</a>
    </div>
  </div>
</div>
