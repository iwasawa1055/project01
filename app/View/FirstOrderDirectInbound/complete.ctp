<?php echo $this->element('FirstOrderDirectInbound/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>注文内容確認 - minikura</title>
<?php echo $this->element('FirstOrderDirectInbound/header'); ?>
<?php echo $this->element('FirstOrderDirectInbound/nav'); ?>
<?php echo $this->element('FirstOrderDirectInbound/breadcrumb_list'); ?>
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
