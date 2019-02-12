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
    <!-- Bootstrap Core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <!-- Global CSS -->
    <link href="https://minikura.com/contents/common/css/app.min.css" rel="stylesheet">
    <!-- Remodal CSS -->
    <link href="/css/remodal.css" rel="stylesheet">
    <link href="/css/remodal-theme.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/css/style.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css">
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
  </head>

  <body id="page-top">
    <div id="header"> </div>
    <?php echo $this->fetch('content'); ?>
    <!-- jQuery -->
    <script src="https://minikura.com/contents/common/js/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="https://minikura.com/contents/common/js/bootstrap.min.js"></script>
    <!-- Plugin JavaScript -->
    <script src="https://minikura.com/contents/common/js/jquery.easing.min.js"></script>
    <!-- Remodal JavaScript -->
    <script src="/js/remodal.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="/js/register/dsn-register.js"></script>
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
