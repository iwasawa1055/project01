<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="minikura,news,Information,あずける,トラクルーム,収納スペース">
<meta name="description" content="箱であずかる収納サービスminikura。箱であずかる収納サービスminikura。宅配便とWebでカンタン、詰めて送るだけ。クラウド収納でお部屋はもっと広くなる！">
<meta name="author" content="">
<title>ログイン・ユーザー登録 | minikura</title>
<?php
  echo $this->Html->meta('icon');

  // echo $this->Html->css('cake.generic');

  echo $this->fetch('meta');
  echo $this->fetch('css');
  echo $this->fetch('script');
?>
<!-- Bootstrap Core CSS -->
<link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- MetisMenu CSS -->
<link href="/css/metisMenu.min.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<!-- Animsition CSS -->
<link href="/css/animsition.min.css" rel="stylesheet">
<!-- Custom Theme CSS -->
<link href="/css/app.css" rel="stylesheet">
<!-- Favicon -->
<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
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
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
    <a class="navbar-brand" href="/"><img class="logo" src="/img/logo.png" alt="minikura"></a>
  </div>
  <?php echo $this->element('navbar_right'); ?>
  <?php echo $this->element('sidebar'); ?>
</nav>
  <?php echo $this->Session->flash(); ?>
  <?php echo $this->fetch('content'); ?>
</div>
<!-- jQuery -->
<script src="/jquery/jquery.min.js"></script> <!-- Bootstrap Core JavaScript -->
<script src="/bootstrap/js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="/js/metisMenu.min.js"></script> <!-- Animsition JavaScript -->
<script src="/js/animsition.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="/js/app.js"></script>
<script>
  $(document).ready(function() {
    $('.animsition').animsition();
  });
</script>
</body>
</html>
