
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
      </section>
      <form id="dev_id_facebook_registform" method="POST" action="/customer/register/complete_facebook">
        <input type="hidden" name="facebook_user_id" value="">
        <input type="hidden" name="facebook_email" value="">
        <input type="hidden" name="facebook_first_name" value="">
        <input type="hidden" name="facebook_last_name" value="">
        <?php if (false): ?>
        <!--TODO facebookへ申請する必要あり-->
        <input type="hidden" name="facebook_gender" value="">
        <input type="hidden" name="facebook_birthday" value="">
        <input type="hidden" name="facebook_location" value="">
        <?php endif; ?>

      </form>
