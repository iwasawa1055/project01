<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>
<p id="<?php echo h($key) ?>Message" class="error-message <?php echo h($class) ?>"><?php echo h($message) ?>
お客さまのご事情により、ログインすることができません。お手数をおかけしますが、<a href="/inquiry/add">各種情報変更</a>からお問い合わせください。
</p>
