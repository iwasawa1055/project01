<?php
/**
* app/View/Layouts/default.ctpを使用しない
* -mypage.minikuraでも/Layouts/default.ctp,Layouts/sales.ctp,と二元管理した
* -今後デザイナーさんと協業した時にわかりやすくもしたい
* 各アクションメソッドに対応したctpファイル内で、$this->element('first')などを記載していく
*
*/
?>
<?php echo $this->fetch('content'); ?>
