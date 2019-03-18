<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<meta name="keywords" content="minikura,あずける,トラクルーム,収納スペース">
<meta name="description" content="箱であずかる収納サービス minikura。宅配便とWebでカンタン、詰めて送るだけ。クラウド収納でお部屋はもっと広くなる！">
<meta property="og:locale" content="ja_JP" />
<meta property="og:site_name"  content="minikura" />
<meta property="og:title" content="minikura" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo Configure::read('site.static_content_url');?>" />
<meta property="og:description" content="箱であずかる収納サービス minikura。宅配便とWebでカンタン、詰めて送るだけ。クラウド収納でお部屋はもっと広くなる！" />
<meta name="twitter:card" content="summary" />
<meta name="format-detection" content="telephone=no">

<title><?php $this->Title->p(); ?></title>
<?php
  echo $this->fetch('meta');

  $this->Html->css('bootstrap.min', ['inline' => false]);
  $this->Html->css('font-awesome.min', ['inline' => false]);
  $this->Html->css('metisMenu.min', ['inline' => false]);
  $this->Html->css('animsition', ['inline' => false]);
  $this->Html->css('remodal', ['inline' => false]);
  $this->Html->css('remodal-theme', ['inline' => false]);
  $this->Html->css('app', ['inline' => false]);
  $this->Html->css('app_dev', ['inline' => false]);
  $this->Html->css('app_sneakers', ['inline' => false]);
  $this->Html->css('bootstrap-social', ['inline' => false]);

  echo $this->fetch('css');
?>
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
<?php /* ?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K4MN3W');</script>
<!-- End Google Tag Manager -->
<?php */ ?>
</head>
<body>
<?php /* ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K4MN3W"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php */ ?>
<div class='airloader-overlay'>
  <div class="loader">Loading...</div>
</div>
<div id="wrapper">
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
  <?php if (!empty($customer) && $customer->isLogined()) : ?>
  <div id="page-wrapper">
    <?php echo $this->Flash->render(); ?>
    <?php echo $this->fetch('content'); ?>
  </div>
  <?php else : ?>
    <div class="col-lg-12 login-wrapper" id="js-agreement_on_page">
      <?php echo $this->Flash->render(); ?>
      <?php echo $this->fetch('content'); ?>
    </div>
  <?php endif; ?>
  <div class="footer">
    <div class="col-xs-12">
      <ul class="list-inline">
        <li><a href="https://minikura-logitech.com/" target="_blank">運営チーム</a>
        </li>
        <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/privacy/" target="_blank">個人情報について</a>
        </li>
        <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/security_policy/" target="_blank">セキュリティポリシー</a>
        </li>
        <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/commercial_transaction/" target="_blank">特定商取引に関する表記について</a>
        </li>
        <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/use_agreement/" target="_blank">利用規約</a>
        </li>
        <li><a href="/inquiry/add">お問い合わせ</a>
        </li>
      </ul>
    </div>
    <div class="col-xs-12">
      <p>© 2012 Warehouse TERRADA</p>
    </div>
  </div>
</div>

<?php
  $this->Html->script('jquery.min', ['inline' => false]);
  $this->Html->script('bootstrap.min', ['inline' => false]);
  $this->Html->script('metisMenu.min', ['inline' => false]);
  $this->Html->script('animsition.min', ['inline' => false]);
  $this->Html->script('remodal.min', ['inline' => false]);
  $this->Html->script('iziModal.min', ['inline' => false]);
  $this->Html->script('app', ['inline' => false]);
  $this->Html->script('app_dev', ['inline' => false]);
  $this->Html->script('jquery.airCenter', ['inline' => false]);

  echo $this->fetch('script');
  echo $this->fetch('scriptMinikura');
?>
<!--[if lte IE 9]>
    <script type="text/javascript" src="/js/jquery.placeholder.min.js"></script>
    <script>
    $(function () {
        $('input, textarea').placeholder();
    });
    </script>
<![endif]-->

<?php // アマゾンペイメント対応 ?>
<?php if (!empty($customer) && $customer->isLogined()) : ?>
  <?php if ($customer->isAmazonPay()):?>
  <script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>"></script>
  <?php endif; ?>
<?php endif; ?>

</body>
</html>
