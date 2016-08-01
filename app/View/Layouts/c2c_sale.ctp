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
<meta name="description" content="箱であずかる収納サービス minikura。箱であずかる収納サービス minikura。宅配便とWebでカンタン、詰めて送るだけ。クラウド収納でお部屋はもっと広くなる！">
<meta name="author" content="">
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

  //c2c_sale common see@mock23 /www/market/common/css/app.min.css
  $this->Html->css('app_common_c2c_sale.min', ['inline' => false]);
  //c2c_sale  see@mock23 /www/market/css/app.min.css
  $this->Html->css('app_c2c_sale.min', ['inline' => false]);


  echo $this->fetch('css');
?>

<?php /* コンテンツ側から参照しているので、絶対ドメインパス */ ?>

<link rel="shortcut icon" type="image/x-icon" href="<?php echo Configure::read('site.c2c_sale.url');?>/images/favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo Configure::read('site.c2c_sale.url');?>/images/apple-touch-icon.png">
<link rel="icon" type="images/png" href="<?php echo Configure::read('site.c2c_sale.url');?>/images/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="images/png" href="<?php echo Configure::read('site.c2c_sale.url');?>/images/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="<?php echo Configure::read('site.c2c_sale.url');?>/images/manifest.json">
<link rel="mask-icon" href="<?php echo Configure::read('site.c2c_sale.url');?>/images/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ff0000">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-K4MN3W"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K4MN3W');</script>
<!-- End Google Tag Manager -->
<div class='airloader-overlay'>
  <div class="loader">Loading...</div>
</div>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header page-scroll">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand animsition-link" href="<?php echo Configure::read('site.c2c_sale.url');?>"><img src="<?php echo Configure::read('site.c2c_sale.url');?>/c2c_sale/common/images/logo.png" alt=""/></a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
        <li class="hidden">
          <a href="#page-top"></a>
        </li>
        <li>
          <a class="animsition-link" href="<?php echo Configure::read('site.static_content_url'); ?>/help/flow.html"><i class="fa fa-sitemap"></i> ご利用の流れ</a>
        </li>
        <li>
          <a class="animsition-link" href="https://help.minikura.com/hc/ja"><i class="fa fa-question"></i> ヘルプセンター</a>
        </li>
        <li>
          <a class="animsition-link login" href="<?php echo Configure::read('site.c2c_sale.url');?>/login"><i class="fa fa-unlock-alt"></i> ログイン</a>
        </li>
        <li>
          <a class="animsition-link signin" href="<?php echo Configure::read('site.c2c_sale.url');?>/customer/register/add"><i class="fa fa-sign-in"></i> 会員登録</a>
        </li>
      </ul>
    </div>
  </div>
</nav>



<!-- FLOW -->
<section id="detail">
  <?php  echo $this->Flash->render();?>
  <?php  echo $this->fetch('content');?>
</section>





<?php /* mock23のフッターも暫定の用なので、まだ何もしていない*/ ?>
  <div class="footer">
    <div class="col-lg-12 col-md-12 col-xs-12">
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
    <div class="row">
      <div class="col-lg-12 col-md-12 col-xs-12">
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

<!-- Yahoo Tag Manager -->
<script type="text/javascript">
  (function () {
    var tagjs = document.createElement("script");
    var s = document.getElementsByTagName("script")[0];
    tagjs.async = true;
    tagjs.src = "//s.yjtag.jp/tag.js#site=yCeb9Et";
    s.parentNode.insertBefore(tagjs, s);
  }());
</script>
<noscript>
  <iframe src="//b.yjtag.jp/iframe?c=yCeb9Et" width="1" height="1" title="iframe"></iframe>
</noscript>
<!-- End Yahoo Tag Manager -->
</body>
</html>
