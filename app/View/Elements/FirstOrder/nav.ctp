    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand animsition-link" href="<?php echo Configure::read('site.static_content_url'); ?>"><img src="https://minikura.com/contents/common/images/logo.png" alt=""/></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <?php if (!empty($customer) && $customer->isLogined()) : ?>
                    <li>
                        <a class="login" href="/login/logout" target="_blank"><i class="fa fa-sign-out fa-fw"></i> ログアウト</a>
                    </li>
                    <?php else : ?>
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="animsition-link" href="<?php echo Configure::read('site.static_content_url'); ?>/lineup/"><i class="fa fa-list-ul"></i> ラインナップ</a>
                    </li>
                    <li>
                        <a class="animsition-link" href="<?php echo Configure::read('site.static_content_url'); ?>/help/flow.html"><i class="fa fa-sitemap"></i> ご利用の流れ</a>
                    </li>
                    <li>
                        <a class="animsition-link" href="https://help.minikura.com/hc/ja"><i class="fa fa-question"></i> ヘルプセンター</a>
                    </li>
                    <li>
                        <a class="login" href="/login"><i class="fa fa-unlock-alt"></i> ログイン</a>
                    </li>
                    <li>
                        <a class="signin" href="/first_order/index" target="_blank"><i class="fa fa-sign-in"></i> 初回購入</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
<!-- PAGENATION -->
<section id="pagenation">
    <ul>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrder/add_order') { ?>class="on" <?php }?> >
            <i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrder/add_address') { ?>class="on" <?php }?> >
            <i class="fa fa-pencil-square-o"></i><span>お届け先<br>登録</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrder/add_credit') { ?>class="on" <?php }?> >
            <i class="fa fa-credit-card"></i><span>カード<br>登録</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrder/add_email') { ?>class="on" <?php }?> >
            <i class="fa fa-envelope"></i><span>メール<br>登録</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrder/confirm') { ?>class="on" <?php }?> >
            <i class="fa fa-check"></i><span>確認</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrder/complete') { ?>class="on" <?php }?> >
            <i class="fa fa-truck"></i><span>完了</span>
        </li>
    </ul>
</section>
