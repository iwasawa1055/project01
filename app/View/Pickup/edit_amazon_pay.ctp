<?php $this->Html->script('https://maps.google.com/maps/api/js?libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/pickup', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('jquery.airAutoKana', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('pickup/add_amazon_pay', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->css('/css/dsn-amazon-pay.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/add_amazon_pay_dev.css', ['block' => 'css']); ?>
<?php
$return = Hash::get($this->request->query, 'return');
?>
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-pencil-square-o"></i> 集荷日時変更</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <?php echo $this->Form->create('Pickup', ['url' => ['controller' => 'pickup', 'action' => 'edit_amazon_pay'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <div class="col-lg-12 none-title">
            <div class="form-group col-lg-12">
              <label>集荷の住所</label>
              <div id="dsn-amazon-pay" class="form-group col-lg-12">
                <div class="dsn-address">
                  <div id="addressBookWidgetDiv">
                  </div>
                </div>
              </div>

            <div class="form-group col-lg-12 inbound_pickup_only name-form-group">
              <label>お名前</label>
              <div class="dsn-form">
                <input type="text" name="lastname" class="dsn-name-last lastname focused" placeholder="寺田" size="10" maxlength="30">
                <input type="text" name="firstname" class="dsn-name-first firstname focused" placeholder="太郎" size="10" maxlength="30">
                <br>
                <?php echo $this->Form->error("Inbound.lastname", null, ['wrap' => 'p']) ?>
                <?php echo $this->Form->error("Inbound.firstname", null, ['wrap' => 'p']) ?>
              </div>
            </div>

            <?php echo $this->Form->hidden('PickupYamato.hidden_pickup_date', ['id' => 'pickup_date']); ?>
            <?php echo $this->Form->hidden('PickupYamato.hidden_pickup_time_code', ['id' => 'pickup_time_code']); ?>
            <div class="form-group col-lg-12">
              <label>集荷の日程</label>
              <?php echo $this->Form->select('PickupYamato.pickup_date', [], ['id' => 'select-pickup-date', 'class' => 'form-control select-pickup-date', 'empty' => '以下からお選びください', 'error' => false]); ?>
              <?php echo $this->Form->error('PickupYamato.pickup_date', null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12">
              <label>集荷の時間</label>
              <?php echo $this->Form->select('PickupYamato.pickup_time', [], ['id' => 'select-pickup-time', 'class' => 'form-control select-pickup-time', 'empty' => '以下からお選びください', 'error' => false]); ?>
              <?php echo $this->Form->error('PickupYamato.pickup_time', null, ['wrap' => 'p']) ?>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-primary btn-lg btn-block" href="/announcement">戻る</a>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <button type="submit" class="btn btn-danger btn-lg btn-block js-btn-submit" disabled="true" id="confirm">確認する</button>
            </span>
          </div>
          <?php echo $this->Form->end(); ?>
        </div>
      </div>
    </div>
  </div>
</div>

