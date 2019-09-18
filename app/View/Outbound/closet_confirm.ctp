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
            <?php echo $this->element('navbar_right'); ?>
            <?php echo $this->element('sidebar'); ?>
        </nav>
        <div id="page-wrapper" class="wrapper library">
            <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> minikura Closet</h1>
            <ul class="pagenation">
                <li><span class="number">1</span><span class="txt">アイテム<br>選択</span>
                </li>
                <li><span class="number">2</span><span class="txt">配送情報<br>入力</span>
                </li>
                <li class="on"><span class="number">3</span><span class="txt">確認</span>
                </li>
                <li><span class="number">4</span><span class="txt">完了</span>
                </li>
            </ul>
            <p class="page-caption">以下の内容でminikura Closetの取り出し手続きを行います。</p>
            <ul class="input-info">
                <?php if (isset($outbound_item_list)) : ?>
                <li>
                    <label class="headline">アイテムの取り出し</label>
                    <ul class="li-libry-item">
                        <?php foreach($outbound_item_list as $k => $v): ?>
                        <li>
                            <p class="li-libry-item-pict"><img src="<?php echo $v['image_first']['image_url']; ?>" alt="<?php echo $v['item_name']; ?>" class="li-libry-img"></p>
                            <p class="li-libry-item-name"><?php echo $v['item_name']; ?><span><?php echo $v['item_id']; ?></span></p>
                            <p class="li-libry-item-price"><?php echo CLOSET_OUTBOUND_PER_ITEM_PRICE; ?>円</p>
                        </li>
                        <?php endforeach; ?>
                        <li>
                            <p class="li-libry-item-pict"></p>
                            <p class="li-libry-item-name">基本料金</p>
                            <p class="li-libry-item-price"><?php echo CLOSET_OUTBOUND_BASIC_PRICE; ?>円</p>
                        </li>
                        <li>
                            <p class="li-libry-item-pict"></p>
                            <p class="li-libry-item-name">小計</p>
                            <p class="li-libry-item-price"><?php echo $outbound_item_price; ?>円</p>
                        </li>
                        <?php if(!empty($use_point)) : ?>
                        <li>
                            <p class="li-libry-item-pict"></p>
                            <p class="li-libry-item-name">ポイントご利用</p>
                            <p class="li-libry-item-price">-<?php echo $use_point;?>円</p>
                        </li>
                        <?php endif;?>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if (isset($outbound_box_list)) : ?>
                <li>
                    <label class="headline">解約の取り出し</label>
                    <ul class="li-libry-box">
                        <?php foreach($outbound_box_list as $k => $v): ?>
                        <li>
                            <p class="li-libry-box-id"><?php echo $v['box']['box_name']; ?><br><?php echo $k; ?></p>
                            <ul class="li-libry-box-item">
                                <?php foreach($v['item'] as $kk => $vv): ?>
                                <li><?php echo $vv['item_name']; ?>(<?php echo $kk; ?>)</li>
                                <?php endforeach; ?>
                            </ul>
                            <p class="li-libry-box-price"><?php echo $v['price']; ?>円</p>
                        </li>
                        <?php endforeach; ?>
                        <li>
                            <p class="li-libry-box-id"></p>
                            <p class="li-libry-box-item">小計</p>
                            <p class="li-libry-box-price"><?php echo $outbound_box_price; ?>円</p>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <li>
                    <ul class="li-libry-box">
                        <li>
                            <p class="li-libry-box-id"></p>
                            <p class="li-libry-box-item">総計(税込み)</p>
                            <p class="li-libry-box-price"><?php echo number_format($outbound_total_price - $use_point); ?>円</p>
                        </li>
                    </ul>
                </li>
                <li>
                    <label class="headline">配送住所</label>
                    <ul class="li-address">
                    <li>〒<?php echo h($address['postal']); ?></li>
                        <li><?php echo h($address['pref'] . $address['address1'] . $address['address2'] . $address['address3']); ?></li>
                        <li><?php echo h("{$address['lastname']} {$address['firstname']}"); ?></li>
                        <li><?php echo h($address['tel1']); ?></li>
                    </ul>
                </li>
                <li>
                    <label class="headline">配送方法</label>
                    <ul class="li-address">
                        <li class="note">宅配便での配送</li>
                        <li class="note"><?php echo $this->App->convDatetimeCode($datetime_cd); ?></li>
                    </ul>
                </li>
                <li>
                    <label class="headline">決済</label>
                    <ul class="li-credit">
                        <li>ご登録のクレジットカード</li>
                        <li><?php echo $default_card['card_no']; ?></li>
                        <li><?php echo $default_card['holder_name']; ?></li>
                    </ul>
                </li>
                <?php if(!empty($use_point)) : ?>
                <li>
                    <label class="headline">ご利用になるポイント</label>
                    <ul class="li-address">
                        <li><?php echo $use_point; ?>ポイント</li>
                    </ul>
                </li>
                <?php endif;?>
                <li class="caution-box">
                    <p class="title">minikuraの他の商品と異なり、<br class="sp">お申し込み完了と同時に決済完了となります。</p>
                    <div class="content">
                        <div id="check-error"></div>
                        <label class="input-check">
                            <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">お申込み完了後、日時を含む内容の変更はお受けすることができません。<br>
                        内容にお間違いないか再度ご確認の上、「この内容で取り出す」にお進みください。</span>
                        </label>
                    </div>
                </li>
                <li>
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <label>ご注意ください</label>
                            <ul>
                                <li>
                                    早期の取り出しについて、預け入れから1ヶ月以内の場合は月額保管料の2ヶ月分。2ヶ月以内の場合は月額保管料の1ヶ月分が料金として発生いたします。個品のお取り出しがある場合は適用致しません。
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
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
                <li><a class="btn-d-gray" href="/outbound/closet_input_address">戻る</a></li>
                <li><button class="btn-red" id="execute">この内容で取り出す</button></li>
            </ul>
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
      $this->Html->script('outbound/closet_confirm', ['inline' => false]);

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
