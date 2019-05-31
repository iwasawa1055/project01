    <section class="registry">
        <div class="container">
            <h2>お客様情報</h2>
            <ul class="input-form">
                <li>
                    <label>お名前</label>
                    <p class="confirm"><?php echo h($CustomerRegistInfo['lastname']); ?>　<?php echo h($CustomerRegistInfo['firstname']); ?></p>
                </li>
                <li>
                    <label>お名前 (カナ) </label>
                    <p class="confirm"><?php echo h($CustomerRegistInfo['lastname_kana']); ?>　<?php echo h($CustomerRegistInfo['firstname_kana']); ?></p>
                </li>
                <li>
                    <label>性別</label>
                    <p class="confirm"><?php echo ($CustomerRegistInfo['gender'] == 'm') ? '男性' : '女性'; ?></p>
                </li>
                <li>
                    <label>生年月日</label>
                    <p class="confirm"><?php echo h($CustomerRegistInfo['birth_year']); ?>年<?php echo h($CustomerRegistInfo['birth_month']); ?>月<?php echo h($CustomerRegistInfo['birth_day']); ?>日</p>
                </li>
            </ul>
            <h2>ご住所</h2>
            <ul class="input-form">
                <li>
                    <label>郵便番号</label>
                    <p class="confirm"><?php echo h($CustomerRegistInfo['postal']); ?></p>
                </li>
                <li>
                    <label>都道府県市区郡</label>
                    <p class="confirm"><?php echo h($CustomerRegistInfo['pref']); ?><?php echo h($CustomerRegistInfo['address1']); ?></p>
                </li>
                <li>
                    <label>町域以降</label>
                    <p class="confirm"><?php echo h($CustomerRegistInfo['address2']); ?></p>
                </li>
                <li>
                    <label>建物名以降</label>
                    <p class="confirm"><?php echo h($CustomerRegistInfo['address3']); ?></p>
                </li>
                <li>
                    <label>専用ボックスお届け希望日時</label>
                    <p class="confirm"><?php echo $this->App->convDatetimeCode($CustomerRegistInfo['datetime_cd']); ?></p>
                </li>
            </ul>
            <h2>ご連絡先</h2>
            <ul class="input-form">
                <li>
                    <label>お電話番号</label>
                    <p class="confirm"><?php echo h($CustomerRegistInfo['tel1']); ?></p>
                </li>
                <li>
                    <label>メールアドレス</label>
                    <p class="confirm"><?php echo h($CustomerRegistInfo['email']); ?></p>
                </li>
            </ul>
            <h2>その他情報</h2>
            <ul class="input-form">
                <li>
                    <label>紹介コード</label>
                    <p class="confirm">gvido</p>
                </li>
                <li>
                    <label>ニュースレターの配信</label>
                    <p class="confirm"><?php echo ($CustomerRegistInfo['newsletter'] == true) ? '配信する' : '配信しない'; ?></p>
                </li>
            </ul>
            <ul class="nav-block-2">
                <li><a class="btn-d-gray" href="/customer/gvido/add">戻る</a></li>
                <li><button class="btn-d-red" onclick="location.href='/customer/gvido/card'">カード情報入力</button></li>
            </ul>
        </div>
    </section>
