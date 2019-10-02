<?php $this->Html->script('inbound_box/attention', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script(Configure::read("app.gmo.token_url"), ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('libGmoCreditCardPayment', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('gmoCreditCardPayment', ['block' => 'scriptMinikura']); ?>

        <?php echo $this->Form->create('InboundBase', ['url' => '/inbound/box/attention', 'name' => 'form', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
        <div id="page-wrapper" class="wrapper inbound">
            <?php $error_item_excess = $this->Flash->render('item_excess');?>
            <?php if (!empty($error_item_excess)) :?>
            <br>
            <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i><?php echo $error_item_excess;?></div>
            <?php endif;?>
            <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i> ボックス預け入れ</h1>
            <ul class="pagenation">
                <li><span class="number">1</span><span class="txt">ボックス<br>選択</span>
                </li>
                <li class="on"><span class="number">2</span><span class="txt">個数入力</span>
                </li>
                <li><span class="number">3</span><span class="txt">確認</span>
                </li>
                <li><span class="number">4</span><span class="txt">完了</span>
                </li>
            </ul>
            <div class="l-desc-num-input">
                <h2 class="ttl-desc-num-input">預け入れ点数をお選びください</h2>
                <p class="txt-desc-num-input">下記表に記載のサービスは預け入れの上限点数まで無料でお預かりができ、それを超えると以下の料金でご利用いただけます。（毎月の保管料は変わりません）</p>
                <table class="tbl-desc-num-input">
                    <tbody>
                    <tr>
                        <th class="th">minikura Closet</th>
                        <td class="td">上限10点。<br class="sp">11点からXXX円</td>
                    </tr>
                    <tr>
                        <th class="th">minikura<br class="sp">クリーニングパック</th>
                        <td class="td"> 上限10点。<br class="sp">11点から900円</td>
                    </tr>
                    <?php if (false): ?>
                    <?php // TODO ギフトリリースまで非表示?>
                    <tr>
                        <th class="th">minikuraギフト <br class="sp">クリーニングパック5</th>
                        <td class="td">上限5点。<br class="sp">6点から900円</td>
                    </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div id="gift" class="item-content">
                <ul class="grid grid-md">
                    <?php foreach ($target_box_list as $box_data): ?>
                    <li>
                        <label>
                            <span class="item-img">
                                <img src="<?php echo KIT_IMAGE[$box_data['kit_cd']] ?>" alt="<?php echo $box_data['kit_name']; ?>" class="img-item">
                            </span>
                        </label>
                        <div class="box-info">
                            <p class="l-box-id">
                                <span class="txt-box-id"><?php echo $box_data['box_id']; ?></span>
                            </p>
                            <p class="box-type"><?php echo $box_data['kit_name']; ?></p>
                            <p class="box-name"><?php echo $box_data['title']; ?></p>
                        </div>
                        <?php if($box_data['product_cd'] === PRODUCT_CD_GIFT_CLEANING_PACK): ?>
                        <div class="l-slct-num">
                            <p class="ttl-slct-num">預け入れ点数</p>
                            <ul class="list-slct-num">
                                <li>
                                    <label class="input-check">
                                        <input type="radio" class="rb" name="data[InboundBase][item_excess_list][<?php echo $box_data['box_id']; ?>]" value="0" <?php echo !$box_data['excess_flag'] ? 'checked' : ''; ?>><span class="icon"></span>
                                        <span class="label-txt">5点以内</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="input-check">
                                        <input type="radio" class="rb" name="data[InboundBase][item_excess_list][<?php echo $box_data['box_id']; ?>]" value="1" <?php echo $box_data['excess_flag'] ? 'checked' : ''; ?>><span class="icon"></span>
                                        <span class="label-txt">5点超</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <?php endif;?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php if(!empty($other_box_list)): ?>
            <h3 class="ttl-box-others">選択中のその他ボックス</h3>
            <div id="other" class="item-content">
                <ul class="grid grid-md">
                    <?php foreach ($other_box_list as $box_data): ?>
                    <li>
                        <label>
                            <span class="item-img">
                                <img src="<?php echo KIT_IMAGE[$box_data['kit_cd']] ?>" alt="<?php echo $box_data['kit_name']; ?>" class="img-item">
                            </span>
                        </label>
                        <div class="box-info">
                            <p class="l-box-id">
                                <span class="txt-box-id"><?php echo $box_data['box_id']; ?></span>
                            </p>
                            <p class="box-type"><?php echo $box_data['kit_name']; ?></p>
                            <p class="box-name"><?php echo $box_data['title']; ?></p>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif;?>
            <?php if($card_flag): ?>
            <section class="l-select-payment">
                <div class="input-card">
                    <ul class="input-check-list">
                        <h4>クレジットカード情報の入力</h4>
                        <li>
                            <label class="input-check">
                                <?php
                                    echo $this->Form->input(
                                        'PaymentGMOKitByCreditCard.select-card',
                                        [
                                            'id'    => '',
                                            'class' => 'cb-square card_check_type',
                                            'label' => false,
                                            'error' => false,
                                            'options' => [
                                                'as-card' => '<span class="icon"></span><span class="label-txt">登録済みのカードを使用する</span>' . '[' . '<label for="as-card" class="dsn-select-card">' . $card_data['card_no'] . '</label>' . ']'
                                            ],
                                            'type' => 'radio',
                                            'div' => false,
                                            'hiddenField' => false,
                                            'checked' => 'checked'
                                        ]
                                    );
                                ?>
                            </label>
                        </li>
                        <li>
                            <label class="input-check">
                                <?php
                                  echo $this->Form->input(
                                    'PaymentGMOKitByCreditCard.select-card',
                                    [
                                        'id'    => '',
                                        'class' => 'cb-square card_check_type',
                                        'label' => false,
                                        'error' => false,
                                        'options' => [
                                            'change-card' => '<span class="icon"></span><span class="label-txt">登録したカードを変更する</span>'
                                        ],
                                        'type' => 'radio',
                                        'div' => false,
                                        'hiddenField' => false
                                    ]
                                    );
                                ?>
                            </label>
                        </li>
                    </ul>
                </div>
                <div id="gmo_validate_error"></div>
                <div id="gmo_credit_card_info"></div>
                <div class="dsn-form card_error">
                    <?php echo $this->Flash->render('customer_kit_card_info');?>
                </div>
                <?php echo $this->Form->error('PaymentGMOKitByCreditCard.card_no', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                <div id="input-exist" class="input-card">
                    <h4>登録済みのカードを使用する</h4>
                    <p class="page-caption">セキュリティコードをご入力ください。</p>
                    <ul class="input-info add-credit">
                        <li>
                            <label class="headline">セキュリティコード<span class="required">※</span></label>
                            <?php echo $this->Form->input('PaymentGMOKitByCreditCard.security_cd', ['id' => 'security_cd', 'class' => "cb-square", 'placeholder'=>'例：0123', 'size' => '6', 'maxlength' => '6', 'autocomplete' => "cc-csc", 'error' => false, 'label' => false, 'div' => false]); ?>
                            <p class="txt-caption">カード裏面に記載された3〜4桁の番号をご入力ください。</p>
                            <?php echo $this->Form->error('PaymentGMOKitByCreditCard.security_cd', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                    </ul>
                </div>
                <div id="input-change" class="input-card">
                    <h4>登録したカードを変更する</h4>
                    <p class="page-caption">利用するカード情報をご入力ください。</p>
                    <?php echo $this->element('Order/add-credit'); ?>
                    <a class="btn-red dsn-btn-credit execute" href="javascript:void(0)">このカードに変更する</a>
                </div>
                <div id="input-new" class="input-card">
                    <h4>カードを新規登録する</h4>
                    <p class="page-caption">利用するカード情報をご入力ください。</p>
                    <?php echo $this->element('Order/add-credit-new'); ?>
                    <a class="btn-red dsn-btn-credit execute" href="javascript:void(0)">このカードを登録する</a>
                </div>
            </section>
            <?php endif;?>
            <ul class="input-info">
                <li class="caution-box">
                    <p class="title">注意事項（ご確認の上、チェックしてください）</p>
                    <div class="content">
                        <label class="input-check">
                            <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">倉庫でお預かり品を計測した際、点数に齟齬があった場合は、点数と料金表に基づいて実際の料金をご連絡させていただきます。</span>
                        </label>
                        <label class="input-check">
                            <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">6ヶ月を超えて保管をする場合、1パックにつき、月額500円で保管ができます。</span>
                        </label>
                        <label class="input-check">
                            <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">衣類の洗濯タグが全て不可になっているものはクリーニングできません。また、高級衣類についてはクリーニング不可または別途見積もりになります。<a class="link-charge" href="https://minikura.com/help/special_cleaning_charge.html" target="_blank">一覧はこちら</a></span>
                        </label>
                    </div>
                </li>
            </ul>
        </div>
        <div class="nav-fixed">
            <ul>
                <li>
                  <a class="btn-d-gray" href="/inbound/box/add">戻る</a>
                </li>
                <li><a class="btn-red" id="execute">ボックスの確認</a>
                </li>
            </ul>
        </div>
        <?php echo $this->Form->end(); ?>
        <input type="hidden" value="<?php if (!empty($card_data)): ?>1<?php else: ?>0<?php endif; ?>" id="is_update">
