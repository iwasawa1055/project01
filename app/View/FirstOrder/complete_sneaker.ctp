<?php echo $this->element('FirstOrder/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>注文内容確認 - minikura</title>
<?php echo $this->element('FirstOrder/header'); ?>
<?php echo $this->element('FirstOrder/nav'); ?>
<?php echo $this->element('FirstOrder/breadcrumb_list'); ?>
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
<section class="nextback fix"><a href="/index.php" class="btn-next-full" target="_blank">マイページトップへ進む <i class="fa fa-chevron-circle-right"></i></a>
</section>
<?php echo $this->element('FirstOrder/footer'); ?>
<?php echo $this->element('FirstOrder/js'); ?>
<?php echo $this->element('FirstOrder/last'); ?>
