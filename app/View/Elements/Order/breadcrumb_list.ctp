    <ul class="pagenation">
      <?php
        $order_disp['input'] = array(
            'Order/input_card',
            'Order/input_bank',
            'Order/input_amazon_pay',
        );
        $order_disp['confirm'] = array(
            'Order/confirm_card',
            'Order/confirm_bank',
            'Order/confirm_amazon_pay',
        );
        $order_disp['complete'] = array(
            'Order/complete_card',
            'Order/complete_bank',
            'Order/complete_amazon_pay',
        );
      ?>
      <li<?php if (in_array(CakeSession::read('app.data.session_referer'), $order_disp['input'], true)): ?> class="on"<?php endif; ?>><span class="number">1</span><span class="txt">プラン<br>選択</span>
      </li>
      <li<?php if (in_array(CakeSession::read('app.data.session_referer'), $order_disp['confirm'], true)): ?> class="on"<?php endif; ?>><span class="number">2</span><span class="txt">確認</span>
      </li>
      <li<?php if (in_array(CakeSession::read('app.data.session_referer'), $order_disp['complete'], true)): ?> class="on"<?php endif; ?>><span class="number">3</span><span class="txt">完了</span>
      </li>
    </ul>