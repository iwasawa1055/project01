<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="keywords" content="minikura,あずける,トラクルーム,収納スペース">
  <meta name="description" content="箱であずかる収納サービス minikura。宅配便とWebでカンタン、詰めて送るだけ。クラウド収納でお部屋はもっと広くなる！">
  <meta name="author" content="">
  <?php echo $this->fetch('meta'); ?>
  <title>登録方法選択 - minikura</title>
  <?php
    $this->Html->css('bootstrap.min', ['inline' => false]);
    $this->Html->css('metisMenu.min', ['inline' => false]);
    $this->Html->css('font-awesome.min', ['inline' => false]);
    $this->Html->css('app', ['inline' => false]);
    $this->Html->css('style', ['inline' => false]);
    $this->Html->css('app_dev', ['inline' => false]);
  ?>
  <?php echo $this->fetch('css'); ?>
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
  <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
  <link rel="icon" type="images/png" href="/images/favicon-32x32.png" sizes="32x32">
  <link rel="icon" type="images/png" href="/images/favicon-16x16.png" sizes="16x16">
  <link rel="manifest" href="/images/manifest.json">
  <link rel="mask-icon" href="/images/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="theme-color" content="#ff0000">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
  <?php # 完了画面なら以下を表示 ?>
  <?php if ($_SERVER['REQUEST_URI'] == '/order/complete_card' || $_SERVER['REQUEST_URI'] == '/order/complete_bank' || $_SERVER['REQUEST_URI'] == '/order/complete_amazon_pay') : ?>
  <script type="text/javascript">
      var products_list = JSON.parse('<?php echo $order_list_criteo_json; ?>');
      var hashed_email = '<?php echo $this->App->getHashedEmail($customer); ?>';
      var transaction_id = '<?php echo $order_id; ?>';
      var dataLayer = dataLayer || [];
      dataLayer.push({
          'PageType': 'Transactionpage',
          'HashedEmail': hashed_email,
          'ProductTransactionProducts': products_list,
          'TransactionID': transaction_id
      });
  </script>
  <?php else : ?>
  <script type="text/javascript">
      var dataLayer = dataLayer || [];
      dataLayer.push({
          'PageType': 'Homepage',
          'HashedEmail': '<?php echo $this->App->getHashedEmail($customer); ?>'
      });
  </script>
  <?php endif; ?>
  <!-- Google Tag Manager -->
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-K4MN3W');</script>
  <!-- End Google Tag Manager -->
</head>

<body>
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K4MN3W"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  <div class='airloader-overlay'>
    <div class="loader">Loading...</div>
  </div>
  <div id="wrapper">
    <!--nav-->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>
        <?php echo $this->element('default_header_logo'); ?>
      </div>

      <?php if (!empty($customer) && $customer->isLogined()) : ?>
        <?php echo $this->element('navbar_right'); ?>
        <?php echo $this->element('sidebar'); ?>
      <?php else : ?>
        <?php echo $this->element('navbar_right_nonlogin'); ?>
        <?php //echo $this->element('sidebar_nonlogin'); ?>
      <?php endif; ?>
    </nav>

    <?php echo $this->fetch('content'); ?>

    <!--footer-->
    <footer>
      <nav class="footer-nav">
        <li><a href="https://minikura-logitech.com/" target="_blank">運営チーム</a>
        </li>
        <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/privacy/">個人情報について</a>
        </li>
        <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/security_policy/">セキュリティポリシー</a>
        </li>
        <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/commercial_transaction/">特定商取引に関する表記について</a>
        </li>
        <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/use_agreement/">利用規約</a>
        </li>
        <li><a href="/inquiry/add">お問い合わせ</a>
        </li>
      </nav>
      <p class="copyright">© Warehouse TERRADA</p>
      <input type='hidden' id='hashed_email' value='<?php echo $this->App->getHashedEmail($customer); ?>'>
    </footer>
  </div>

<?php
  $this->Html->script('jquery.min', ['inline' => false]);
  $this->Html->script('jquery-ui.min', ['inline' => false]);
  $this->Html->script('jquery.easing', ['inline' => false]);
  $this->Html->script('bootstrap.min', ['inline' => false]);
  $this->Html->script('metisMenu.min', ['inline' => false]);
  $this->Html->script('remodal.min', ['inline' => false]);
  $this->Html->script('iziModal.min', ['inline' => false]);
  $this->Html->script('app', ['inline' => false]);
  $this->Html->script('app_dev', ['inline' => false]);
  $this->Html->script('jquery.airCenter', ['inline' => false]);

  echo $this->fetch('script');
  echo $this->fetch('scriptMinikura');
?>

  <?php // アマゾンペイメント対応 ?>
  <?php if (!empty($customer) && $customer->isLogined()) : ?>
  <?php if ($customer->isAmazonPay()):?>
  <script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>"></script>
  <?php endif; ?>
  <?php endif; ?>

</body>

</html>
