<!-- PAGENATION -->
<section id="pagenation">
    <ul>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrder/add_order') { ?>class="on" <?php }?> >
            <i class="fa fa-hand-o-right"></i><span>ボックス<br>選択</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrder/add_address') { ?>class="on" <?php }?> >
            <i class="fa fa-pencil-square-o"></i><span>お届け先<br>登録</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrder/add_email') { ?>class="on" <?php }?> >
            <i class="fa fa-envelope"></i><span>メール<br>登録</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrder/confirm') { ?>class="on" <?php }?> >
            <i class="fa fa-check"></i><span>注文内容<br>確認</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrder/add_credit') { ?>class="on" <?php }?> >
            <i class="fa fa-credit-card"></i><span>カード<br>登録</span>
        </li>
        <li <?php if (CakeSession::read('app.data.session_referer') === 'FirstOrder/complete') { ?>class="on" <?php }?> >
            <i class="fa fa-truck"></i><span>完了</span>
        </li>
    </ul>
</section>
