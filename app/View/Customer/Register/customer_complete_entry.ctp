
    <section id="page-wrapper" class="wrapper register">
      <ul class="pagenation">
        <li><span class="number">1</span><span class="txt">登録方法<br>選択</span>
        </li>
        <li><span class="number">2</span><span class="txt">お名前<br>入力</span>
        </li>
        <li><span class="number">3</span><span class="txt">ご住所<br>入力</span>
        </li>
        <li><span class="number">4</span><span class="txt">登録内容<br>確認</span>
        </li>
        <li class="on"><span class="number">5</span><span class="txt">登録完了</span>
        </li>
      </ul>
      <div class="content">
        <div class="register-complete">
          <h2 class="page-title">登録完了</h2>
          <p class="page-description">お客さま情報の登録が完了しました。</p>
          <p class="dialog">minikuraへのご登録ありがとうございました。
            <br>まずは用途に合ったサービスの申し込みをして<br class="sp">minikuraを始めましょう。</p>
        </div>
        <ul class="nextback">
          <li>
            <?php if(!empty(CakeSession::read('app.data.gift_cd'))): ?>
            <a href="/gift/receive/add" class="btn next">ギフト受け取りへ進む</a>
            <?php else: ?>
            <a href="/order/add" class="btn next">サービスの申し込みへ進む</a>
            <?php endif; ?>
          </li>
        </ul>
      </div>
    </section>
