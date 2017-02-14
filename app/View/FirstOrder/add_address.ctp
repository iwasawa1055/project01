  <section id="pagenation">
    <ul>
      <li><i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
      </li>
      <li class="on"><i class="fa fa-pencil-square-o"></i><span>お届け先<br>登録</span>
      </li>
      <li><i class="fa fa-credit-card"></i><span>カード<br>登録</span>
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
  <form method="post" action="/FirstOrder/confirm_address">
  <section id="adress">
    <div class="wrapper">
      <div class="form">
        <label>お名前<span class="required">※</span></label>
        <input type="text" name="lastname" class="name-last" placeholder="寺田" size="10" maxlength="30" required><input type="text" name="firstname" class="name-first" placeholder="太郎" size="10" maxlength="30" required>
        <span class="validation">validation,validation,validation,validation</span>
      </div>
      <div class="form">
        <label>フリガナ<span class="required">※</span></label>
        <input type="text" name="lastname_kana" class="name-last-kana" placeholder="テラダ" size="10" maxlength="30" required><input type="text" name="firstname_kana" class="name-first-kana" placeholder="タロウ" size="10" maxlength="30" required>
        <span class="validation">validation,validation,validation,validation</span>
      </div>
      <div class="divider"></div>
      <div class="form">
        <label>郵便番号<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。<br>入力すると以下の住所が自動で入力されます。</span></label>
        <input type="text" name="postal" class="postal" placeholder="0123456" size="8" maxlength="8" required>
        <span class="validation">validation,validation,validation,validation</span>
      </div>
      <div class="form">
        <label>都道府県市区郡（町村）<span class="required">※</span></label>
        <input type="text" name="address1" class="adress1" placeholder="東京都品川区東品川" size="28" maxlength="50" required>
        <span class="validation">validation,validation,validation,validation</span>
      </div>
      <div class="form">
        <label>丁目以降<span class="required">※</span></label>
        <input type="text" name="address2" class="adress2" placeholder="2-2-28" size="28" maxlength="50" required>
        <span class="validation">validation,validation,validation,validation</span>
      </div>
      <div class="form">
        <label>建物名</label>
        <input type="text" name="address3" class="build" placeholder="Tビル" size="28" maxlength="50">
      </div>
      <div class="divider"></div>
      <div class="form">
        <label>電話番号<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
        <input type="text" name="tel1" class="tel" placeholder="01234567890" size="15" maxlength="15" required>
        <span class="validation">validation,validation,validation,validation</span>
      </div>
      <div class="divider"></div>
      <div class="form">
        <label>お届け希望日<span class="required">※</span></label>
        <select name="datetime_cd" class="select-delivery" required>
          <option value="">0000年00月00日 午前中</option>
          <option value="">0000年00月00日 12時〜</option>
          <option value="">0000年00月00日 14時〜</option>
          <option value="">0000年00月00日 16時〜</option>
          <option value="">0000年00月00日 18時〜</option>
        </select>
        <span class="validation">validation,validation,validation,validation</span>
      </div>
    </div>
  </section>
  <section class="nextback"><a href="add_order" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a><button type="submit" class="btn-next">クレジットカード情報を入力<i class="fa fa-chevron-circle-right"></i></button>  </section>
  </form>
