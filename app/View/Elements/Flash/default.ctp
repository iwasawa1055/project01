<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>
<p id="<?php echo h($key) ?>Message" class="error-message <?php echo h($class) ?>"><?php echo h($message) ?></p>
