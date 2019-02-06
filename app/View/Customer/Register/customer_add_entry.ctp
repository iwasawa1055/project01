<?php
$this->Html->script('https://maps.google.com/maps/api/js?key=' . Configure::read('app.googlemap.api.key') . '&libraries=places', ['block' => 'scriptMinikura']);
$this->Html->script('minikura/address', ['block' => 'scriptMinikura']);
$this->Html->script('jquery.airAutoKana.js', ['block' => 'scriptMinikura']);
$this->Html->script('customer/register/add', ['block' => 'scriptMinikura']);
?>
    <section class="registry">
        <div class="container">
            <?php echo $this->Form->create('CustomerRegistInfo', ['url' => ['controller' => 'register', 'action' => 'customer_add_entry'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
            <?php $complete_error = $this->Flash->render('complete_error');?>
            <?php if (isset($complete_error)) : ?>
            <p class="valid-bl"><?php echo $complete_error; ?></p>
            <?php endif; ?>
            <h2>お客様情報</h2>
            <ul class="input-form">
                <li>
                    <label>お名前<span class="required">必須</span></label>
                    <ul class="col-name">
                        <li>
                            <?php echo $this->Form->input('CustomerRegistInfo.lastname', ['size' => 50, 'maxlength' => 50, 'placeholder'=>'例：寺田', 'class' => 'lastname', 'label' => false, 'error' => false, 'div' => false]); ?>
                            <?php echo $this->Form->error('CustomerRegistInfo.lastname', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                        <li>
                            <?php echo $this->Form->input('CustomerRegistInfo.firstname', ['size' => 50, 'maxlength' => 50, 'placeholder'=>'例：太郎', 'class' => 'firstname', 'label' => false, 'error' => false, 'div' => false]); ?>
                            <?php echo $this->Form->error('CustomerRegistInfo.firstname', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                    </ul>
                </li>
                <li>
                    <label>お名前 (カナ) <span class="required">必須</span></label>
                    <ul class="col-name">
                        <li>
                            <?php echo $this->Form->input('CustomerRegistInfo.lastname_kana', ['size' => 50, 'maxlength' => 50, 'placeholder'=>'例：テラダ', 'class' => 'lastname_kana', 'label' => false, 'error' => false, 'div' => false]); ?>
                            <?php echo $this->Form->error('CustomerRegistInfo.lastname_kana', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                        <li>
                            <?php echo $this->Form->input('CustomerRegistInfo.firstname_kana', ['size' => 50, 'maxlength' => 50, 'placeholder'=>'例：タロウ', 'class' => 'firstname_kana', 'label' => false, 'error' => false, 'div' => false]); ?>
                            <?php echo $this->Form->error('CustomerRegistInfo.firstname_kana', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                    </ul>
                </li>
                <li>
                    <label>性別<span class="required">必須</span></label>
                    <ul class="col-gender">
                        <li>
                            <label class="cb-circle">
                                <?php echo $this->Form->input('CustomerRegistInfo.gender', ['class' => 'cb', 'label' => false, 'error' => false, 'options' => ['m' => '<span class="icon-cb"></span><span class="txt-cb">男性</span>'], 'type' => 'radio', 'div' => false, 'hiddenField' => false]); ?>
                            </label>
                        </li>
                        <li>
                            <label class="cb-circle">
                                <?php echo $this->Form->input('CustomerRegistInfo.gender', ['class' => 'cb', 'label' => false, 'error' => false, 'options' => ['f' => '<span class="icon-cb"></span><span class="txt-cb">女性</span>'], 'type' => 'radio', 'div' => false, 'hiddenField' => false]); ?>
                            </label>
                        </li>
                    </ul>
                    <?php echo $this->Form->error('CustomerRegistInfo.gender', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
                <li class="birth">
                    <label>生年月日<span class="required">必須</span></label>
                    <ul class="col-birth">
                        <li>
                            <?php echo $this->Form->input('CustomerRegistInfo.birth_year', ['size' => 4, 'maxlength' => 4, 'placeholder'=>'2019', 'label' => false, 'error' => false, 'div' => false]); ?>
                            <span>年</span>
                        </li>
                        <li>
                            <?php echo $this->Form->select('CustomerRegistInfo.birth_month', array_combine(range(1, 12), range(1, 12)), ['class' => 'w90', 'empty' => false, 'label' => false, 'error' => false, 'div' => false]); ?>
                            <span>月</span>
                        </li>
                        <li>
                            <?php echo $this->Form->select('CustomerRegistInfo.birth_day', array_combine(range(1, 31), range(1, 31)), ['class' => 'w90', 'empty' => false, 'label' => false, 'error' => false, 'div' => false]); ?>
                            <span>日</span>
                        </li>
                    </ul>
                    <?php echo $this->Form->error('CustomerRegistInfo.birth', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
            </ul>
            <h2>ご住所</h2>
            <ul class="input-form">
                <li class="postal">
                    <label>郵便番号<span class="required">必須</span></label>
                    <?php echo $this->Form->input('CustomerRegistInfo.postal', ['maxlength' => 8, 'placeholder'=>'例：1400002', 'class' => 'search_address_postal', 'label' => false, 'error' => false, 'div' => false]); ?>
                    <p class="description">全角半角、ハイフンありなし、どちらでもご入力いただけます。<br>
                        入力すると以下の住所が自動で入力されます。</p>
                    <?php echo $this->Form->error('CustomerRegistInfo.postal', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
                <li>
                    <label>都道府県<span class="required">必須</span></label>
                    <?php echo $this->Form->input('CustomerRegistInfo.pref', ['size' => 16, 'maxlength' => 16, 'placeholder'=>'例：東京都', 'class' => 'address_pref', 'label' => false, 'error' => false, 'div' => false]); ?>
                    <?php echo $this->Form->error('CustomerRegistInfo.pref', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
                <li>
                    <label>市区郡（町村）<span class="required">必須</span></label>
                    <?php echo $this->Form->input('CustomerRegistInfo.address1', ['size' => 16, 'maxlength' => 16, 'placeholder'=>'例：品川区東品川', 'class' => 'address_address1', 'label' => false, 'error' => false, 'div' => false]); ?>
                    <?php echo $this->Form->error('CustomerRegistInfo.address1', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
                <li>
                    <label>丁目以降<span class="required">必須</span></label>
                    <?php echo $this->Form->input('CustomerRegistInfo.address2', ['size' => 10, 'maxlength' => 10, 'placeholder'=>'例：2-6-10', 'class' => 'address_address2', 'label' => false, 'error' => false, 'div' => false]); ?>
                    <?php echo $this->Form->error('CustomerRegistInfo.address2', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
                <li>
                    <label>建物名以降</label>
                    <?php echo $this->Form->input('CustomerRegistInfo.address3', ['size' => 23, 'maxlength' => 23, 'placeholder'=>'建物名・部屋番号をご入力ください。', 'label' => false, 'error' => false, 'div' => false]); ?>
                    <?php echo $this->Form->error('CustomerRegistInfo.address3', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
            </ul>
            <h2>ご連絡先</h2>
            <ul class="input-form">
                <li>
                    <label>お電話番号<span class="required">必須</span></label>
                    <?php echo $this->Form->input('CustomerRegistInfo.tel1', ['size' => 12, 'maxlength' => 12, 'placeholder'=>'例：0367129590', 'label' => false, 'error' => false, 'div' => false]); ?>
                    <p class="description">全角半角、ハイフンありなし、どちらでもご入力いただけます。</p>
                    <?php echo $this->Form->error('CustomerRegistInfo.tel1', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
                <li>
                    <label>メールアドレス<span class="required">必須</span></label>
                    <?php echo $this->Form->input('CustomerRegistInfo.email', ['maxlength' => 50, 'placeholder'=>'例：user@gvidomusic.com', 'label' => false, 'error' => false, 'div' => false]); ?>
                    <?php echo $this->Form->error('CustomerRegistInfo.email', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
                <li>
                    <label>パスワード<span class="required">必須</span></label>
                    <?php echo $this->Form->input('CustomerRegistInfo.password', ['placeholder'=>'例：aBcD1234', 'label' => false, 'error' => false, 'div' => false, 'type' => 'password']); ?>
                    <p class="description">半角英数記号8文字以上でご入力ください。</p>
                    <?php echo $this->Form->error('CustomerRegistInfo.password', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
                <li>
                    <label>パスワード（確認用） <span class="required">必須</span></label>
                    <?php echo $this->Form->input('CustomerRegistInfo.password_confirm', ['placeholder'=>'例：aBcD1234', 'label' => false, 'error' => false, 'div' => false, 'type' => 'password']); ?>
                    <?php echo $this->Form->error('CustomerRegistInfo.password_confirm', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
            </ul>
            <h2>その他情報</h2>
            <ul class="input-form">
                <li>
                    <label>紹介コード</label>
                    <?php echo $this->Form->input('CustomerRegistInfo.alliance_cd', ['readonly' => 'readonly', 'label' => false, 'error' => false, 'div' => false]); ?>
                </li>
                <li>
                    <label>ニュースレターの配信</label>
                    <?php echo $this->Form->select('CustomerRegistInfo.newsletter', ['1' => '配信する', '0' => '配信しない'], ['required' => 'required', 'empty' => false, 'label' => false, 'error' => false, 'div' => false]); ?>
                    <?php echo $this->Form->error('CustomerRegistInfo.newsletter', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
            </ul>
            <h2 class="heading icon-checkmark-circle">利用規約に同意</h2>
            <ul class="input-form">
                <li>
                    <label class="cb-square">
                        <input type="checkbox" class="cb" id="terms" value="1"><span class="icon-cb"></span><span class="txt-cb"><a href="https://minikura.com/privacy/">個人情報について</a>、<a href="https://minikura.com/use_agreement/">利用規約</a>に同意する</span>
                    </label>
                </li>
            </ul>
            <ul class="nav-block-1">
                <li><button type="button" class="btn-d-red" id="execute">確認</button></li>
            </ul>
            <?php echo $this->Form->end(); ?>
        </div>
    </section>
