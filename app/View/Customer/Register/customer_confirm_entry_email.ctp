
      <section id="page-wrapper" class="wrapper register">
        <ul class="pagenation">
          <li><span class="number">1</span><span class="txt">登録方法<br>選択</span>
          </li>
          <li><span class="number">2</span><span class="txt">お名前<br>入力</span>
          </li>
          <li><span class="number">3</span><span class="txt">ご住所<br>入力</span>
          </li>
          <li class="on"><span class="number">4</span><span class="txt">登録内容<br>確認</span>
          </li>
          <li><span class="number">5</span><span class="txt">完了</span>
          </li>
        </ul>
        <div class="content">
          <h2 class="page-title">登録内容確認</h2>
          <p class="page-description">以下の内容で会員登録を行います。</p>
          <ul class="input-info">
            <li>
              <label class="headline">お名前</label>
              <p class="txt-confirm"><?php echo h($CustomerRegistInfo['lastname']); ?>　<?php echo h($CustomerRegistInfo['firstname']); ?></p>
            </li>
            <li>
              <label class="headline">生年月日</label>
              <?php if (false): ?>
              <p class="txt-confirm"><?php echo h($CustomerRegistInfo['birth_year']); ?>年<?php echo h($CustomerRegistInfo['birth_month']); ?>月<?php echo h($CustomerRegistInfo['birth_day']); ?>日</p>
              <?php endif; ?>
            </li>
            <li>
              <label class="headline">性別</label>
              <p class="txt-confirm"><?php echo ($CustomerRegistInfo['gender'] == 'm') ? '男性' : '女性'; ?></p>
            </li>
            <li>
              <label class="headline">メールアドレス</label>
              <p class="txt-confirm"><?php echo h($CustomerRegistInfo['email']); ?></p>
            </li>
            <li>
              <label class="headline">お知らせメール</label>
              <p class="txt-confirm"><?php echo ($CustomerRegistInfo['newsletter'] == '1') ? '受信する' : '受信しない'; ?></p>
            </li>
            <li class="divider">
            </li>
            <li>
              <label class="headline">お届け先住所</label>
              <p class="txt-confirm">〒<?php echo h($CustomerRegistInfo['postal']); ?></p>
              <p class="txt-confirm"><?php echo h($CustomerRegistInfo['pref']); ?><?php echo h($CustomerRegistInfo['address1']); ?><?php echo h($CustomerRegistInfo['address2']); ?><?php echo h($CustomerRegistInfo['address3']); ?></p>
            </li>
            <li>
              <label class="headline">電話番号</label>
              <p class="txt-confirm"><?php echo h($CustomerRegistInfo['tel1']); ?></p>
            </li>
            <li>
              <label class="headline">紹介コード</label>
              <p class="txt-confirm"><?php echo h($CustomerRegistInfo['alliance_cd']); ?></p>
            </li>
          </ul>
          <ul class="nextback">
            <li>
              <a href="/customer/register/add_address_email" class="btn back">戻る</a>
            </li>
            <li>
              <a href="/customer/register/complete_entry_email" class="btn next">完了</a>
            </li>
          </ul>
        </div>
      </section>
