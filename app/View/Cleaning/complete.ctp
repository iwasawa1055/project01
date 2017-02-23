<?php $this->Html->css('cleaning/app', ['block' => 'css']); ?>
<?php $this->Html->script('cleaning/app', ['block' => 'scriptMinikura']); ?>
  <h1 class="page-header"><i class="fa icon-cleaning"></i> minikuraCLEANING</h1>
  <h2 class="page-caption">以下の内容でクリーニングの申し込み手続きが完了しました。</h2>
  <div id="cleaning-wrapper">
    <div class="nav-cleaning">
    <ul>
      <li><a href="/" class="btn-next-full"><i class="fa fa-chevron-circle-left"></i> マイページへ戻る</a>
      </li>
    </ul>
    </div>
    <div class="grid">
      <ul>
        <!--loop-->
        <?php foreach ($itemList as $item): ?>
        <li class="item">
          <div class="item-select">
            <img src="<?php echo $item['image_url'];?>" alt="<?php echo $item['item_id'];?>">
          </div>
          <div class="item-caption">
          </div>
        </li>
        <?php endforeach; ?>
        <!--loop end-->
      </ul>
    </div>

  </div>
