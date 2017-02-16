<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>メールアドレス・パスワード入力 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>
<section id="pagenation">
  <ul>
    <li><i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
    </li>
    <li><i class="fa fa-pencil-square-o"></i><span>お届け先<br>登録</span>
    </li>
    <li><i class="fa fa-credit-card"></i><span>カード<br>登録</span>
    </li>
    <li><i class="fa fa-envelope"></i><span>メール<br>登録</span>
    </li>
    <li class="on"><i class="fa fa-check"></i><span>確認</span>
    </li>
    <li><i class="fa fa-truck"></i><span>完了</span>
    </li>
  </ul>
</section>
  <!-- ADRESS -->
  <section id="adress">
    <div class="wrapper">
      <div class="form">
        <label>ご注文内容</label>
        <table>
          <thead>
            <tr>
              <td>商品名</td>
              <td>個数</td>
              <td>価格</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>minikura スターターパック</th>
              <td>1</td>
              <td>000円</td>
            </tr>
            <tr>
              <th>MONO レギュラーボックス</th>
              <td>1</td>
              <td>000円</td>
            </tr>
            <tr>
              <th>HAKO レギュラーボックス</th>
              <td>1</td>
              <td>000円</td>
            </tr>
            <tr>
              <th>クリーニングパック</th>
              <td>1</td>
              <td>000円</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="divider"></div>
      <div class="form">
        <label>お届け先住所</label>
        <p>〒<?php echo $Address['postal'];?></p>
        <p><?php echo $Address['pref'];?><?php echo $Address['address1'];?><?php echo $Address['address2'];?> <?php echo $Address['address3'];?></p>
        <p><?php echo $Address['lastname'];?>　<?php echo $Address['firstname'];?></p>
        <p><?php echo $Address['lastname_kana'];?>　<?php echo $Address['firstname_kana'];?></p>
        <p><?php echo $Address['tel1'];?></p>
      </div>
      <div class="form">
        <label>お届け日時</label>
        <p>0000年00月00日 午前中</p>
      </div>
      <div class="divider"></div>
      <div class="form">
        <label>メールアドレス</label>
        <p><?php echo $Email['email'];?></p>
      </div>
      <div class="form">
        <label>お知らせメール</label>
        <p>
	  <?php if ( $Email['newsletter'] === "1" ) : ?>
	    希望する
	  <?php else: ?>
	    希望しない
	  <?php endif ?>
	</p>
      </div>
    </div>
  </section>
  <section class="nextback"><a href="/first_order/add_email?back=true" class="btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a><a href="complete" class="btn-next">この内容でボックスを購入 <i class="fa fa-chevron-circle-right"></i></a>
  </section>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<?php echo $this->element('FirstOrder/last'); ?>
