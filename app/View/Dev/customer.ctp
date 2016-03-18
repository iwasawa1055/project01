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
<dt>紹介コードリンク</dt>
    <dd><a href="/customer/register/add?code=yahoo" target="_blank">yahoo</a></dd>
    <dd><a href="/customer/register/add?code=daito" target="_blank">daito</a></dd>
</dl>
</div>

<?php if (!$customer->isEntry()) : ?>
<div class="container">
<h2>注文ID、作業ID</h2>
<div class="col-md-3 order_id">
<dl><dt>注文ID</dt>
<?php foreach ($order_ids as $data): ?>
<dd>
    <span><?php echo $data['order_id']; ?></span>
    <a href="/dev/delivery_done?order_id=<?php echo $data['order_id']; ?>">done</a>
</dd>
<?php endforeach; ?>
</dl>
</div>

<div class="col-md-3 inbound_box_id">
<dl><dt>入庫</dt>
<?php if (array_key_exists(BOXITEM_STATUS_INBOUND_START, $boxData)): ?>
<?php foreach ($boxData[BOXITEM_STATUS_INBOUND_START] as $data): ?>
<dd>
<?php //pr($data); ?>
    <span><?php echo $data['box_id']; ?></span>
  <?php if ($data['product_cd'] === PRODUCT_CD_HAKO) : ?>
    <a href="/dev/inbound_done?number=1&box_id=<?php echo $data['box_id']; ?>">done</a>
  <?php else : ?>
    <a href="/dev/inbound_done?number=1&box_id=<?php echo $data['box_id']; ?>">done_1</a>
    <a href="/dev/inbound_done?number=5&box_id=<?php echo $data['box_id']; ?>">done_5</a>
    <a href="/dev/inbound_done?number=10&box_id=<?php echo $data['box_id']; ?>">done_10</a>
    <?php if ($data['product_cd'] === PRODUCT_CD_MONO) : ?>
      <a href="/dev/inbound_done?number=25&box_id=<?php echo $data['box_id']; ?>">done_25</a>
    <?php endif; ?>
  <?php endif; ?>
</dd>
<?php endforeach; ?>
<?php endif; ?>
</dl>
</div>


<div class="col-md-3 outbound_work_id">
<dl><dt>作業ID（出庫）</dt>
<?php foreach ($work_ids_003 as $data): ?>
<dd>
    <span><?php echo $data['work_id']; ?></span>
    <a href="/dev/outbound_done?work_id=<?php echo $data['work_id']; ?>">done</a>
</dd>
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
    // BOXITEM_STATUS_INBOUND_IN_PROGRESS => '入庫進行中',
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
                    <?php echo $box['kit_cd']; ?>,
                    <?php echo $box['box_id'];  ?>
                    <?php if (count($timeData[$box['box_id']]) !== 0) {
                            echo ' (' . count($timeData[$box['box_id']]) . ')';
                        }  ?>
                    <br>
                    <?php echo $box['box_name']; ?>
                </p>
                    <?php foreach ($timeData[$box['box_id']] as $item): ?>
                        <div class="<?php echo (BOXITEM_STATUS_INBOUND_DONE < $item['item_status']) ? 'outbound' : 'item'; ?>">
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
<?php endif; ?>
</div>
</div>
</body>
</html>
