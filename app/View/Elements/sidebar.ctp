  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
      <ul class="nav" id="side-menu">
      <?php // TODO: ログイン状態により切り替え ?>
      <?php if ($_SERVER["REQUEST_URI"] === '/') { ?>
        </li>
        <li> <a href="/"><i class="fa fa-home fa-fw"></i> トップページ</a> </li>
      <?php } else { ?>
        <li class="sidebar-search">
          <div class="input-group custom-search-form">
            <input type="text" class="form-control" placeholder="Search...">
            <span class="input-group-btn">
            <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
            </span> </div>
        </li>
        <li> <a class="animsition-link" href="/"><i class="fa fa-home fa-fw"></i> マイページ</a> </li>
        <li> <a href="#"><i class="fa fa-tags fa-fw"></i> ご利用中のサービス<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a class="animsition-link" href="/box"><i class="fa fa-tag fa-fw"></i> MONO（00箱）</a> </li>
            <li> <a class="animsition-link" href="#"><i class="fa fa-tag fa-fw"></i> HAKO（00箱）</a> </li>
<!-- 
            <li> <a class="animsition-link" href="#"><i class="fa fa-tag fa-fw"></i> CARGO じぶんで（00箱）</a> </li>
            <li> <a class="animsition-link" href="#"><i class="fa fa-tag fa-fw"></i> CARGO ひとまかせ（00箱）</a> </li>
            <li> <a class="animsition-link" href="#"><i class="fa fa-tag fa-fw"></i> MONO-wine-（00箱）</a> </li>
            <li> <a class="animsition-link" href="#"><i class="fa fa-tag fa-fw"></i> HAKO-wine-（00箱）</a> </li>
            <li> <a class="animsition-link" href="#"><i class="fa fa-tag fa-fw"></i> クリーニングパック（00箱）</a> </li>
 -->
          </ul>
        </li>
        <li> <a class="animsition-link" href="buy/index.html"><i class="fa fa-shopping-cart fa-fw"></i> ボックス購入</a></li>
        <li> <a href="#"><i class="fa fa-arrow-circle-o-up fa-fw"></i> 預け入れ<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a class="animsition-link" href="/inbound/box/add"><i class="fa fa-arrow-circle-o-up fa-fw"></i> ボックス預け入れ</a> </li>
<!-- 
            <li> <a class="animsition-link" href="deposit/shoes.html"><i class="fa fa-arrow-circle-o-up fa-fw"></i> シューズクリーニング</a> </li>
 -->
            <li> <a class="animsition-link" href="/inbound/shoe/add"><i class="fa fa-arrow-circle-o-up fa-fw"></i> ボックスレス</a> </li>
          </ul>
        </li>
        <li> <a href="#"><i class="fa fa-arrow-circle-o-down fa-fw"></i> 取り出し<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a class="animsition-link" href="takeout/item.html"><i class="fa fa-arrow-circle-o-down fa-fw"></i> アイテムを取り出す</a> </li>
            <li> <a class="animsition-link" href="takeout/box.html"><i class="fa fa-arrow-circle-o-down fa-fw"></i> ボックスを取り出す</a> </li>
          </ul>
        </li>
      <?php } ?>
      </ul>
    </div>
  </div>
