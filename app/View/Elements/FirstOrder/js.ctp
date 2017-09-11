<!-- jQuery -->
<script src="https://minikura.com/contents/common/js/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="https://minikura.com/contents/common/js/bootstrap.min.js"></script>
<!-- Plugin JavaScript -->
<script src="https://minikura.com/contents/common/js/jquery.easing.min.js"></script>
<!-- Remodal JavaScript -->
<script src="/js/remodal.min.js"></script>
<!-- AmazonPay  JavaScript -->
<?php if (!empty(CakeSession::read('FirstOrder.amazon_pay.access_token'))):?>
<script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>"></script>
<?php endif; ?>

<!-- Custom Theme JavaScript -->
<script src="/first_order_file/js/app.js"></script>
<script src="/first_order_file/js/app_dev.js"></script>
<script src="/js/jquery.airCenter.js"></script>

<?php if (!empty(CakeSession::read('FirstOrder.amazon_pay.access_token'))) : ?>
    <script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>"></script>
<?php endif; ?>
