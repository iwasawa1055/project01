<?php
$this->Html->script(Configure::read("app.gmo.token_url"), ['block' => 'scriptMinikura']);
$this->Html->script('libGmoCreditCardPayment', ['block' => 'scriptMinikura']);
$this->Html->script('customer/gvido/card', ['block' => 'scriptMinikura']);
?>

    <section class="registry">
        <div class="container">
            <form method="post" action="/customer/gvido/card">
                <h2>クレジットカード情報</h2>
                <ul class="input-form">
                    <div id="gmo_credit_card_info"></div>
                    <div id="gmo_validate_error"></div>
                    <?php if (isset($error_message)) : ?>
                    <p class="valid-il"><?php echo h($error_message); ?></p>
                    <?php endif; ?>
                    <li>
                        <label>クレジットカード番号<span class="required">必須</span></label>
                        <input type="tel" id="cardno" class="form-control" name="cardno" placeholder="例：0000-0000-0000-0000" size="20" maxlength="20" value="">
                        <p class="description">全角半角、ハイフンありなし、どちらでもご入力いただけます。</p>
                    </li>
                    <li class="postal">
                        <label>セキュリティコード<span class="required">※</span></label>
                        <input type="tel" id="securitycode" class="form-control" name="securitycode" placeholder="例：0123" size="6" maxlength="6" value="">
                        <p class="description">全角半角、ハイフンありなし、どちらでもご入力いただけます。</p>
                    </li>
                    <li>
                        <label>カード有効期限<span class="required">必須</span></label>
                        <ul class="col-name">
                            <li>
                                <select "focused" id="expiremonth" name="expiremonth">
                                <?php foreach ($this->Html->creditcardExpireMonth() as $value => $string) :?>
                                    <option value="<?php echo $value;?>"><?php echo $string;?></option>
                                <?php endforeach ?>
                                </select>
                            </li>
                            <li>
                                <select "focused" id="expireyear" name="expireyear">
                                <?php foreach ($this->Html->creditcardExpireYear() as $value => $string) :?>
                                    <option value="<?php echo $value;?>"><?php echo $string;?></option>
                                <?php endforeach ?>
                                </select>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <label>カード名義<span class="required">必須</span></label>
                        <input type="url" id="holdername" class="form-control" name="holdername" placeholder="TERRADA MINIKURA" size="28" maxlength="30" value="" novalidate>
                    </li>
                </ul>
                <input type="hidden" id="shop_id" value="<?php echo Configure::read('app.gmo.shop_id'); ?>">
            </form>
            <ul class="nav-block-2">
                <li><a class="btn-d-gray" href="/customer/gvido/confirm">戻る</a></li>
                <li><button class="btn-d-red" id="execute">完了</button></li>
            </ul>
        </div>
    </section>
