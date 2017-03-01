  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse collapse">
      <ul class="nav in" id="side-menu">
          <?php if (in_array($this->action, Configure::read('api.sneakers.action_name'))) : ?>
		  <li>
			<?php // Sneakers 要件変更 key 制限解除 2017/03/01 modified by osada@terrada ?>
            <?php /*<a href="<?php echo Configure::read('site.sneakers.static_content_url'); ?>?key=<?php echo $key;?>"><i class="fa fa-home fa-fw"></i> トップページ</a> */ ?>
            <a href="<?php echo Configure::read('site.sneakers.static_content_url'); ?>"><i class="fa fa-home fa-fw"></i> トップページ</a>
		  </li>	
		  <li class="mysnkrs">
            <a href="<?php echo Configure::read('site.sneakers.MY_SNKRS_url'); ?>" target="_blank"><i class="fa fa-external-link fa-fw"></i>MY SNKRSへ</a>
		  </li>	
          <?php elseif (!empty($code) && $code ===  Configure::read('api.sneakers.alliance_cd')) : ?>
		  <li>
			<?php // Sneakers 要件変更 key 制限解除 2017/03/01 modified by osada@terrada ?>
            <?php /*<a href="<?php echo Configure::read('site.sneakers.static_content_url'); ?>?key=<?php echo $key;?>"><i class="fa fa-home fa-fw"></i> トップページ</a> */ ?>
            <a href="<?php echo Configure::read('site.sneakers.static_content_url'); ?>"><i class="fa fa-home fa-fw"></i> トップページ</a>
		  </li>	
		  <li class="mysnkrs">
            <a href="<?php echo Configure::read('site.sneakers.MY_SNKRS_url'); ?>" target="_blank"><i class="fa fa-external-link fa-fw"></i>MY SNKRSへ</a>
	 	  </li>	
          <?php else : ?>
		  <li>
            <a href="<?php echo Configure::read('site.top_page'); ?>"><i class="fa fa-home fa-fw"></i> トップページ</a>
		  </li>
          <?php endif; ?>
      </ul>
    </div>
  </div>
