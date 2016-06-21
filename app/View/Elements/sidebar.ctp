  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
      <ul class="nav in" id="side-menu">

		<!--feature_mypage_menu test_Start-->
		<!--
        <li class="sidebar-search">
          <?php echo $this->Form->create('GlobalSreach', ['id' => 'sidebar-search', 'url' => ['controller' => 'result', 'action' => 'index'], 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
          <div class="input-group custom-search-form">
            <?php echo $this->Form->text("keyword", ['class' => 'form-control', 'error' => false, 'placeholder' => 'Search...']); ?>
            <span class="input-group-btn">
              <button type="submit" class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
            </span>
          </div>
          <?php echo $this->Form->error("keyword", null, ['wrap' => 'p']) ?>
          <?php echo $this->Form->end(); ?>
        </form>
        </li>
		-->
		<!--feature_mypage_menu test_End-->

        <li> <a href="/"><i class="fa fa-home fa-fw"></i> マイページ</a> </li>

        <?php if (!empty($customer) && !$customer->isEntry()) : ?>

		<!--feature_mypage_menu test_Start-->
        <li> <a href="#"><i class="fa fa-diamond fa-fw"></i>アイテムリスト<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
		    <?php if (! empty($product_summary)):?>
                <li> <a href="/item?"><i class="fa fa-diamond fa-fw"></i>すべてのアイテム</a> </li>
			<?php endif;?>
            <?php foreach(IN_USE_SERVICE['minikura'] as $v):?>
              <?php if(hash::get($product_summary, $v['product_cd'], '0') > 0) : ?>
			    <!--HOKO除外-->
				<?php if ($v['product'] !== 'hako' ):?>
			      <!--todo 箱の項目ごとのアイテムに-->
                  <li> <a href="/item?product=<?php echo $v['product'];?>"><i class="fa fa-diamond fa-fw"></i> <?php echo $v['name'];?></a> </li>
				<?php endif;?>
              <?php endif;?>
            <?php endforeach;?>
          </ul>
        </li>
		<!--feature_mypage_menu test_End-->

        <li> <a href="#"><i class="fa fa-cube fa-fw"></i>ボックスリスト<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
		    <!--feature_mypage_menu test_Start-->
		    <?php if (! empty($product_summary)):?>
                <li> <a href="/box?product="><i class="fa fa-cube fa-fw"></i> すべてのボックス（<?php echo array_sum($product_summary); ?>箱）</a> </li>
			<?php endif;?>
		    <!--feature_mypage_menu test_End-->
            <?php foreach(IN_USE_SERVICE['minikura'] as $v):?>
              <?php if(hash::get($product_summary, $v['product_cd'], '0') > 0) : ?>
                <li> <a href="/box?product=<?php echo $v['product'];?>"><i class="fa fa-cube fa-fw"></i> <?php echo $v['name'];?>（<?php echo hash::get($product_summary, $v['product_cd'], '0'); ?>箱）</a> </li>
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
        <?php if (!empty($customer) && !$customer->isEntry()) : ?>
        <li>
          <a href="/mini_auction/" target="_blank"><i class="fa fa-gavel fa-fw"></i> ヤフオク! 出品</a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
