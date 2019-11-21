<!DOCTYPE html>
<html lang="ja">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="minikura,あずける,トラクルーム,収納スペース">
    <meta name="description" content="箱であずかる収納サービス minikura。箱であずかる収納サービス minikura。宅配便とWebでカンタン、詰めて送るだけ。クラウド収納でお部屋はもっと広くなる！">
    <meta name="author" content="">
    <meta name="format-detection" content="telephone=no">
    <?php echo $this->fetch('meta'); ?>

    <title>登録方法選択 - minikura</title>
    <?php
      $this->Html->css('bootstrap.min', ['inline' => false]);
      $this->Html->css('https://minikura.com/contents/common/css/app.min.css', ['inline' => false]);
      $this->Html->css('style', ['inline' => false]);
      $this->Html->css('font-awesome.min', ['inline' => false]);
      $this->Html->css('app_dev', ['inline' => false]);
      $this->Html->css('customer/register/app_dev', ['inline' => false]);
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
    <!-- Criteo -->
    <script type="text/javascript">
        var dataLayer = dataLayer || [];
        dataLayer.push({
            'PageType': 'Homepage',
            'HashedEmail': ''
        });
    </script>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-K4MN3W');</script>
    <!-- End Google Tag Manager -->
  </head>

  <body id="page-top">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K4MN3W"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div id="header">
      <?php echo $this->element('Register/nav'); ?>
    </div>

    <?php echo $this->fetch('content'); ?>

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
    <?php
      $this->Html->script('jquery.min', ['inline' => false]);
      $this->Html->script('jquery-ui.min', ['inline' => false]);
      $this->Html->script('jquery.easing', ['inline' => false]);
      $this->Html->script('bootstrap.min', ['inline' => false]);
      $this->Html->script('metisMenu.min', ['inline' => false]);
      $this->Html->script('remodal.min', ['inline' => false]);
      $this->Html->script('register/dsn-register.js', ['inline' => false]);
      $this->Html->script('app', ['inline' => false]);
      $this->Html->script('app_dev', ['inline' => false]);
    ?>
    <?php
      echo $this->fetch('script');
      echo $this->fetch('scriptMinikura');
    ?>

    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId      : "<?php echo Configure::read('app.facebook.app_id'); ?>",
                cookie     : true,
                xfbml      : true,
                version    : "<?php echo Configure::read('app.facebook.version'); ?>"
            });

            FB.AppEvents.logPageView();

        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/ja_JP/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <script src="/js/app_dev_facebook.js"></script>
  </body>
</html>
