  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
      <ul class="nav in" id="side-menu">
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
        <li> <a href="/"><i class="fa fa-home fa-fw"></i> マイページ</a> </li>
        <?php if (!empty($customer) && !$customer->isEntry()) : ?>
        <li> <a href="#"><i class="fa fa-tags fa-fw"></i> ご利用中のサービス<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <?php foreach(IN_USE_SERVICE['sneakers'] as $v):?>
              <?php if(hash::get($product_summary, $v['product_cd'], '0') > 0) : ?>
                <li> <a href="/box?product=<?php echo $v['product'];?>"><i class="fa fa-tag fa-fw"></i> <?php echo $v['name'];?>（<?php echo hash::get($product_summary, $v['product_cd'], '0'); ?>箱）</a> </li>
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
      </ul>
    </div>
  </div>