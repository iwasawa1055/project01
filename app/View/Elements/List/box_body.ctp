<?php $url = '/box/detail/' . $box['box_id']; ?>
<div class="panel-body <?php echo $this->MyPage->boxClassName($box); ?>">
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12">
      <h3 class="box-item-name">
        <a href="<?php echo $url; ?>"><?php echo h($box['box_name']); ?></a>
      </h3>
    </div>
    <div class="col-lg-4 col-md-4 col-xs-12">
      <?php if($box['box_status'] != BOXITEM_STATUS_INBOUND_IN_PROGRESS): ?>
      <a class="btn btn-danger btn-md btn-block btn-detail animsition-link" href="<?php echo $url; ?>">ボックスの内容を確認する</a>
      <?php endif;?>
    </div>
    <?php if(!empty($box['search_flag'])) :?>
    <div class="col-lg-12 col-md-12 col-sm-12">
      <?php if(!empty($box['search_note_flag'])) :?>
        <p class="box-item-remarks"><?php echo $box['box_note'];?></p>
      <?php else:?>
        <p class="box-item-remarks">　　　　</p>
      <?php endif;?>
    </div>
    <?php endif;?>
  </div>
</div>
