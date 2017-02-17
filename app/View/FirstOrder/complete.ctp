<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>注文内容確認 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>
<!-- PAGENATION -->
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
    <li><i class="fa fa-check"></i><span>確認</span>
    </li>
    <li class="on"><i class="fa fa-truck"></i><span>完了</span>
    </li>
  </ul>
</section>
<!-- ADRESS -->
<section id="adress" class="complete">
  <h2>注文が完了しました。</h2>
  <div class="wrapper">
    <div class="form">
      <p class="dialog">専用ボックスのご注文ありがとうございました。</p>
      <p class="dialog"><strong><></strong>にご指定いただいた住所へお届けする予定です。お届けまでしばらくお待ちください。</p>
      <p class="dialog">またお届けまでのあいだ、<a href="https://minikura.com/help/packing.html" target="_blank">専用ボックスの到着から預け入れまで <i class="fa fa-external-link-square"></i></a> をお読みいただけると、その後のお手続きがスムーズに進みますので併せてご確認ください。</p>
  </div>
  </div>
</section>
<section class="nextback"><a href="/index.php" class="btn-next-full">マイページトップへ進む <i class="fa fa-chevron-circle-right"></i></a>
</section>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<?php echo $this->element('FirstOrder/last'); ?>
