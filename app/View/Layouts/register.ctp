<!DOCTYPE html>
<html lang="ja">

<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="terradawinestorage.com">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="content-language" content="ja">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
    <link rel="icon" type="images/png" href="/images/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="images/png" href="/images/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/images/manifest.json">
    <link rel="mask-icon" href="/common/img/safari-pinned-tab.svg" color="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <meta name="description" content=" | 寺田倉庫">
    <meta name="keywords" content="">
    <!-- local CSS -->
    <?php
        $this->Html->css('form-register', ['inline' => false]);
        $this->Html->css('customer/register/app_dev', ['inline' => false]);
        echo $this->fetch('css');
    ?>
    <!-- html5 Shim and Respond.js IE8 support of html5 elements and media queriejjs -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <!-- title -->
    <title><?php $this->Title->p(); ?></title>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-K4MN3W');</script>
    <!-- End Google Tag Manager -->
</head>

<body id="top">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K4MN3W"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class='airloader-overlay'>
        <div class="loader">Loading...</div>
    </div>

    <header>
        <div class="container">
            <h1><a href="/"><img class="logo" src="/images/logo.png" alt="minikura"></a></h1>
        </div>
    </header>

    <?php echo $this->fetch('content'); ?>

    <footer>
        <ul class="nav">
            <li><a href="http://www.terrada.co.jp/ja/company/" target="_blank">会社概要</a></li>
            <li><a href="http://www.terrada.co.jp/ja/privacy/" target="_blank">個人情報保護方針</a></li>
            <li><a href="http://www.terrada.co.jp/ja/privacy/handling.html" target="_blank">個人情報の取扱いについて</a></li>
            <li><a href="/ja/policies.html">セキュリティーポリシー</a></li>
            <li><a href="https://www.terrada.co.jp/ja/security/cookie.html" target="_blank">クッキーポリシー</a></li>
            <li><a href="/ja/law.html">特定商取引</a></li>
            <li><a href="/ja/terms.html">利用規約</a></li>
            <li><a href="/contact/add" target="_blank">お問い合わせ</a></li>
        </ul>
        <p class="copyright futura"> &copy; Warehouse TERRADA</p>
        <p id="pagetop"><a href="#header" data-scroll><span></span></a></p>
    </footer>
    <?php
      $this->Html->script('jquery.min', ['inline' => false]);
      $this->Html->script('app_dev', ['inline' => false]);
      $this->Html->script('jquery.airCenter', ['inline' => false]);

      echo $this->fetch('script');
      echo $this->fetch('scriptMinikura');
    ?>
</body>

</html>
