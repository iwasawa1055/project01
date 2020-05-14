
      <section id="page-wrapper" class="wrapper register">
        <ul class="pagenation">
          <li class="on"><span class="number">1</span><span class="txt">登録方法<br>選択</span>
          </li>
          <li><span class="number">2</span><span class="txt">お名前<br>入力</span>
          </li>
          <li><span class="number">3</span><span class="txt">ご住所<br>入力</span>
          </li>
          <li><span class="number">4</span><span class="txt">登録内容<br>確認</span>
          </li>
          <li><span class="number">5</span><span class="txt">完了</span>
          </li>
        </ul>
        <div class="content">
          <h2 class="page-title">登録方法選択</h2>
          <?php echo $this->Flash->render(); ?>
          <ul class="col-login">
            <li>
              <h3>SNSアカウントで新規会員登録</h3>
              <a href="javascript:void(0);" class="btn fb btn-facebook dev_facebook_regist"><img src="/images/icon-fb.svg" class="icon">Facebookで登録</a>
              <p class="txt-caption">minikuraが許可なくお客さまのFacebookへ投稿することはございません。</p>
              <?php echo $this->Form->error('FacebookUser.facebook', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            </li>
            <li>
              <div class="g-signin2" onclick="signIn();">Google Sign In</div>
              <p class="txt-caption">minikuraが許可なくお客さまのGoogleを操作することはございません。</p>
              <?php echo $this->Form->error('GoogleUser.google', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
            </li>
            <li>
              <h3>メールアドレスで新規会員登録</h3>
              <?php echo $this->Form->create('CustomerRegistInfo', ['url' => ['controller' => 'register', 'action' => 'customer_add'], 'novalidate' => true]); ?>

              <?php echo $this->Form->input('CustomerRegistInfo.email', ['class' => "form-control", 'placeholder'=>'例：terrada@minikura.com', 'size' => '28', 'maxlength' => '50', 'error' => false, 'label' => false, 'div' => false]); ?>
              <p class="txt-caption">半角英数記号でご入力ください。</p>
              <?php echo $this->Form->error('CustomerRegistInfo.email', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
              <button type="submit" class="btn send-mail">送信する</button>
              <?php echo $this->Form->end(); ?>
            </li>
          </ul>
        </div>

        <div class="campaign">
          <a class="lnk" href="https://minikura.com/2box1free/" target="_blank">
              <picture>
                  <source media="(min-width: 768px)" srcset="/images/bnr2box1free-pc@1x.png 1x, /images/bnr2box1free-pc@2x.png 2x">
                  <source media="(max-width: 767px)" srcset="/images/bnr2box1free-sp@2x.png 1x, /images/bnr2box1free-sp@2x.png 2x">
                  <img src="/images/bnr2box1free-pc@1x.png" alt="1箱ずーっと無料 新規の登録で2Box1Free" class="img">
              </picture>
          </a>
        </div>
      </section>
      <?php echo $this->Form->create('FacebookUser', ['url' => ['controller' => 'register', 'action' => 'customer_complete_facebook'], "id" => "dev_id_facebook_registform", 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
      <?php echo $this->Form->hidden('FacebookUser.access_token', ['value'=>'', 'label' => false, 'error' => false, 'div' => false]); ?>
      <?php echo $this->Form->hidden('FacebookUser.facebook_user_id', ['value'=>'', 'label' => false, 'error' => false, 'div' => false]); ?>
      <?php echo $this->Form->hidden('FacebookUser.email', ['value'=>'', 'label' => false, 'error' => false, 'div' => false]); ?>
      <?php echo $this->Form->hidden('FacebookUser.firstname', ['value'=>'', 'label' => false, 'error' => false, 'div' => false]); ?>
      <?php echo $this->Form->hidden('FacebookUser.lastname', ['value'=>'', 'label' => false, 'error' => false, 'div' => false]); ?>
      <?php echo $this->Form->end(); ?>

      <?php echo $this->Form->create('GoogleUser', ['url' => ['controller' => 'register', 'action' => 'customer_complete_google'], "id" => "dev_id_google_registform", 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
      <?php echo $this->Form->hidden('GoogleUser.access_token', ['value'=>'', 'label' => false, 'error' => false, 'div' => false]); ?>
      <?php echo $this->Form->hidden('GoogleUser.id_token', ['value'=>'', 'label' => false, 'error' => false, 'div' => false]); ?>
      <?php echo $this->Form->end(); ?>

