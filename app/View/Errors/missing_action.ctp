<?php $this->layout = null; ?>
<?php echo $this->element('first'); ?>
<title>Not Found | Minikura API</title>
<style>
.error .text {
	font-size: 15px;
}
.error .code {
	font-size: 22px;
	font-weight: bold;
	color: #A00000;
}
.error .msg {
	font-size: 17px;
	font-weight: bold;
	color: #606060;
}
.error .trace {
	margin-top: 1%;
	margin-left: 5%;
	text-align: left;
	color: #00A000;
}
.error .trace ul {
	margin-top: 10px;
	margin-left: 10px;
}
</style>
</head>
<body id="error">
<?php echo $this->element('header'); ?>
<div class="error">
	<h2>
		<p class='code'>404 Not Found</p>
	</h2>
	<?php if (Configure::read('debug')): ?>
	<p class='msg'><?php echo $name; ?></p>
	<div class='trace'><?php echo $this->element('exception_stack_trace'); ?></div>
	<?php endif; ?>
</div>
<?php echo $this->element('footer'); ?>
<?php echo $this->element('last'); ?>
