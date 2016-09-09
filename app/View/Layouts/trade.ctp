<!DOCTYPE html>
<html lang="ja">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
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
<?php if( !empty($sales)):?>
<meta property="og:title" content="<?php echo h($sales['sales_title']);?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo Configure::read('site.trade.url') . $sales['sales_id'];?>" />
<meta property="og:image" content="<?php echo $sales['item_image'][0]['image_url'];?>" />
<meta property="og:description" content="<?php echo h($sales['sales_note']);?>" />
<?php else:?>
<meta property="og:title" content="minikura" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo Configure::read('site.static_content_url');?>" />
<meta property="og:description" content="箱であずかる収納サービス minikura。宅配便とWebでカンタン、詰めて送るだけ。クラウド収納でお部屋はもっと広くなる！" />
<?php endif;?>
<meta name="twitter:card" content="summary" />

<title><?php $this->Title->p(); ?></title>
<?php
  echo $this->fetch('meta');
  $this->Html->css('font-awesome.min', ['inline' => false]);
  /* 必要なものあれば適宜使用するため、のこしておく

  $this->Html->css('bootstrap.min', ['inline' => false]);
  $this->Html->css('font-awesome.min', ['inline' => false]);
  $this->Html->css('metisMenu.min', ['inline' => false]);
  $this->Html->css('animsition', ['inline' => false]);
  $this->Html->css('app', ['inline' => false]);
  $this->Html->css('app_dev', ['inline' => false]);

  */

  echo $this->fetch('css');
?>


<?php /* コンテンツ側から参照しているので、絶対ドメインパス */ ?>
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('site.mypage.url');?>/trade/common/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('site.mypage.url');?>/trade/common/css/animsition.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('site.mypage.url');?>/trade/common/css/animate.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('site.mypage.url');?>/trade/common/css/app.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('site.mypage.url');?>/trade/css/app.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('site.mypage.url');?>/css/app_dev.css">
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('site.mypage.url');?>/trade/css/app_dev.min.css">

<!-- Custom Fonts -->
<link href="<?php echo Configure::read('site.mypage.url');?>/trade/common/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
<link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

<link rel="shortcut icon" type="image/x-icon" href="<?php echo Configure::read('site.mypage.url');?>/images/favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo Configure::read('site.mypage.url');?>/images/apple-touch-icon.png">
<link rel="icon" type="images/png" href="<?php echo Configure::read('site.mypage.url');?>/images/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="images/png" href="<?php echo Configure::read('site.mypage.url');?>/images/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="<?php echo Configure::read('site.mypage.url');?>/images/manifest.json">
<link rel="mask-icon" href="<?php echo Configure::read('site.mypage.url');?>/images/safari-pinned-tab.svg" color="#5bbad5">
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
      <a class="navbar-brand animsition-link" href="<?php echo Configure::read('site.static_content_url');?>"><img src="<?php echo Configure::read('site.mypage.url');?>/trade/common/images/logo.png" alt=""/></a>
    </div>
  </div>
</nav>

<!-- FLOW -->
  <?php  echo $this->Flash->render();?>
  <?php  echo $this->fetch('content');?>

<!-- FOOTER -->
<footer>
  <div class="footer-top">
    <div class="container">
      <div class="row footer-top-inner">
        <div>
          <span><a href="<?php echo Configure::read('site.static_content_url'); ?>/lineup/">ラインナップ</a>
          </span>
          <ul>
            <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/lineup/mono.html">minikuraMONO</a>
            </li>
            <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/lineup/hako.html">minikuraHAKO</a>
            </li>
            <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/lineup/cleaning.html">クリーニングパック</a>
            </li>
          </ul>
          <span><a href="<?php echo Configure::read('site.static_content_url'); ?>/lineup/option.html">オプション</a>
          </span>
          <!--ul>
            <li><a href="/usageguide_artist">minikuraMONO VIEW</a>
            </li>
            <li><a href="/usageguide_artist">データ化オプション</a>
            </li>
            <li><a href="/usageguide_artist">クリーニングオプション</a>
            </li>
            <li><a href="/usageguide_artist">あんしんオプション</a>
            </li>
            <li><a href="/usageguide_artist">文書溶解サービス</a>
            </li>
          </ul-->
        </div>
        <div>
          <span><a href="<?php echo Configure::read('site.static_content_url'); ?>/help/flow.html">ご利用の流れ</a>
          </span>
          <ul>
            <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/help/flow.html">ユーザー登録〜ボックス購入</a>
            </li>
            <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/help/flow.html">ボックスに詰めてお預け入れ</a>
            </li>
            <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/help/flow.html">マイページの使い方</a>
            </li>
            <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/help/flow.html">ヤフオク!へ個品から手軽に出品する場合</a>
            </li>
            <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/help/flow.html">ヤフオク!へまとめて一括出品する場合</a>
            </li>
            <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/help/flow.html">ヤフオク!落札商品の配送方法</a>
            </li>
          </ul>
        </div>
        <div>
          <ul>
            <li><a href="<?php echo Configure::read('site.mypage.url');?>/customer/register/add" target="_blank">会員登録</a>
            </li>
            <li><a href="<?php echo Configure::read('site.mypage.url');?>/" target="_blank">ログイン</a>
            </li>
            <li><a href="https://help.minikura.com/hc/ja" target="_blank">ヘルプセンター</a>
            </li>
            <li><a href="https://minikura-logitech.com/" target="_blank">運営チーム</a>
            </li>
            <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/privacy/">個人情報について</a>
            </li>
            <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/security_policy/">セキュリティポリシー</a>
            </li>
            <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/commercial_transaction/">特定商取引に関する表記</a>
            </li>
            <li><a href="<?php echo Configure::read('site.static_content_url'); ?>/use_agreement/">利用規約</a>
            </li>
            <li><a href="<?php echo Configure::read('site.mypage.url');?>/inquiry/add" target="_blank">お問い合わせ</a>
            </li>
            <!--li><a href="https://minikura.com/news/197/">特許：第5578581号</a>
            </li-->
          </ul>
        </div>
      </div>
    </div>
  </div>
  <!--footer-bottom-->
  <div class="footer-bottom">
    <div class="container">
      © 2016 Warehouse TERRADA
    </div>
  </div>
  <!--footer-bottom-->
</footer>
<!-- FOOTER -->


<script src="<?php echo Configure::read('site.mypage.url');?>/trade/common/js/jquery.min.js"></script>
<script src="<?php echo Configure::read('site.mypage.url');?>/trade/common/js/bootstrap.min.js"></script>
<script src="<?php echo Configure::read('site.mypage.url');?>/trade/common/js/jquery.easing.min.js"></script>
<script src="<?php echo Configure::read('site.mypage.url');?>/trade/common/js/classie.js"></script>
<script src="<?php echo Configure::read('site.mypage.url');?>/trade/common/js/animsition.min.js"></script>
<script src="<?php echo Configure::read('site.mypage.url');?>/trade/common/js/wow.min.js"></script>
<script>
    new WOW().init();
</script>

<?php

  /*
  $this->Html->script('jquery.min', ['inline' => false]);
  $this->Html->script('bootstrap.min', ['inline' => false]);
  $this->Html->script('metisMenu.min', ['inline' => false]);
  $this->Html->script('animsition.min', ['inline' => false]);
  $this->Html->script('remodal.min', ['inline' => false]);
  $this->Html->script('app', ['inline' => false]);
  $this->Html->script('app_dev', ['inline' => false]);
  $this->Html->script('jquery.airCenter', ['inline' => false]);
  */

  echo $this->fetch('script');
  echo $this->fetch('scriptMinikura');
?>

<script src="<?php echo Configure::read('site.mypage.url');?>/js/metisMenu.min.js"></script>
<script src="<?php echo Configure::read('site.mypage.url');?>/js/remodal.min.js"></script>
<script src="<?php echo Configure::read('site.mypage.url');?>/js/app.js"></script>
<script src="<?php echo Configure::read('site.mypage.url');?>/js/app_dev.js"></script>
<script src="<?php echo Configure::read('site.mypage.url');?>/js/jquery.airCenter.js"></script>

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
