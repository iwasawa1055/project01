  <ul class="nav navbar-top-links navbar-right">
  <?php if (! $isLogined) { ?>
    <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#"> <i class="fa fa-user fa-fw"></i> 暫定メニュー <i class="fa fa-caret-down"></i> </a>
      <ul class="dropdown-menu dropdown-user">
        <li> <a href="/login"><i class="fa fa-pencil-square-o fa-fw"></i> ログイン</a> </li>
        <li><a class="animsition-link" href="/customer/password_reset/add"><i class="fa fa-lock fa-fw"></i> パスワード再発行</a> </li>
        <li> <a href="/inquiry"><i class="fa fa-pencil-square-o fa-fw"></i> お問い合わせ</a> </li>
      </ul>
    </li>
  <?php } else { ?>
    <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#"> <i class="fa fa-bell fa-fw"></i> お知らせ <i class="fa fa-caret-down"></i> </a>
      <ul class="dropdown-menu dropdown-alerts">
        <?php if (!empty($notice_announcements) && is_array($notice_announcements)) : ?>
        <?php foreach ($notice_announcements as $data): ?>
          <?php $url = '/announcement/detail/' . $data['announcement_id']; ?>
          <li> <a class="animsition-link" href="<?php echo $url; ?>">
            <div>
                <i class="fa fa-bell fa-fw"></i>
                <?php echo $data['title']; ?>
                <span class="pull-right text-muted small"><?php echo $data['date']; ?></span>
            </div>
            </a>
          </li>
          <li class="divider"></li>
        <?php endforeach; ?>
        <?php endif; ?>
        <li> <a class="animsition-link" class="text-center" href="/announcement/"> <strong>すべてのお知らせを見る</strong> <i class="fa fa-angle-right"></i> </a> </li>
      </ul>
    </li>
    <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#"> <i class="fa fa-user fa-fw"></i> 各種情報変更 <i class="fa fa-caret-down"></i> </a>
      <ul class="dropdown-menu dropdown-user">
        <li><a class="animsition-link" href="/customer/info/edit"><i class="fa fa-user fa-fw"></i> ユーザー情報変更</a> </li>
        <li><a class="animsition-link" href="/customer/email/edit"><i class="fa fa-envelope fa-fw"></i> メールアドレス変更</a> </li>
        <li><a class="animsition-link" href="/customer/credit_card/edit"><i class="fa fa-credit-card fa-fw"></i> クレジットカード変更</a> </li>
        <li><a class="animsition-link" href="/customer/address/add"><i class="fa fa-truck fa-fw"></i> お届け先追加・変更</a> </li>
        <li><a class="animsition-link" href="/customer/password/edit"><i class="fa fa-lock fa-fw"></i> パスワード変更</a> </li>
        <li class="divider"></li>
        <li> <a class="animsition-link" href="/contact_us/add"><i class="fa fa-pencil-square-o fa-fw"></i> お問い合わせ</a> </li>
        <li> <a class="animsition-link" href="agreement/index.html"><i class="fa fa-list-alt fa-fw"></i> 契約情報</a> </li>
        <li class="divider"></li>
        <li><a class="animsition-link" href="/login/logout"><i class="fa fa-sign-out fa-fw"></i> ログアウト</a> </li>
      </ul>
    </li>
  <?php } ?>
  </ul>
