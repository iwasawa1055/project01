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
            <li> <a href="/box?product=mono"><i class="fa fa-tag fa-fw"></i> MONO（<?php echo hash::get($product_summary, PRODUCT_CD_MONO, '0'); ?>箱）</a> </li>
            <li> <a href="/box?product=hako"><i class="fa fa-tag fa-fw"></i> HAKO（<?php echo hash::get($product_summary, PRODUCT_CD_HAKO, '0'); ?>箱）</a> </li>
            <li> <a href="/box?product=cargo01"><i class="fa fa-tag fa-fw"></i> CARGO じぶんで（<?php echo hash::get($product_summary, PRODUCT_CD_CARGO_JIBUN, '0'); ?>箱）</a> </li>
            <li> <a href="/box?product=cargo02"><i class="fa fa-tag fa-fw"></i> CARGO ひとまかせ（<?php echo hash::get($product_summary, PRODUCT_CD_CARGO_HITOMAKASE, '0'); ?>箱）</a> </li>
            <li> <a href="/box?product=cleaning"><i class="fa fa-tag fa-fw"></i> クリーニングパック（<?php echo hash::get($product_summary, PRODUCT_CD_CLEANING_PACK, '0'); ?>箱）</a> </li>
            <li> <a href="/box?product=shoes"><i class="fa fa-tag fa-fw"></i> シューズパック（<?php echo hash::get($product_summary, PRODUCT_CD_SHOES_PACK, '0'); ?>箱）</a> </li>
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
