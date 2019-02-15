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
          <li class="on"><span class="number">2</span><span class="txt">お名前<br>入力</span>
          </li>
          <li><span class="number">3</span><span class="txt">ご住所<br>入力</span>
          </li>
          <li><span class="number">4</span><span class="txt">登録内容<br>確認</span>
          </li>
          <li><span class="number">5</span><span class="txt">完了</span>
          </li>
        </ul>
        <div class="content">
          <?php echo $this->Form->create('CustomerRegistInfo', ['url' => ['controller' => 'register', 'action' => 'customer_add_personal'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <?php $complete_error = $this->Flash->render('complete_error');?>
          <?php if (isset($complete_error)) : ?>
          <p class="valid-bl"><?php echo $complete_error; ?></p>
          <?php endif; ?>
          <h2 class="page-title">お名前・メールアドレス入力</h2>
          <p class="page-description">お客さまのお名前・メールアドレス等をご入力ください。</p>
          <ul class="input-info input-form">
            <li>
              <label class="headline">お名前<span class="required">※</span></label>
              <ul class="col-name">
                <li>
                  <?php echo $this->Form->input('CustomerRegistInfo.lastname', ['size' => 10, 'maxlength' => 30, 'placeholder'=>'例：寺田', 'class' => 'lastname', 'label' => false, 'error' => false, 'div' => false]); ?>
                  <?php echo $this->Form->error('CustomerRegistInfo.lastname', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
                <li>
                  <?php echo $this->Form->input('CustomerRegistInfo.firstname', ['size' => 10, 'maxlength' => 30, 'placeholder'=>'例：太郎', 'class' => 'firstname', 'label' => false, 'error' => false, 'div' => false]); ?>
                  <?php echo $this->Form->error('CustomerRegistInfo.firstname', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
              </ul>
            </li>
            <li>
              <label class="headline">フリガナ<span class="required">※</span></label>
              <ul class="col-name">
                <li>
                  <?php echo $this->Form->input('CustomerRegistInfo.lastname_kana', ['size' => 10, 'maxlength' => 30, 'placeholder'=>'例：テラダ', 'class' => 'lastname_kana', 'label' => false, 'error' => false, 'div' => false]); ?>
                  <?php echo $this->Form->error('CustomerRegistInfo.lastname_kana', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
                <li>
                  <?php echo $this->Form->input('CustomerRegistInfo.firstname_kana', ['size' => 10, 'maxlength' => 30, 'placeholder'=>'例：タロウ', 'class' => 'firstname_kana', 'class' => 'firstname_kana', 'label' => false, 'error' => false, 'div' => false]); ?>
                  <?php echo $this->Form->error('CustomerRegistInfo.firstname_kana', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                </li>
              </ul>
            </li>
            <li>
              <label class=" headline">生年月日<span class="required">※</span></label>
              <ul class="col-birthday">
                <li>
                  <?php
                    $year_list = array();
                    for ($year = date('Y'); $year >= REGISTER_CUSTOMER_DEFAULT_BIRTH_START_YEAR; $year--) {
                        $year_list[$year] = $year . '年';
                    }
                    $default_year_key = array_search(REGISTER_CUSTOMER_DEFAULT_BIRTH_DEFAULT_YEAR, $year_list);
                    echo $this->Form->select('CustomerRegistInfo.birth_year', $year_list, ['empty' => false, 'label' => false, 'error' => false, 'div' => false, 'default' => $default_year_key]);
                  ?>
                </li>
                <li>
                  <?php
                    $month_list = array();
                    for ($month = 1; $month <= 12; ++$month) {
                        $month_list[$month] = $month . '月';
                    }
                    echo $this->Form->select('CustomerRegistInfo.birth_month', $month_list, ['empty' => false, 'label' => false, 'error' => false, 'div' => false]);
                  ?>
                </li>
                <li>
                  <?php
                    $day_list = array();
                    for ($day = 1; $day <= 31; ++$day) {
                        $day_list[$day] = $day . '日';
                    }
                    echo $this->Form->select('CustomerRegistInfo.birth_day', $day_list, ['empty' => false, 'label' => false, 'error' => false, 'div' => false]);
                  ?>
                </li>
              </ul>
            </li>
            <li>
              <label class="headline">性別<span class="required">※</span></label>
              <ul class="col-gender">
                <li>
                  <label class="input-check">
                    <?php
                      echo $this->Form->input(
                        'CustomerRegistInfo.gender',
                        [
                          'class' => 'rb',
                          'label' => false,
                          'error' => false,
                          'options' => [
                            'm' => '<span class="icon"></span><span class="label-txt">男性</span>'
                          ],
                          'type' => 'radio',
                          'div' => false,
                          'hiddenField' => false,
                          'checked' => 'checked'
                        ]
                      );
                    ?>
                  </label>
                </li>
                <li>
                  <label class="input-check">
                    <?php
                      echo $this->Form->input(
                        'CustomerRegistInfo.gender',
                        [
                          'class' => 'rb',
                          'label' => false,
                          'error' => false,
                          'options' => [
                            'f' => '<span class="icon"></span><span class="label-txt">女性</span>'
                          ],
                          'type' => 'radio',
                          'div' => false,
                          'hiddenField' => false
                        ]
                      );
                    ?>
                </label>
                </li>
              </ul>
            </li>
            <li>
              <label class="headline">メールアドレス<span class="required">※</span></label>
              <?php echo $this->Form->input('CustomerRegistInfo.email', ['size' => 28, 'maxlength' => 50, 'placeholder'=>'例：terrada@minikura.com', 'label' => false, 'error' => false, 'div' => false, 'readonly' => 'readonly']); ?>
              <?php echo $this->Form->error('CustomerRegistInfo.email', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
              <p class="txt-caption">半角英数記号でご入力ください。</p>
            </li>
            <li>
              <label class="headline">パスワード<span class="required">※</span></label>
              <?php echo $this->Form->input('CustomerRegistInfo.password', ['size' => 20, 'maxlength' => 20, 'placeholder'=>'例：aBcDeF12', 'label' => false, 'error' => false, 'div' => false, 'type' => 'password']); ?>
              <p class="txt-caption">半角英数記号8文字以上でご入力ください。</p>
              <?php echo $this->Form->error('CustomerRegistInfo.password', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            </li>
            <li>
              <label class="headline">パスワード（確認用）<span class="required">※</span></label>
              <?php echo $this->Form->input('CustomerRegistInfo.password_confirm', ['size' => 20, 'maxlength' => 20, 'placeholder'=>'例：aBcD1234', 'label' => false, 'error' => false, 'div' => false, 'type' => 'password']); ?>
              <?php echo $this->Form->error('CustomerRegistInfo.password_confirm', null, ['size' => 20, 'maxlength' => 20, 'wrap' => 'p', 'class' => 'valid-il']) ?>
            </li>
            <li>
              <label class="headline">お知らせメール<span class="required">※</span></label>
              <ul class="col-mail">
                <li>
                  <label class="input-check">
                    <?php
                      echo $this->Form->input(
                        'CustomerRegistInfo.newsletter',
                        [
                          'class' => 'rb',
                          'label' => false,
                          'error' => false,
                          'options' => [
                            '1' => '<span class="icon"></span><span class="label-txt">受信する</span>'
                          ],
                          'type' => 'radio',
                          'div' => false,
                          'hiddenField' => false,
                          'checked' => 'checked'
                        ]
                      );
                    ?>
                  </label>
                </li>
                <li>
                  <label class="input-check">
                    <?php
                      echo $this->Form->input(
                        'CustomerRegistInfo.newsletter',
                        [
                          'class' => 'rb',
                          'label' => false,
                          'error' => false,
                          'options' => [
                            '0' => '<span class="icon"></span><span class="label-txt">受信しない</span>'
                          ],
                          'type' => 'radio',
                          'div' => false,
                          'hiddenField' => false
                        ]
                      );
                    ?>
                  </label>
                </li>
              </ul>
            </li>
          </ul>
          <ul class="nextback">
            <li>
              <button type="submit" class="btn next">次へ</button>
            </li>
          </ul>
          <?php echo $this->Form->end(); ?>
        </div>
      </section>
