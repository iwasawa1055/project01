<?php $url = '/box/detail/' . $box['box_id']; ?>
<a name="<?php echo $box['box_id'] ?>">
<div class="panel-body <?php echo $this->MyPage->boxClassName($box); ?>">
  <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12">
      <h3 class="boxitem-name">
        <a href="<?php echo $url; ?>"><?php echo h($box['box_name']); ?></a>
      </h3>
    </div>
    <div class="col-lg-4 col-md-4 col-xs-12">
      <?php echo $this->Form->create(false, ['url' => '/outbound/box', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
      <?php echo $this->Form->hidden("box_id.${box['box_id']}", ['value' => '0']); ?>
      <span class="col-xs-12 col-lg-12">
          <button type="submit" class="btn btn-warning btn-md btn-block btn-detail">取り出しリストから削除する</button>
      </span>
      <?php echo $this->Form->end(); ?>
    </div>
  </div>
</div>
