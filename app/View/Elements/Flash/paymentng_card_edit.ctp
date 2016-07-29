<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
?>
<p id="<?php echo h($key) ?>Message" class="error-message <?php echo h($class) ?>">
お支払い状況をご確認ください</br>
</br>
いつもminikuraをご利用いただき、ありがとうございます。</br>
</br>
現在、お客様とのご契約に基づき、保管品をお預かりしておりますが、お支払いが確認できておりません。</br>
</br>
下記にてクレジットカード情報の変更を承ります。</br>
</br>
※必ず、ご契約者様名義のクレジットカード情報をご登録ください。</br>
※ご登録いただいたクレジットカード情報の反映には数日かかることがございます。</br>
</p>
<?php if (!empty($message)) : ?>
<p class="error-message"><?php echo h($message) ?></p>
<?php endif; ?>
