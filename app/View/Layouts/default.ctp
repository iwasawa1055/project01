<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<meta name="keywords" content="minikura,あずける,トラクルーム,収納スペース">
<meta name="description" content="箱であずかる収納サービス minikura。箱であずかる収納サービス minikura。宅配便とWebでカンタン、詰めて送るだけ。クラウド収納でお部屋はもっと広くなる！">
<meta name="author" content="">
<title><?php $this->Title->p(); ?></title>
<?php
  echo $this->Html->meta('icon');
  echo $this->fetch('meta');

  $this->Html->css('bootstrap.min', ['inline' => false]);
  $this->Html->css('font-awesome.min', ['inline' => false]);
  $this->Html->css('metisMenu.min', ['inline' => false]);
  $this->Html->css('animsition.min', ['inline' => false]);
  $this->Html->css('app', ['inline' => false]);
  $this->Html->css('app_dev', ['inline' => false]);

  echo $this->fetch('css');
?>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
<div id="wrapper" class="animsition" data-animsition-in-class="fade-in-up-lg" data-animsition-out-class="fade-out-up-lg">
  <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/"><img class="logo" src="/images/logo.png" alt="minikura"></a>
    </div>
    <?php if (!empty($customer) && $customer->isLogined()) : ?>
      <?php echo $this->element('navbar_right'); ?>
      <?php echo $this->element('sidebar'); ?>
    <?php else : ?>
      <?php echo $this->element('navbar_right_nonlogin'); ?>
      <?php echo $this->element('sidebar_nonlogin'); ?>
    <?php endif; ?>
  </nav>
  <div id="page-wrapper">
    <?php echo $this->Flash->render(); ?>
    <?php echo $this->fetch('content'); ?>
  </div>
  <div class="footer">
    <div class="col-lg-12 col-md-12 col-xs-12">
      <ul class="list-inline">
        <li><a href="http://www.terrada.co.jp/company/" target="_blank">会社情報</a>
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
  $this->Html->script('app', ['inline' => false]);
  $this->Html->script('app_dev', ['inline' => false]);

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
