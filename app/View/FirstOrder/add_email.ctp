  <?php if (!empty($validErrors)) { $this->validationErrors['CustomerEmail'] = $validErrors; } ?>
  <section id="pagenation">
    <ul>
      <li><i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
      </li>
      <li><i class="fa fa-pencil-square-o"></i><span>お届け先<br>登録</span>
      </li>
      <li><i class="fa fa-credit-card"></i><span>カード<br>登録</span>
      </li>
      <li class="on"><i class="fa fa-envelope"></i><span>メール<br>登録</span>
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
        <label>メールアドレス<span class="required">※</span><br><span>半角英数記号でご入力ください。</span></label>
        <input class="mail" placeholder="terrada@minikura.com" size="28" maxlength="50">
        <span class="validation">validation,validation,validation,validation</span>
      </div>
      <div class="form">
        <label>パスワード<span class="required">※</span><br><span>半角英数記号8文字以上でご入力ください。</span></label>
        <input type="password" class="password" size="20" maxlength="20">
        <span class="validation">validation,validation,validation,validation</span>
      </div>
      <div class="form">
        <label>パスワード（確認用）<span class="required">※</span></label>
        <input type="password" class="password" size="20" maxlength="20">
        <span class="validation">validation,validation,validation,validation</span>
      </div>
      <div class="divider"></div>
      <div class="form form-line">
        <label>生年月日<span class="required">※</span></label>
        <select class="select-birth-year">
          <option value="">1985年</option>
          <option value="">1986年</option>
          <option value="">1987年</option>
          <option value="">1988年</option>
          <option value="">1989年</option>
          <option value="">1990年</option>
        </select>
        <select class="select-birth-month">
          <option value="">1月</option>
          <option value="">2月</option>
          <option value="">3月</option>
          <option value="">4月</option>
          <option value="">5月</option>
          <option value="">6月</option>
          <option value="">7月</option>
          <option value="">8月</option>
          <option value="">9月</option>
          <option value="">10月</option>
          <option value="">11月</option>
          <option value="">12月</option>
        </select>
        <select class="select-birth-day">
          <option value="">1日</option>
          <option value="">2日</option>
          <option value="">3日</option>
          <option value="">4日</option>
          <option value="">5日</option>
          <option value="">6日</option>
          <option value="">7日</option>
          <option value="">8日</option>
          <option value="">9日</option>
          <option value="">10日</option>
          <option value="">11日</option>
          <option value="">12日</option>
          <option value="">13日</option>
          <option value="">14日</option>
          <option value="">15日</option>
          <option value="">16日</option>
          <option value="">17日</option>
          <option value="">18日</option>
          <option value="">19日</option>
          <option value="">20日</option>
          <option value="">21日</option>
          <option value="">22日</option>
          <option value="">23日</option>
          <option value="">24日</option>
          <option value="">25日</option>
          <option value="">26日</option>
          <option value="">27日</option>
          <option value="">28日</option>
          <option value="">29日</option>
          <option value="">30日</option>
          <option value="">31日</option>
        </select>
        <span class="validation">validation,validation,validation,validation</span>
      </div>
      <div class="form form-line">
        <label>性別<span class="required">※</span></label>
        <label class="genders"><input type="radio" name="gender" value="man" id="man"><span class="check-icon"></span> <label for="man" class="gender">男</label></label><label class="genders"><input type="radio" name="gender" value="woman" id="woman"><span class="check-icon"></span> <label for="woman" class="gender">女</label></label>
      </div>
      <div class="divider"></div>
      <div class="form form-line">
        <label>お知らせメール</label>
        <select class="select-info">
          <option value="">受信する</option>
          <option value="">受信しない</option>
        </select>
      </div>
      <div class="form form-line">
        <label>紹介コード</label>
        <input type="text" size="20" maxlength="20">
      </div>
      <div class="divider"></div>
      <div class="form">
        <label class="terms"><input type="checkbox" class="term" id="term"><span class="check-icon"></span> <label for="term" class="term">minikura利用規約に同意する</label></label>
        <span class="validation">validation,validation,validation,validation</span>
        <a href="https://minikura.com/use_agreement/" target="_blank" class="link-terms"><i class="fa fa-chevron-circle-right"></i> minikura利用規約</a>
      </div>
    </div>
  </section>
  <section class="nextback"><a href="add_credit" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a><a href="confirm" class="btn-next">最後の確認へ <i class="fa fa-chevron-circle-right"></i></a>
  </section>
