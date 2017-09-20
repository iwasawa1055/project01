<ul class="nav navbar-top-links navbar-right">
  <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
      <i class="fa fa-user fa-fw"></i> <?php echo h($customer->getName()); ?> <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu dropdown-user">
      <li><a class="animsition-link" href="/customer/email/edit"><i class="fa fa-envelope fa-fw"></i> メールアドレス変更</a> </li>
    <?php if (!empty($customer) && $customer->hasCreditCard()) : ?>
      <?php // 個人アカウントまたは、法人(クレジットカード)のみ表示 ?>
      <?php if ($customer->isAmazonPay() === false) : ?>
        <?php // かつアマゾンペイメントのユーザーではない場合に表示 ?>
        <li><a class="animsition-link" href="/customer/credit_card/edit"><i class="fa fa-credit-card fa-fw"></i> クレジットカード変更</a> </li>
      <?php endif; ?>
    <?php endif; ?>
    <?php if (!$customer->isEntry() && ($customer->isCustomerCreditCardUnregist() || $customer->isCorprateCreditCardUnregist())) : ?>
      <?php // 本登録アカウントかつクレジットカード登録なし(個人、法人)のみ表示 ?>
      <?php if ($customer->isAmazonPay() === false) : ?>
        <?php // かつアマゾンペイメントのユーザーではない場合に表示 ?>
        <li><a class="animsition-link" href="/customer/credit_card/add"><i class="fa fa-credit-card fa-fw"></i> クレジットカード登録</a> </li>
      <?php endif; ?>
    <?php endif; ?>
    <?php if (!empty($customer) && !$customer->isEntry() && !$customer->isAmazonPay()) : ?>
      <li><a class="animsition-link" href="/customer/address/"><i class="fa fa-truck fa-fw"></i> お届け先変更</a> </li>
    <?php endif; ?>
      <li><a href="/customer/password/edit"><i class="fa fa-lock fa-fw"></i> パスワード変更</a> </li>
    <?php if (!empty($customer) && !$customer->isEntry()) : ?>
      <?php if ( !$customer->isSneaker()) : ?>
	  <li><a class="animsition-link" href="/point/"><i class="fa fa-database fa-fw"></i> ポイント情報</a>
	  <?php endif; ?>
      <li><a class="animsition-link" href="/contract"><i class="fa fa-list-alt fa-fw"></i> 会員情報</a> </li>
    <?php endif; ?>
      <li class="divider"></li>
      <li><a href="/contact_us/add"><i class="fa fa-pencil-square-o fa-fw"></i> お問い合わせ</a> </li>
      <li class="divider"></li>
      <li><a id="Logout" href="/login/logout"><i class="fa fa-sign-out fa-fw"></i> ログアウト</a> </li>
    </ul>
  </li>
</ul>
