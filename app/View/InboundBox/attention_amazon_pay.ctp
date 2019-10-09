<?php $this->Html->script('inbound_box/attention_amazon_pay', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script(Configure::read("app.gmo.token_url"), ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('libGmoCreditCardPayment', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('gmoCreditCardPayment', ['block' => 'scriptMinikura']); ?>

        <?php echo $this->Form->create('InboundBase', ['url' => '/inbound/box/attention_amazon_pay', 'name' => 'form', 'inputDefaults' => ['label' => false, 'div' => false], 'novalidate' => true]); ?>
        <div id="page-wrapper" class="wrapper inbound">
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
                      <td class="td">上限<?php echo EXCESS_ATTENTION_PRODUCT_CD[PRODUCT_CD_CLOSET]; ?>点。<br class="sp">上限超過時＋91円(税抜)/1アイテム</td>
                    </tr>
                    <tr>
                      <th class="th">minikura<br class="sp">クリーニングパック</th>
                      <td class="td">上限<?php echo EXCESS_ATTENTION_PRODUCT_CD[PRODUCT_CD_CLEANING_PACK]; ?>点。<br class="sp">上限超過時＋850円(税抜)/1アイテム</td>
                    </tr>
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
            <ul class="input-info">
                <li class="caution-box">
                    <p class="title">注意事項（ご確認の上、チェックしてください）</p>
                    <div class="content">
                        <label class="input-check">
                            <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">倉庫でお預かり品を計測した際、点数に齟齬があった場合は、点数と料金表に基づいて実際の料金をご連絡させていただきます。</span>
                        </label>
                        <?php if($box_use_flag[PRODUCT_CD_CLEANING_PACK]) :?>
                          <label class="input-check">
                            <input type="checkbox" class="cb-square"><span class="icon"></span><span class="label-txt">クリーニングパックを6ヶ月を超えて保管をする場合、1パックにつき、月額500円(税抜)で保管ができます。</span>
                          </label>
                        <?php endif; ?>
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
                <li><a class="btn-red" id="execute">この内容で預ける</a>
                </li>
            </ul>
        </div>
        <?php echo $this->Form->end(); ?>
        <input type="hidden" value="<?php if (!empty($card_data)): ?>1<?php else: ?>0<?php endif; ?>" id="is_update">
