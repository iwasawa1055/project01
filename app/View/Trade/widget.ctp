<!doctype html>
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
<meta property="og:description" content="<?php echo h($sales['sales_note']);?>" />
<meta property="og:image" content="<?php echo $sales['item_image'][0]['image_url'];?>" />
<meta property="og:image:width" content="800" />
<meta property="og:image:width" content="800" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="<?php echo h($sales['sales_title']);?>" />
<meta name="twitter:description" content="<?php echo h($sales['sales_note']);?>" />
<meta name="twitter:image:src" content="<?php echo $sales['item_image'][0]['image_url'];?>" />
<?php else:?>
<meta property="og:title" content="minikura" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo Configure::read('site.static_content_url');?>" />
<meta property="og:description" content="箱であずかる収納サービス minikura。宅配便とWebでカンタン、詰めて送るだけ。クラウド収納でお部屋はもっと広くなる！" />
<meta name="twitter:card" content="summary" />
<?php endif;?>
<meta charset="UTF-8">
<style>
body {
	margin: 0;
	padding: 0;
}
.logo{
	margin:5px 0 0 10px;
	padding:0;
	max-width:640px;
}
.logo img{
	width:127px;
	margin: 0;
	padding:0;
}
#wrapper {
	margin: 0 5px 5px 5px;
	border: 1px solid #999999;
	border-radius: 3px;
	max-width:640px;
}
#wrapper .image {
	width: 100%;
}
#wrapper .image img {
	width: 100%;
}
#wrapper .title {
	font-size: 12px;
	margin: 10px;
}
#wrapper .description {
	font-size: 10px;
	margin: 10px;
}
#wrapper .price {
	font-size: 12px;
	color: #ff0000;
	font-weight: bold;
	margin: 10px;
}
#wrapper .btn {
	display: block;
	background-color: #ff0000;
	border: 1px solid #ff0000;
	border-radius: 3px;
	text-align: center;
	font-size: 12px;
	color: #ffffff;
	font-weight: bold;
	margin: 10px;
	padding: 15px;
}
#wrapper a.btn {
	text-decoration: none;
}
</style>
<title><?php echo h($sales['sales_title']);?></title>
</head>

<body>

<?php if ( empty($sales)):?>
<div class="logo">
  <a href="<?php echo Configure::read('site.mypage.url');?>"><img src="<?php echo Configure::read('site.mypage.url');?>/trade/images/logo.png" alt=""></a>
</div>
<div id="wrapper">
  <p style="text-align : center";>存在しないデータです</p>
</div>

<?php else:?>
<div class="logo">
  <a href="<?php echo Configure::read('site.trade.url') . $sales['sales_id'];?>"><img src="<?php echo Configure::read('site.mypage.url');?>/trade/images/logo.png" alt=""></a>
</div>
<div id="wrapper">
  <div class="image">
    <a href="<?php echo Configure::read('site.trade.url') . $sales['sales_id'];?>" target="_parent"><img src="<?php echo $sales['item_image'][0]['image_url'];?>" alt=""></a>
  </div>
  <div class="title">
    <?php echo h($sales['sales_title']);?>
  </div>
  <div class="description">
    <?php echo nl2br(h($sales['sales_note']));?>
  </div>
  <div class="price">
    <?php echo number_format(h($sales['price']));?> 円 (税込)
  </div>
  <?php if ($sales['sales_status'] === SALES_STATUS_ON_SALE):?>
  <a href="<?php echo Configure::read('site.trade.url') . $sales['sales_id'];?>" target="_parent" class="btn">このアイテムを購入する</a>
  <?php endif;?>
</div>
<?php endif;?>
</body>
</html>
