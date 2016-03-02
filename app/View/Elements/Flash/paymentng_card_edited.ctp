<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>
<p id="<?php echo h($key) ?>Message" class="error-message <?php echo h($class) ?>"><?php echo h($message) ?>
クレジットカード情報の変更を承りました。ご登録いただいたクレジットカード情報の反映には数日かかることがございます。</br>
お手数をお掛けしますが、詳細はお問い合わせください。
</p>
