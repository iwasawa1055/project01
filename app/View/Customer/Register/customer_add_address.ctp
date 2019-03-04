<?php
$this->Html->script('https://maps.google.com/maps/api/js?key=' . Configure::read('app.googlemap.api.key') . '&libraries=places', ['block' => 'scriptMinikura']);
$this->Html->script('minikura/address', ['block' => 'scriptMinikura']);
$this->Html->script('jquery.airAutoKana.js', ['block' => 'scriptMinikura']);
$this->Html->script('customer/register/add', ['block' => 'scriptMinikura']);
?>
      <section id="page-wrapper" class="wrapper register">
        <ul class="pagenation">
          <li><span class="number">1</span><span class="txt">登録方法<br>選択</span>
          </li>
          <li><span class="number">2</span><span class="txt">お名前<br>入力</span>
          </li>
          <li class="on"><span class="number">3</span><span class="txt">ご住所<br>入力</span>
          </li>
          <li><span class="number">4</span><span class="txt">登録内容<br>確認</span>
          </li>
          <li><span class="number">5</span><span class="txt">完了</span>
          </li>
        </ul>
        <div class="content">
          <?php echo $this->Form->create('CustomerRegistInfo', ['url' => ['controller' => 'register', 'action' => 'customer_add_address'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <?php $complete_error = $this->Flash->render('complete_error');?>
          <?php if (isset($complete_error)) : ?>
          <p class="valid-bl"><?php echo $complete_error; ?></p>
          <?php endif; ?>
          <h2 class="page-title">ご住所・電話番号入力</h2>
          <p class="page-description">お客さまのご住所・電話番号等をご入力ください。</p>
          <ul class="input-info">
            <li>
              <label class="headline">郵便番号<span class="required">※</span></label>
              <?php echo $this->Form->input('CustomerRegistInfo.postal', ['size' => 8, 'maxlength' => 8, 'placeholder'=>'例：012 3456', 'class' => 'search_address_postal', 'label' => false, 'error' => false, 'div' => false]); ?>
              <p class="txt-caption">全角半角、ハイフンありなし、どちらでもご入力いただけます。<br>入力すると以下の住所が自動で入力されます。</p>
              <?php echo $this->Form->error('CustomerRegistInfo.postal', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            </li>
            <li>
              <label class="headline">都道府県<span class="required">※</span></label>
              <?php echo $this->Form->input('CustomerRegistInfo.pref', ['size' => 28, 'maxlength' => 50, 'placeholder'=>'例：東京都', 'class' => 'address_pref', 'label' => false, 'error' => false, 'div' => false]); ?>
              <?php echo $this->Form->error('CustomerRegistInfo.pref', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            </li>
            <li>
              <label class="headline">市区郡（町村）<span class="required">※</span></label>
              <?php echo $this->Form->input('CustomerRegistInfo.address1', ['size' => 28, 'maxlength' => 50, 'placeholder'=>'例：品川区東品川2', 'class' => 'address_address1', 'label' => false, 'error' => false, 'div' => false]); ?>
              <?php echo $this->Form->error('CustomerRegistInfo.address1', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            </li>
            <li>
              <label class="headline">番地<span class="required">※</span></label>
              <?php echo $this->Form->input('CustomerRegistInfo.address2', ['size' => 28, 'maxlength' => 50, 'placeholder'=>'例：2-28', 'class' => 'address_address2', 'label' => false, 'error' => false, 'div' => false]); ?>
              <?php echo $this->Form->error('CustomerRegistInfo.address2', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            </li>
            <li>
              <label class="headline">建物名</label>
              <?php echo $this->Form->input('CustomerRegistInfo.address3', ['size' => 28, 'maxlength' => 50, 'placeholder'=>'例：Tビル 7階', 'label' => false, 'error' => false, 'div' => false]); ?>
              <?php echo $this->Form->error('CustomerRegistInfo.address3', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            </li>
            <li>
              <label class="headline">電話番号<span class="required">※</span></label>
              <?php echo $this->Form->input('CustomerRegistInfo.tel1', ['size' => 15, 'maxlength' => 15, 'placeholder'=>'例：012 3456 7890', 'label' => false, 'error' => false, 'div' => false]); ?>
              <p class="txt-caption">全角半角、ハイフンありなし、どちらでもご入力いただけます。</p>
              <?php echo $this->Form->error('CustomerRegistInfo.tel1', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>

            </li>
            <li>
              <label class="headline">紹介コード</label>
              <?php echo $this->Form->input('CustomerRegistInfo.alliance_cd', ['size' => 20, 'maxlength' => 20, 'label' => false, 'error' => false, 'div' => false]); ?>
            </li>
            <li>
              <label class="input-check icon-checkmark-circle">
                <input type="checkbox" class="cb-square" id="terms"><span class="icon"></span><span class="label-txt"><a href="https://minikura.com/use_agreement/" class="link" target="_blank">minikura利用規約</a>に同意する</span>
              </label>
            </li>
          </ul>
          <ul class="nextback">
            <li>
              <a href="/customer/register/add_personal" class="btn back">戻る</a>
            </li>
            <li>
              <button type="button" class="btn next" id="execute">次へ</button>
            </li>
          </ul>
          <?php echo $this->Form->end(); ?>
        </div>
      </section>
