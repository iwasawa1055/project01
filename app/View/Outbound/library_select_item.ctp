<!DOCTYPE html>
<html lang="en">
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
  $this->Html->css('style', ['inline' => false]);

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
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K4MN3W');</script>
<!-- End Google Tag Manager -->
</head>

<body id="">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K4MN3W"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
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
            <?php echo $this->element('navbar_right'); ?>
            <?php echo $this->element('sidebar'); ?>
        </nav>
        <form method="POST" action="/outbound/library_select_item" name="form">
        <div id="page-wrapper" class="wrapper library">
            <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> minikura Library</h1>
            <ul class="pagenation">
                <li class="on"><span class="number">1</span><span class="txt">アイテム<br>選択</span>
                </li>
                <li><span class="number">2</span><span class="txt">配送情報<br>入力</span>
                </li>
                <li><span class="number">3</span><span class="txt">確認</span>
                </li>
                <li><span class="number">4</span><span class="txt">完了</span>
                </li>
            </ul>
            <?php if (isset($complete_error)) : ?>
            <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i><?php echo $this->Flash->render('complete_error');?></div>
            <?php endif; ?>
            <?php if (isset($no_select_item_error)) : ?>
            <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> アイテムが選択されていません</div>
            <?php endif; ?>
            <ul class="setting-switcher">
                <li>
                    <label class="setting-switch">
                        <input type="radio" class="ss" name="select-deposit" value="item" checked="">
                        <span class="btn-ss"><span class="icon"></span>アイテムから選択</span>
                    </label>
                </li>
                <li>
                    <label class="setting-switch">
                        <input type="radio" class="ss" name="select-deposit" value="box">
                        <span class="btn-ss"><span class="icon"></span>ボックスごと取り出す</span>
                    </label>
                </li>
            </ul>
            <ul class="item-search">
                <li>
                    <input type="search" placeholder="" class="search" id="search_txt">
                </li>
                <li>
                    <!--<label class="cb-circle">
                        <input type="checkbox" class="cb" id="all_select"><span class="icon-cb"></span><span class="txt-cb">すべて選択</span>
                    </label>-->
                    <label class="input-check">
                        <input type="checkbox" class="cb-circle" id="all_select">
                        <span class="icon"></span>
                        <span class="label-txt">すべて選択</span>
                    </label>
                </li>
            </ul>
            <div class="item-content">
                <!--<ul class="grid-view grid-library">
                </ul>-->
                <ul class="grid grid-lg">
                </ul>
            </div>

            <input type="hidden" id="selected_item_ids" value="<?php echo $item_id; ?>">
            <input type="hidden" id="selected_box_ids" value="<?php echo $box_id; ?>">
        </div>
        </form>
        <!--footer-->
        <footer>
            <nav class="footer-nav">
                <li><a href="https://minikura-logitech.com/" target="_blank">運営チーム</a>
                </li>
                <li><a href="/privacy/">個人情報について</a>
                </li>
                <li><a href="/security_policy/">セキュリティポリシー</a>
                </li>
                <li><a href="/commercial_transaction/">特定商取引に関する表記について</a>
                </li>
                <li><a href="/use_agreement/">利用規約</a>
                </li>
                <li><a href="/contact_us/">お問い合わせ</a>
                </li>
            </nav>
            <p class="copyright">© Warehouse TERRADA</p>
        </footer>
        <div class="nav-fixed">
            <ul>
                <li><button class="btn-red" id="execute">配送先入力へ</button></li>
            </ul>
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
      $this->Html->script('outbound/library', ['inline' => false]);

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
</body>
</html>
