  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
      <ul class="nav in" id="side-menu">
        <li>
          <?php if (in_array($this->action, Configure::read('api.sneakers.action_name'))) : ?>
            <a href="<?php echo Configure::read('site.sneakers.static_content_url'); ?>?key=<?php echo $key;?>"><i class="fa fa-home fa-fw"></i> トップページ</a>
          <?php elseif (!empty($code) && $code ===  Configure::read('api.sneakers.alliance_cd')) : ?>
            <a href="<?php echo Configure::read('site.sneakers.static_content_url'); ?>?key=<?php echo $key;?>"><i class="fa fa-home fa-fw"></i> トップページ</a>
          <?php else : ?>
            <a href="<?php echo Configure::read('site.top_page'); ?>"><i class="fa fa-home fa-fw"></i> トップページ</a>
          <?php endif; ?>
        </li>
      </ul>
    </div>
  </div>
