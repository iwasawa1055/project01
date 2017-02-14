<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="minikura,あずける,トラクルーム,収納スペース">
    <meta name="description" content="箱であずかる収納サービス minikura。箱であずかる収納サービス minikura。宅配便とWebでカンタン、詰めて送るだけ。クラウド収納でお部屋はもっと広くなる！">
    <meta name="author" content="">
    <title>ボックス選択 - minikura</title>
    <!-- Bootstrap Core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <!-- Global CSS -->
    <link href="https://minikura.com/contents/common/css/app.min.css" rel="stylesheet">
    <!-- Remodal CSS -->
    <link href="/css/remodal.css" rel="stylesheet">
    <link href="/css/remodal-theme.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/first_order/css/app.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css">
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

<body id="register page-top">
<!--HEADER-->
<div id="header">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand animsition-link" href="/"><img src="https://minikura.com/contents/common/images/logo.png" alt=""/></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <?php if (!empty($customer) && $customer->isLogined()) : ?>
                        <li>
                            <a class="animsition-link" href="/MyPage/"><i class="fa fa-list-ul"></i> マイページへ</a>
                        </li>
                    <?php else : ?>
                        <li class="hidden">
                            <a href="#page-top"></a>
                        </li>
                        <li>
                            <a class="animsition-link" href="/lineup/"><i class="fa fa-list-ul"></i> ラインナップ</a>
                        </li>
                        <li>
                            <a class="animsition-link" href="/help/flow.html"><i class="fa fa-sitemap"></i> ご利用の流れ</a>
                        </li>
                        <li>
                            <a class="animsition-link" href="https://help.minikura.com/hc/ja"><i class="fa fa-question"></i> ヘルプセンター</a>
                        </li>
                        <li>
                            <a class="login" href="/" target="_blank"><i class="fa fa-unlock-alt"></i> ログイン</a>
                        </li>
                        <li>
                            <a class="signin" href="/customer/register/add" target="_blank"><i class="fa fa-sign-in"></i> ユーザー登録</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav></div>
<!--HEADER-->

<!-- PAGENATION -->
<?php echo $this->Flash->render(); ?>
<?php echo $this->fetch('content'); ?>

<!-- FOOTER -->
<div id="footer"> </div>
<!-- FOOTER -->

<!-- jQuery -->
<script src="https://minikura.com/contents/common/js/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="https://minikura.com/contents/common/js/bootstrap.min.js"></script>
<!-- Plugin JavaScript -->
<script src="https://minikura.com/contents/common/js/jquery.easing.min.js"></script>
<!-- Remodal JavaScript -->
<script src="/js/remodal.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="/first_order/js/app.js"></script>
<script src="/first_order/js/app_dev.js"></script>
</body>

</html>