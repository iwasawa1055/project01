<style type='text/css'>
.msg_default {
	clear: both;
	margin-top: 10px;
	padding: 5px 20px;
	border-radius: 5px;
	border: 1px solid #E0E0E0;
	background-color: #FFFFFF;
	color: #606060;
	text-align: center;
}
.msg_info {
	clear: both;
	margin-top: 10px;
	padding: 5px 20px;
	border-radius: 5px;
	border: 1px solid #C9DEE7;
	background-color: #D9EDF7;
	color: #3A878D;
	text-align: center;
}
.msg_success { clear: both;
	margin-top: 10px;
	padding: 5px 20px;
	border-radius: 5px;
	border: 1px solid #C0EFA0;
	background-color: #D0FFB0;
	color: #468847;
	text-align: center;
}
.msg_warning {
	clear: both;
	margin-top: 10px;
	padding: 5px 20px;
	border-radius: 5px;
	border: 1px solid #EFD960;
	background-color: #FFE970;
	color: #E05010;
	text-align: center;
}
.msg_error {
	clear: both;
	margin-top: 5px;
	margin-bottom: 15px;
	padding: 5px 20px;
	border-radius: 5px;
	border: 1px solid #EFB0B0;
	background-color: #FFC0C0;
	color: #A00000;
	text-align: center;
}
.invalid {
	color: #D00000;
	font-size: 15px;
}

input#category_path {
	width: 560px;
	padding: 5px 15px;
	border: 1px solid #C9DEE7;
	background-color: #D9EDF7;
	color: #3A878D;
	border-radius: 4px;
	cursor: pointer;
}
input#category_path:hover {
	opacity: 0.6;
}

.specInfo li.title {
	padding: 0px 10px;
	border-left: 4px solid #E10012;
	border-bottom: 1px solid #E0E0E0;
}

.specInfo li.detail {
	margin-bottom: 20px;
	padding: 0px 14px;
}
</style>
<div id="Main">
	<div id="Main_inner">
        <div id="topMenu">
            <div class="inner">
				<?php if (! empty($mono_view_msg)): ?>
				<div class='msg_default' style='margin-bottom: 20px;'><i class='fa fa-info-circle'></i>&ensp;「ゴールデンウィーク MONO VIEW 新規お申込み休止期間」　2015/05/02 (土) 11:00 ～ 2015/05/07 (木) 10:59</div>
				<?php endif; ?>
                <ul>
                    <li class="menu01 mono_view_menu"><a href='/mini_auction/pro/mono_view_box'>MONO VIEW 申込み</a></li>
                    <li class="menu02"><a href="/mini_auction/pro/box">出品する</a></li>
                    <li class="menu03"><a href="/mini_auction/pro/history">出品履歴</a></li>
                    <!--<li class="menu04"><a style='border-color: #909090; background-color: #C0C0C0; color: #909090;'>利用状況</a></li>-->
                </ul>
            </div><!-- /.inner -->
        </div><!-- /#topMenu -->
		<?php echo isset($hash['heading']) ? '<h2 class="heading">' . $hash['heading'] . '</h2>': ''; ?>
		<div id="Head_nav_area" class="navArea cFix">
        	<?php //<p class="headTxt">Yahoo!CD: <?php echo $hash['yahoo_cd']; ?></p>
        	<p class="headTxtSub">Yahoo!<?php echo $hash['yahoo_seller_type']; ?>アカウントログイン中</p>
<?php /*
			<div id="accMenuArea">
                <ul class="accMenu">
                    <li class="menu01"><a href="https://login.yahoo.co.jp/config/login?.lg=jp&.intl=jp&logout=1" target='_blank'>Yahoo!ログアウト</a></li>
                    <li class="menu02"><a href="https://login.yahoo.co.jp/config/login?" target='_blank'>別のYahoo!IDでログイン</a></li>
                </ul>
            </div><!-- /#accMenuArea -->
*/ ?>
		</div><!-- /#Head_nav_area -->

