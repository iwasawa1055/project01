  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse collapse">
      <ul class="nav in" id="side-menu">
        <li> <a class="animsition-link" href="/"><i class="fa fa-home fa-fw"></i> マイページ</a> </li>
        <?php if (!empty($customer) && !$customer->isEntry()) : ?>

        <li<?php if($active_status['item']['toggle']):?> class="active"<?php endif;?>> <a href="#"><i class="fa fa-diamond fa-fw"></i> アイテムリスト<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a class="animsition-link<?php if($active_status['item']['all']):?> active<?php endif;?>" href="/item?product="><i class="fa fa-diamond fa-fw"></i> すべてのアイテム</a> </li>
            <?php foreach(IN_USE_SERVICE['minikura'] as $v):?>
              <?php if(hash::get($product_summary, $v['product_cd'], '0') > 0) : ?>
                <?php if ($v['product'] !== 'hako' ):?>
                  <li> <a class="animsition-link<?php if($active_status['item'][$v['product']]):?> active<?php endif;?>" href="/item?product=<?php echo $v['product'];?>"><i class="fa fa-diamond fa-fw"></i><?php echo $v['name'];?></a> </li>
                <?php endif;?>
              <?php endif;?>
            <?php endforeach;?>
          </ul>
        </li>
        <li<?php if($active_status['box']['toggle']):?> class="active"<?php endif;?>> <a href="#"><i class="fa fa-cube fa-fw"></i> ボックスリスト<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <?php if (! empty($summary_all)):?>
              <li> <a class="animsition-link<?php if($active_status['box']['all']):?> active<?php endif;?>" href="/box?product="><i class="fa fa-cube fa-fw"></i> すべてのボックス（<?php echo array_sum($product_summary); ?>箱）</a> </li>
            <?php endif;?>
            <?php foreach(IN_USE_SERVICE['minikura'] as $v):?>
              <?php if(hash::get($summary_all, $v['product_cd'], '0') > 0) : ?>
                <li> <a class="animsition-link<?php if($active_status['box'][$v['product']]):?> active<?php endif;?>" href="/box?product=<?php echo $v['product'];?>"><i class="fa fa-cube fa-fw"></i> <?php echo $v['name'];?>（<?php echo hash::get($product_summary, $v['product_cd'], '0'); ?>箱）</a> </li>
              <?php endif;?>
            <?php endforeach;?>
          </ul>
        </li>

        <?php endif; ?>
        <li> <a class="animsition-link" href="/order/add"><i class="fa fa-shopping-cart fa-fw"></i> ボックス購入</a></li>
        <?php if (!empty($customer) && $customer->canInbound()) : ?>
        <li> <a href="#"><i class="fa fa-arrow-circle-o-up fa-fw"></i> 預け入れ<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a class="animsition-link" href="/inbound/box/add"><i class="fa fa-arrow-circle-o-up fa-fw"></i> ボックス預け入れ</a> </li>
            <li> <a class="animsition-link" href="/direct_inbound/input"><i class="fa fa-arrow-circle-o-up fa-fw"></i> minikuraダイレクト</a> </li>
          </ul>
        </li>
        <?php endif; ?>
        <?php if (!empty($customer) && $customer->canOutbound()) : ?>
        <li class="separator"> <a href="#"><i class="fa fa-arrow-circle-o-down fa-fw"></i> 取り出し<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a class="animsition-link" href="/outbound/mono"><i class="fa fa-arrow-circle-o-down fa-fw"></i> アイテムを取り出す</a> </li>
            <li> <a class="animsition-link" href="/outbound/box"><i class="fa fa-arrow-circle-o-down fa-fw"></i> ボックスを取り出す</a> </li>
          </ul>
        </li>
        <?php endif; ?>
        <?php if (!empty($customer) && !$customer->isEntry()) : ?>
        <li<?php if($active_status['cleaning']):?> class="active"<?php endif;?>>
	    <a href="/cleaning/input"<?php if($active_status['cleaning']):?> class="active"<?php endif;?>><i class="fa icon-cleaning fa-fw"></i> minikuraCLEANING＋</a>	</li>
        <?php if (!$customer->isAmazonPay()) : ?>
        <li<?php if($active_status['travel']):?> class="active"<?php endif;?>>
            <a href="/travel/mono"<?php if($active_status['travel']):?> class="active"<?php endif;?>><i class="fa fa-suitcase fa-fw"></i> minikura teburaTRAVEL</a>
        </li>
        <?php endif; ?>
        <li class="separator">
          <a href="/mini_auction/" target="_blank"><i class="fa fa-gavel fa-fw"></i> ヤフオク! 出品</a>
        </li>
        <?php endif; ?>
        <li>
          <a href="https://help.minikura.com/hc/ja" target="_blank"><i class="fa fa-question-circle fa-fw"></i> ヘルプ</a>
        </li>
      </ul>
    </div>
  </div>
