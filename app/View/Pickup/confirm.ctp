<?php
$return = Hash::get($this->request->query, 'return');
?>
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-pencil-square-o"></i> 集荷情報変更</h1>
  </div>
</div>
<div class="row">
<?php echo $this->Form->create('PickupAddress', ['url' => '/pickup/complete', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true ]); ?>
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12 none-title">
            <div class="form-group col-lg-12">
              <label>集荷の住所</label>
              <p class="form-control-static">
              <?php echo '〒' . h($pickup_confirm['postal']); ?>
              &nbsp;
              <?php echo h($pickup_confirm['pref'].$pickup_confirm['address1'].$pickup_confirm['address2'].$pickup_confirm['address3']); ?>
              &nbsp;
              <?php echo h($pickup_confirm['name']); ?>
              </p>
            </div>
            <div class="form-group col-lg-12">
              <label>集荷の日程</label>
              <p class="form-control-static">
              <?php echo h($pickup_confirm['pickup_date_text']); ?>
              </p>
            </div>
            <div class="form-group col-lg-12">
              <label>集荷の時間</label>
              <p class="form-control-static">
              <?php echo h($pickup_confirm['pickup_time_text']); ?>
              </p>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
                <?php echo $this->Html->link('戻る', ['controller' => 'pickup', 'action' => 'edit', '?' => ['back' => 'true', 'return' => Hash::get($this->request->query, 'return')]], ['class' => 'btn btn-primary btn-lg btn-block']); ?>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <button type="submit" class="btn btn-danger btn-lg btn-block">変更する</button>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php echo $this->Form->end(); ?>
</div>

