<section id="dsn-pagenation">
    <ul>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'Order/input') { ?>class="dsn-on" <?php }?> >
            <i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'Order/confirm') { ?>class="dsn-on" <?php }?> >
            <i class="fa fa-check"></i><span>確認</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'Order/complete') { ?>class="dsn-on" <?php }?> >
            <i class="fa fa-truck"></i><span>完了</span>
        </li>
    </ul>
</section>
