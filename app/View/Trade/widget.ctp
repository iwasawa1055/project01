<!doctype html>
<html>
<head>
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
