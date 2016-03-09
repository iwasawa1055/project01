  <ul class="nav navbar-top-links navbar-right">
  <?php if (!empty($isLogined) && $isLogined === true) : ?>
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-bell fa-fw"></i> お知らせ <i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-alerts">
        <?php if (!empty($notice_announcements) && is_array($notice_announcements)) : ?>
        <?php foreach ($notice_announcements as $data): ?>
          <?php $url = '/announcement/detail/' . $data['announcement_id']; ?>
          <li> <a href="<?php echo $url; ?>">
            <div>
                <i class="fa fa-bell fa-fw"></i>
                <?php echo h($data['title']); ?>
                <span class="pull-right text-muted small"><?php echo $data['date']; ?></span>
            </div>
            </a>
          </li>
          <li class="divider"></li>
        <?php endforeach; ?>
        <?php endif; ?>
        <li>
          <a class="text-center" href="/announcement/">
            <strong>すべてのお知らせを見る</strong> <i class="fa fa-angle-right"></i>
          </a>
        </li>
      </ul>
    </li>
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-user fa-fw"></i> <?php echo h($customerName); ?> <i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-user">
        <li><a href="/customer/email/edit"><i class="fa fa-envelope fa-fw"></i> メールアドレス変更</a> </li>
      <?php if ($hasCreditCard)  : ?>
        <?php // 個人アカウントまたは、法人(クレジットカード)のみ表示 ?>
        <li><a href="/customer/credit_card/edit"><i class="fa fa-credit-card fa-fw"></i> クレジットカード変更</a> </li>
      <?php endif; ?>
      <?php if (!$isEntry) : ?>
        <li><a href="/customer/address/"><i class="fa fa-truck fa-fw"></i> 住所・お届け先変更</a> </li>
      <?php endif; ?>
        <li><a href="/customer/password/edit"><i class="fa fa-lock fa-fw"></i> パスワード変更</a> </li>
      <?php if (!$isEntry) : ?>
        <li><a href="/contract"><i class="fa fa-list-alt fa-fw"></i> 契約情報</a> </li>
      <?php endif; ?>
        <li class="divider"></li>
        <li><a href="/contact_us/add"><i class="fa fa-pencil-square-o fa-fw"></i> お問い合わせ</a> </li>
        <li class="divider"></li>
        <li><a href="/login/logout"><i class="fa fa-sign-out fa-fw"></i> ログアウト</a> </li>
      </ul>
    </li>
  <?php else: ?>
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="fa fa-user fa-fw"></i> 各種情報変更 <i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-user">
        <li> <a href="/inquiry/add"><i class="fa fa-pencil-square-o fa-fw"></i> お問い合わせ</a> </li>
      </ul>
    </li>
  <?php endif; ?>
</ul>
