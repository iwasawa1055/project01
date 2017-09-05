<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<link href="/first_order_file/css/dsn-register.css" rel="stylesheet">
<title>注文内容確認 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>

<!-- PAGENATION -->
<section id="dsn-pagenation">
  <ul>
    <li><i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
    </li>
    <li><i class="fa fa-amazon"></i><span>Amazon<br>アカウントで<br>お支払い</span>
    </li>
    <li><i class="fa fa-check"></i><span>確認</span>
    </li>
    <li class="dsn-on"><i class="fa fa-truck"></i><span>完了</span>
    </li>
  </ul>
</section>
<!-- ADRESS -->
<section id="adress" class="complete">
  <h2>注文が完了しました。</h2>
  <div class="wrapper">
    <div class="form">
      <p class="dialog">専用ボックスのご注文ありがとうございました。</p>
      <p class="dialog">ご登録メールアドレスにお送りしました完了メールをご確認ください。</p>
  </div>
  </div>
</section>
<section class="nextback fix"><a href="/" class="btn-next-full" target="_blank">マイページトップへ進む <i class="fa fa-chevron-circle-right"></i></a>
</section>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<?php # Affiliate Tag ?>
<img src="https://is.accesstrade.net/cgi-bin/isatV2/minikura/isatWeaselV2.cgi?result_id=100&verify=<?php echo $customer_id; ?>" width="1" height="1">
<img src="https://is.accesstrade.net/cgi-bin/isatV2/minikurap/isatWeaselV2.cgi?result_id=100&verify=<?php echo $customer_id; ?>" width="1" height="1">
<?php echo $this->element('FirstOrder/last'); ?>
