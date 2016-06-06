<?php $this->Html->script('https://maps.google.com/maps/api/js?libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/address', ['block' => 'scriptMinikura']); ?>
<?php
$actionName = '追加';
if ($action === 'customer_edit') {
    $actionName = '変更';
} else if ($action === 'customer_delete') {
    $actionName = '削除';
}
$return = Hash::get($this->request->query, 'return');
?>
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-truck"></i>お届け先<?php echo $actionName; ?></h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <?php echo $this->Form->create('CustomerAddress', ['url' => ['controller' => 'address', 'action' => $action, 'step' => 'confirm', '?' => ['return' => $return]], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
        <div class="row">
          <div class="col-lg-12">
            <h2>お届け先<?php echo $actionName; ?></h2>
            <?php echo $this->Form->hidden('CustomerAddress.address_id'); ?>
            <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerAddress.postal', ['class' => "form-control search_address_postal", 'maxlength' => 8, 'placeholder'=>'郵便番号（入力すると以下の住所が自動で入力されます）', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerAddress.postal', null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerAddress.pref', ['class' => "form-control address_pref", 'maxlength' => 4, 'placeholder'=>'都道府県', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerAddress.pref', null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerAddress.address1', ['class' => "form-control address_address1", 'maxlength' => 8, 'placeholder'=>'住所', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerAddress.address1', null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerAddress.address2', ['class' => "form-control address_address2", 'maxlength' => 18, 'placeholder'=>'番地', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerAddress.address2', null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerAddress.address3', ['class' => "form-control", 'maxlength' => 30, 'placeholder'=>'建物名', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerAddress.address3', null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerAddress.tel1', ['class' => "form-control", 'maxlength' => 20, 'placeholder'=>'電話番号', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerAddress.tel1', null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerAddress.lastname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerAddress.lastname', null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerAddress.lastname_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'姓（カナ）', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerAddress.lastname_kana', null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerAddress.firstname', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerAddress.firstname', null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12">
                <?php echo $this->Form->input('CustomerAddress.firstname_kana', ['class' => "form-control", 'maxlength' => 29, 'placeholder'=>'名（カナ）', 'error' => false]); ?>
                <?php echo $this->Form->error('CustomerAddress.firstname_kana', null, ['wrap' => 'p']) ?>
            </div>
          </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
                <?php if ($action === 'customer_add'): ?>
                <a class="btn btn-primary btn-lg btn-block" href="/customer/address/add<?php echo (empty($return)) ? '' : '?return=' . $return; ?>"> クリア </a>
                <?php else: ?>
                <?php $url = '/customer/address/edit?address_id=' . $address_id;  ?>
                <a class="btn btn-primary btn-lg btn-block" href="<?php echo $url ?>"> クリア </a>
                <?php endif; ?>
            </span>
            <span class="col-lg-6 col-md-6 col-xs-12">
                <button type="submit" class="btn btn-danger btn-lg btn-block">確認する</button>
            </span>
        </div>
        <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
</div>
