  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse collapse">
      <ul class="nav in" id="side-menu">
        <li> <a href="/"><i class="fa fa-home fa-fw"></i> マイページ</a> </li>
        <?php if (!empty($customer) && !$customer->isEntry()) : ?>
		<li> <a href="#"><i class="fa fa-diamond fa-fw"></i>アイテムリスト<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
		    <?php if (! empty($summary_all)):?>
                <li> <a class="animsition-link" href="/item?"><i class="fa fa-diamond fa-fw"></i>すべてのアイテム</a> </li>
            <?php endif;?>
            <?php foreach(IN_USE_SERVICE['sneakers'] as $v):?>
              <?php if(hash::get($summary_all, $v['product_cd'], '0') > 0) : ?>
                <?php if ($v['product'] !== 'hako' ):?>
                  <li> <a class="animsition-link" href="/item?product=<?php echo $v['product'];?>"><i class="fa fa-diamond fa-fw"></i><?php echo $v['name'];?></a> </li>
                <?php endif;?>
              <?php endif;?>
            <?php endforeach;?>
          </ul>
        </li>
        <li> <a href="#"><i class="fa fa-cube fa-fw"></i>ボックスリスト<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <?php if (! empty($summary_all)):?>
                <li> <a class="animsition-link" href="/box?product="><i class="fa fa-cube fa-fw"></i> すべてのボックス（<?php echo array_sum($product_summary); ?>箱）</a> </li>
            <?php endif;?>
            <?php foreach(IN_USE_SERVICE['sneakers'] as $v):?>
              <?php if(hash::get($summary_all, $v['product_cd'], '0') > 0) : ?>
                <li> <a class="animsition-link" href="/box?product=<?php echo $v['product'];?>"><i class="fa fa-cube fa-fw"></i> <?php echo $v['name'];?>（<?php echo hash::get($product_summary, $v['product_cd'], '0'); ?>箱）</a> </li>
              <?php endif;?>
            <?php endforeach;?>
          </ul>
        </li>
        <?php endif; ?>
        <li> <a href="/order/add"><i class="fa fa-shopping-cart fa-fw"></i> ボックス購入</a></li>
        <?php if (!empty($customer) && $customer->canInbound()) : ?>
        <li> <a href="#"><i class="fa fa-arrow-circle-o-up fa-fw"></i> 預け入れ<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="/inbound/box/add"><i class="fa fa-arrow-circle-o-up fa-fw"></i> ボックス預け入れ</a> </li>
          </ul>
        </li>
        <?php endif; ?>
        <?php if (!empty($customer) && $customer->canOutbound()) : ?>
        <li> <a href="#"><i class="fa fa-arrow-circle-o-down fa-fw"></i> 取り出し<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="/outbound/mono"><i class="fa fa-arrow-circle-o-down fa-fw"></i> アイテムを取り出す</a> </li>
            <li> <a href="/outbound/box"><i class="fa fa-arrow-circle-o-down fa-fw"></i> ボックスを取り出す</a> </li>
          </ul>
        </li>
        <?php endif; ?>
        <li class="mysnkrs"> <a href="<?php echo Configure::read('site.sneakers.MY_SNKRS_url'); ?>" target="_blank"><i class="fa fa-external-link fa-fw"></i>MY SNKRSへ</a> </li>
      </ul>
    </div>
  </div>
