<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<title>dev - customer</title>
<?php
  echo $this->Html->meta('icon');
  echo $this->fetch('meta');

  $this->Html->css('bootstrap.min', ['inline' => false]);

  echo $this->fetch('css');
?>
</head>
<body>

<style>
h2 {
    border: 0px solid #165828;
    border-width: 0 0 3px 5px;
    padding-left: 10px;
}
.boxitem .box {
    background-color: #e8f04f;
    border: 1px solid #948f17;
    padding: 4px;
    margin-bottom: 2px;
}
.boxitem .box p {
    padding: 1px;
    margin: 0;
    font-size: .9em;
}
.boxitem .box .item {
    background-color: #87e07b;
    border: 1px solid #328f12;
    padding: 1px;
    margin: 0;
    font-size: .7em;
    margin-bottom: 2px;
}
.boxitem .box .outbound {
    background-color: #6cb362;
    border: 1px solid #328f12;
    padding: 1px;
    margin: 0;
    font-size: .7em;
    margin-bottom: 2px;
}
}
dl {
}
dt {
  float:left;
}
dd {
  margin-left:150px;
}

.border_top {
  border-top: solid 2px #8491c3;
  padding: 5px;
}

.box_area {
  overflow:  hidden;
}

.box_status {
  padding: 0.5em 1em;
  margin: 1em 0;
  background: #f4f4f4;
  border-left: solid 6px #6cb362;
  box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.33);
}
.box_status p {
  margin: 0;
  padding: 0;
}

.box_info {
  float:  left;
  margin: 0.5em auto;
  margin-right: 0.5em;
  padding: 0.5em;
  width: 32%;
  border: 5px double #8491c3; /*太さ・線種・色*/
  color: #333; /* 文字色 */
  background-color: #fff; /* 背景色 */
  border-radius: 1px; /*角の丸み*/
}

.box_info p {
  margin: 0px 0 4px;
}

.box_info a {
  margin: 5px;
}

.item_info {
  float:  left;
  width: 49%;
  padding: 0.5em 1em;
  margin: 0.1em auto;
  margin-right: 0.2em;
  border: solid 2px #8491c3;
}
.item_info p {
  font-size: 12px;
  margin: 0;
  padding: 0;
}

p {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

</style>

<div class="container">
<dl>
<?php foreach ($data as $k => $list): ?>
    <h2><?php echo $k; ?></h2>
    <?php foreach ($list as $key => $value): ?>
        <dt><?php echo $key; ?></dt><dd id="<?php echo $key; ?>"><?php echo $value; ?>　</dd>
    <?php endforeach; ?>
<?php endforeach; ?>
<br>
<dt>情報変更申請</dt>
    <dd><a href="/dev/user_applying?approval=1">承認</a></dd>
    <dd><a href="/dev/user_applying?approval=0">却下</a></dd>
<dt>債務ランク</dt>
    <dd><a href="/dev/user_debt?payment=1">債務解除</a></dd>
    <dd><a href="/dev/user_debt?payment=2">債務状態</a></dd>
<dt>請求情報</dt>
    <dd><a href="/dev/billing">強制作成</a></dd>
</dl>
</div>

<?php if (!$customer->isEntry()) : ?>
<div class="container">
  <h2>ボックスアイテム情報</h2>

  <?php
  $status_list = [
      BOXITEM_STATUS_BUYKIT_START => 'キットサービスの申し込み済み',
      BOXITEM_STATUS_BUYKIT_DONE => 'キット発送',
      BOXITEM_STATUS_INBOUND_START => '入庫受付',
      BOXITEM_STATUS_INBOUND_DONE => '入庫完了',
      BOXITEM_STATUS_OUTBOUND_START => '出庫受付',
      BOXITEM_STATUS_OUTBOUND_DONE => '出庫終了',
  ];
  ?>

  <?php foreach ($status_list as $status => $status_name) : ?>
  <div class="box_area" >
    <div class="box_status"><p><?php echo $status . ' : ' . $status_name; ?></p></div>
    <?php if ($status == BOXITEM_STATUS_OUTBOUND_START) : ?>
      <p style="color:red;">※「work_linkage_id」押下で出庫完了処理を実行します。複数出庫場合は同時に出庫依頼をかけたボックスも出庫されます</p>
    <?php endif; ?>
    <?php if (isset($boxData[$status])) : ?>
    <?php foreach ($boxData[$status] as $box) : ?>
    <div class="box_info">
      <p><b>ボックス情報</b></p>
      <p>kit_name : <?php echo $box['kit_name']; ?></p>
      <p>box_name : <?php echo $box['box_name']; ?></p>
      <p>box_id : <?php echo $box['box_id']; ?></p>
      <p>kit_cd : <?php echo $box['kit_cd']; ?></p>
      <?php if ($status == BOXITEM_STATUS_OUTBOUND_START) : ?>
      <p class="border_top"><b>出庫</b></p>
      <p>work_linkage_id : <a href="/dev/outbound_done?work_linkage_id=<?php echo $box['outbound_linkage_id']; ?>"><?php echo $box['outbound_linkage_id']; ?></a></p>
      <?php endif; ?>
      <!-- 入庫受付 -->
      <?php if ($status == BOXITEM_STATUS_INBOUND_START) : ?>
      <p class="border_top"><b>ボックス入庫</b></p>
      <?php if ($box['product_cd'] === PRODUCT_CD_HAKO) : ?>
      <a href="/dev/inbound_done?number=1&box_id=<?php echo $box['box_id']; ?>">done</a>
      <?php else : ?>
      <a href="/dev/inbound_done?number=1&box_id=<?php echo $box['box_id']; ?>">done_1</a>
      <a href="/dev/inbound_done?number=5&box_id=<?php echo $box['box_id']; ?>">done_5</a>
      <a href="/dev/inbound_done?number=10&box_id=<?php echo $box['box_id']; ?>">done_10</a>
      <?php if ($box['product_cd'] === PRODUCT_CD_MONO) : ?>
      <a href="/dev/inbound_done?number=25&box_id=<?php echo $box['box_id']; ?>">done_25</a>
      <?php endif; ?>
      <?php endif; ?>
      <?php endif; ?>

      <!-- over 入庫完了 -->
      <?php if ($status == BOXITEM_STATUS_INBOUND_DONE || $status == BOXITEM_STATUS_OUTBOUND_START || $status == BOXITEM_STATUS_OUTBOUND_DONE) : ?>
      <?php if ($box['product_cd'] != PRODUCT_CD_HAKO) : ?>
      <p class="border_top"><b>アイテム情報 (<?php echo count($timeData[$box['box_id']]); ?>)</b></p>
      <div class="box_area" >
      <?php foreach ($timeData[$box['box_id']] as $item) : ?>
        <div class="item_info">
          <p>item_status : <?php echo $item['item_status']; ?></p>
          <p>item_id : <?php echo $item['item_id']; ?></p>
          <p>item_name : <?php echo $item['item_name']; ?></p>
        </div>
      <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <?php endif; ?>

    </div>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
</body>
</html>
