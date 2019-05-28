<?php $this->Html->script('https://maps.google.com/maps/api/js?libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/pickup', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('pickup/edit', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('jquery.airAutoKana', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('pickupYamato', ['block' => 'scriptMinikura']); ?>
<?php
$return = Hash::get($this->request->query, 'return');
?>
<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header"><i class="fa fa-pencil-square-o"></i> 集荷情報変更</h1>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <div class="row">
          <?php echo $this->Form->create('Pickup', ['url' => ['controller' => 'pickup', 'action' => 'edit'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <div class="col-lg-12 none-title">
            <p class="form-control-static caution col-lg-12">注意：集荷情報変更で対応可能なのは住所・日程・時間のみとなり、ボックス内容や個数の変更等はお受けできかねます。</p>
            <div class="form-group col-lg-12">
              <label>集荷の住所</label>
              <select class="address select-add-address-pickup" name="data[PickupYamato][address_id]">
                  <?php foreach ($addressList as $data) : ?>
                  <option value="<?php echo $data['address_id']; ?>" <?php echo (isset($this->request->data['PickupYamato']['address_id']) && $this->request->data['PickupYamato']['address_id'] == $data['address_id']) ? 'selected' : ''; ?> data-address-name="<?php echo h("${data['lastname']}${data['firstname']}"); ?>">
                      <?php echo h("〒${data['postal']} ${data['pref']}${data['address1']}${data['address2']}${data['address3']}　${data['lastname']}${data['firstname']}"); ?>
                    </option>
                  <?php endforeach; ?>;
                  <option value="-99" <?php echo (isset($this->request->data['PickupYamato']['address_id']) && $this->request->data['PickupYamato']['address_id'] == 'add') ? 'selected' : ''; ?> data-address-name="">お届先を追加する</option>
              </select>
              <?php echo $this->Form->error('PickupYamato.address_id', null, ['wrap' => 'p']) ?>
              <div class="input-address">
                <label>お届け先追加</label>
                <div class="form-group">
                    <?php echo $this->Form->input('CustomerAddress.postal', ['class' => "form-control search_address_postal", 'maxlength' => 8, 'placeholder'=>'郵便番号（入力すると以下の住所が自動で入力されます）', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.postal', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('CustomerAddress.pref', ['class' => "form-control address_pref", 'placeholder'=>'都道府県', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.pref', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('CustomerAddress.address1', ['class' => "form-control address_address1", 'placeholder'=>'市区郡', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.address1', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('CustomerAddress.address2', ['class' => "form-control address_address2", 'placeholder'=>'町域以降', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.address2', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('CustomerAddress.address3', ['class' => "form-control", 'placeholder'=>'建物名', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.address3', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('CustomerAddress.tel1', ['class' => "form-control", 'maxlength' => 20, 'placeholder'=>'電話番号', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.tel1', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('CustomerAddress.lastname', ['id' => 'lastname', 'class' => "form-control name-last lastname focused", 'maxlength' => 29, 'placeholder'=>'姓', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.lastname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('CustomerAddress.lastname_kana', ['id' => 'lastname_kana', 'class' => "form-control name-last-kana lastname_kana focused", 'maxlength' => 29, 'placeholder'=>'姓（カナ）', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.lastname_kana', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('CustomerAddress.firstname', ['id' => 'firstname','class' => "form-control name-first firstname focused", 'maxlength' => 29, 'placeholder'=>'名', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.firstname', null, ['wrap' => 'p']) ?>
                </div>
                <div class="form-group">
                    <?php echo $this->Form->input('CustomerAddress.firstname_kana', ['id' => 'firstname_kana', 'class' => "form-control name-first-kana firstname_kana focused", 'maxlength' => 29, 'placeholder'=>'名（カナ）', 'error' => false]); ?>
                    <?php echo $this->Form->error('CustomerAddress.firstname_kana', null, ['wrap' => 'p']) ?>
                </div>
              </div>
            </div>
            <?php echo $this->Form->hidden('PickupYamato.hidden_pickup_date', ['id' => 'pickup_date']); ?>
            <?php echo $this->Form->hidden('PickupYamato.hidden_pickup_time_code', ['id' => 'pickup_time_code']); ?>
            <div class="form-group col-lg-12">
              <label>集荷の日程</label>
              <?php echo $this->Form->select('PickupYamato.pickup_date', [], ['id' => 'day_cd', 'class' => 'form-control select-pickup-date', 'empty' => '以下からお選びください', 'error' => false]); ?>
              <?php echo $this->Form->error('PickupYamato.pickup_date', null, ['wrap' => 'p']) ?>
            </div>
            <div class="form-group col-lg-12">
              <label>集荷の時間</label>
              <?php echo $this->Form->select('PickupYamato.pickup_time', [], ['id' => 'time_cd', 'class' => 'form-control select-pickup-time', 'empty' => '以下からお選びください', 'error' => false]); ?>
              <?php echo $this->Form->error('PickupYamato.pickup_time', null, ['wrap' => 'p']) ?>
            </div>
            <span class="col-lg-6 col-md-6 col-xs-12">
              <a class="btn btn-primary btn-lg btn-block" href="/announcement">戻る</a>
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
</div>

