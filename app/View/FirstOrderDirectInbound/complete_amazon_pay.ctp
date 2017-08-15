<?php echo $this->element('FirstOrderDirectInbound/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>注文内容確認 - minikura</title>
<?php echo $this->element('FirstOrderDirectInbound/header'); ?>
<?php echo $this->element('FirstOrderDirectInbound/nav'); ?>
<!-- PAGENATION -->
  <section id="dsn-pagenation">
    <ul>
      <li><i class="fa fa-pencil-square-o"></i><span>集荷内容<br>登録</span>
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
<section id="dsn-adress" class="dsn-complete">
  <h2>預け入れ申し込みが完了しました。</h2>
  <div class="dsn-wrapper">
    <div class="dsn-form">
      <p class="dsn-dialog">預け入れ申し込みありがとうございました。</p>
      <p class="dsn-dialog">ご登録メールアドレスにお送りしました完了メールをご確認ください。</p>
  </div>
  </div>
</section>
<section class="dsn-nextback fix"><a href="/" class="dsn-btn-next-full" target="_blank">マイページトップへ進む <i class="fa fa-chevron-circle-right"></i></a>
</section>
<?php echo $this->element('FirstOrderDirectInbound/footer'); ?>
<?php echo $this->element('FirstOrderDirectInbound/js'); ?>
<?php # Affiliate Tag ?>
<img src="https://is.accesstrade.net/cgi-bin/isatV2/minikura/isatWeaselV2.cgi?result_id=100&verify=<?php echo $customer_id; ?>" width="1" height="1">
<img src="https://is.accesstrade.net/cgi-bin/isatV2/minikurap/isatWeaselV2.cgi?result_id=100&verify=<?php echo $customer_id; ?>" width="1" height="1">
<?php echo $this->element('FirstOrderDirectInbound/last'); ?>
