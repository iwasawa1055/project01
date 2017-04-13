<!-- PAGENATION -->
<section id="dsn-pagenation">
    <ul>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrderDirectInbound/add_address') { ?>class="dsn-on" <?php }?> >
            <i class="fa fa-pencil-square-o"></i><span>集荷内容<br>登録</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrderDirectInbound/add_credit') { ?>class="dsn-on" <?php }?> >
            <i class="fa fa-credit-card"></i><span>カード<br>登録</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrderDirectInbound/add_email') { ?>class="dsn-on" <?php }?> >
            <i class="fa fa-envelope"></i><span>メール<br>登録</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrderDirectInbound/confirm') { ?>class="dsn-on" <?php }?> >
            <i class="fa fa-check"></i><span>確認</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrderDirectInbound/complete') { ?>class="dsn-on" <?php }?> >
            <i class="fa fa-truck"></i><span>完了</span>
        </li>
    </ul>
</section>
