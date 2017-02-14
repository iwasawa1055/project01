  <section id="pagenation">
    <ul>
      <li><i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
      </li>
      <li><i class="fa fa-pencil-square-o"></i><span>お届け先<br>登録</span>
      </li>
      <li class="on"><i class="fa fa-credit-card"></i><span>カード<br>登録</span>
      </li>
      <li><i class="fa fa-envelope"></i><span>メール<br>登録</span>
      </li>
      <li><i class="fa fa-check"></i><span>確認</span>
      </li>
      <li><i class="fa fa-truck"></i><span>完了</span>
      </li>
    </ul>
  </section>
  <!-- ADRESS -->
  <section id="adress">
    <div class="wrapper">
      <div class="form">
        <label>クレジットカード番号<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input class="name" placeholder="0000-0000-0000-0000" size="20" maxlength="20">
        <span class="validation">validation,validation,validation,validation</span>
      </div>
      <div class="form">
        <label>セキュリティコード<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input class="postal" placeholder="0123" size="6" maxlength="6">
        <span class="validation">validation,validation,validation,validation</span>
      </div>
      <div class="form">
        <label>カード有効期限<span class="required">※</span></label>
        <select class="select-month">
          <option value="">00月</option>
          <option value="">00月</option>
          <option value="">00月</option>
          <option value="">00月</option>
          <option value="">00月</option>
        </select>
        /
        <select class="select-year">
          <option value="">0000年</option>
          <option value="">0000年</option>
          <option value="">0000年</option>
          <option value="">0000年</option>
          <option value="">0000年</option>
        </select>
        <span class="validation">validation,validation,validation,validation</span>
      </div>
      <div class="form">
        <label>カード名義<span class="required">※</span></label>
        <input class="adress2" placeholder="TERRADA MINIKURA" size="28" maxlength="30">
        <span class="validation">validation,validation,validation,validation</span>
      </div>
    </div>
  </section>
  <section class="nextback"><a href="add_address" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a><a href="add_email" class="btn-next">メールアドレスを入力 <i class="fa fa-chevron-circle-right"></i></a>
  </section>
