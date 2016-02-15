<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<title>test</title>
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
dl {
}
dt {
  float:left;
}
dd {
  margin-left:150px;
}
</style>

<div class="container">
<dl>
<?php foreach ($data as $k => $list): ?>
    <h2><?php echo $k; ?></h2>
    <?php foreach ($list as $key => $value): ?>
        <dt><?php echo $key; ?></dt><dd id="<?php echo $key; ?>"><?php echo $value; ?></dd>
    <?php endforeach; ?>
<?php endforeach; ?>
</dl>
</div>

<div class="container">
<div class="col-md-3">
<h2>注文ID</h2><dl><dt>order_id</dt>
<?php foreach ($order_ids as $data): ?>
<dd><?php echo $data['order_id']; ?></dd>
<?php endforeach; ?>
</dl>
</div>

<div class="col-md-3">
<h2>作業ID（入庫）</h2><dl><dt>work_id</dt>
<?php foreach ($work_ids_001 as $data): ?>
<dd><?php echo $data['work_id']; ?></dd>
<?php endforeach; ?>
</dl>
</div>

<div class="col-md-3">
<h2>作業ID（出庫）</h2><dl><dt>work_id</dt>
<?php foreach ($work_ids_003 as $data): ?>
<dd><?php echo $data['work_id']; ?></dd>
<?php endforeach; ?>
</dl>
</div>

<div class="col-md-3">
<h2>作業ID（出庫（期限付き））</h2><dl><dt>work_id</dt>
<?php foreach ($work_ids_006 as $data): ?>
<dd><?php echo $data['work_id']; ?></dd>
<?php endforeach; ?>
</dl>
</div>
</div>

<div class="container">
<h2>ボックスアイテム</h2>
<div class="boxitem row">
<?php
$a = [
    BOXITEM_STATUS_BUYKIT_START => 'キット購入',
    BOXITEM_STATUS_BUYKIT_DONE => 'キット発送',
    BOXITEM_STATUS_INBOUND_START => '入庫受付',
    BOXITEM_STATUS_INBOUND_DONE => '入庫終了',
    BOXITEM_STATUS_OUTBOUND_START => '出庫受付',
    BOXITEM_STATUS_OUTBOUND_DONE => '出庫終了',
];
foreach ($a as $status => $label): ?>
    <div class="col-md-2">
        <?php if (!array_key_exists($status, $boxData)) {
            $boxData[$status] = [];
        } ?>
    <p><?php echo $status . ' ' . $label . ' (' . count($boxData[$status]) . ')'; ?></p>
        <?php foreach ($boxData[$status] as $box): ?>
        <div class="col-md-12">
            <div class="box">
                <?php if (!array_key_exists($box['box_id'], $timeData)) {
                        $timeData[$box['box_id']] = [];
                    }  ?>
                <p>
                    <?php echo $box['product_cd']; ?>,
                    <?php echo $box['box_id'];  ?>
                    <?php if (count($timeData[$box['box_id']]) !== 0) {
                            echo ' (' . count($timeData[$box['box_id']]) . ')';
                        }  ?>
                    <br>
                    <?php echo $box['box_name']; ?>
                </p>
                    <?php foreach ($timeData[$box['box_id']] as $item): ?>
                        <div class="item">
                            <p>
                                <?php echo $item['item_status']; ?>,
                                <?php echo $item['item_id']; ?><br>
                                <?php echo $item['item_name']; ?>
                            </p>
                        </div>
                    <?php endforeach; ?>

            </div>
        </div>
    <?php endforeach; ?>

    </div>
<?php endforeach; ?>
</div>
</div>
</body>
</html>
