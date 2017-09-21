<?php $this->Html->script('order/input', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('https://maps.google.com/maps/api/js?libraries=places', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('minikura/address', ['block' => 'scriptMinikura']); ?>

<?php $this->Html->css('/css/order/dsn-purchase.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/order/order_dev.css', ['block' => 'css']); ?>
<?php $this->validationErrors['OrderKit'] = $validErrors; ?>

    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-shopping-cart"></i> ボックス購入</h1>
      </div>
    </div>
    <div class="row">
      <form method="post" class="select-add-address-form" action="/order/confirm_sneaker" novalidate>
        <div class="col-lg-12">
          <div class="panel panel-default">
            <?php echo $this->element('Order/breadcrumb_list'); ?>

            <!-- LINEUP -->
            <section id="dsn-lineup" class="fix">
              <div class="dsn-wrapper dev-wrapper">
                <!-- sneakers -->
                <div class="dsn-lineup-box">
                  <h3>minikura sneakers</h3>
                  <p class="dsn-price">月額保管料<span>800円</span>
                  </p>
                  <p class="dsn-price">ボックス代金<span>800円</span>
                  </p>
                  <p class="dsn-box-caption">「NIKE MY SNKRS」専用の スニーカー保管サービス。あなたのスニーカーを大切に保管します。
                  </p>
                  <p class="dsn-select-number" id="select_sneaker"><?php if (CakeSession::read('Order.sneaker.sneaker') > 0) : ?><span><?php echo h(CakeSession::read('Order.sneaker.sneaker')) ?>個選択済み</span><?php else : ?>未選択<?php endif; ?></p>
                  <div class="dsn-box-sneaker"><img src="/images/box_sneaker@1x.png" srcset="/images/box_sneaker@1x.png 1x, /images/box_sneaker@2x.png 2x" alt="minikura sneakers"> </div>
                  <a href="#" class="dsn-btn-select" data-remodal-target="modal-sneaker"><i class="fa fa-chevron-circle-down"></i> 個数を選ぶ</a>
                  <div class="dsn-form">
                    <?php echo $this->Flash->render('select_oreder_sneaker'); ?>
                  </div>
                </div>
              </div>
            </section>
            <section id="dsn-delivery">
              <div class="dsn-wrapper">
                <table class="accounting">
                  <tr>
                    <th>合計点数</th>
                    <td id="js-item-total">0点</td>
                  </tr>
                </table>
                <div class="dsn-divider"></div>
                <?php if (CakeSession::read('OrderKit.is_credit')) { ?>
                  <h4 class="dev-after-link">クレジットカード情報の入力</h4><a href="https://minikura.com/privacy_case/" target="_blank" class="link-terms"><i class="fa fa-chevron-circle-right"></i> クレジットカード情報の取り扱いについて</a>
                  <div class="dsn-form">
                    <?php echo $this->Flash->render('customer_kit_card_info');?>
                  </div>
                  <div class="dsn-form">
                    <?php if (!is_null(CakeSession::read('OrderKit.card_data'))) { ?>
                      <div class="dsn-form">
                        <?php echo $this->Flash->render('card_no');?>
                      </div>
                      <label class="dsn-select-cards"><input type="radio" name="select-card" id="as-card"     value="default"  <?php if((string)CakeSession::read('OrderKit.select_card') === "default") { ?> checked <?php }?>><span class="dsn-check-icon"></span> <label for="as-card" class="dsn-select-card"><?php echo h(CakeSession::read('OrderKit.card_data.card_no')) ?></label></label>
                      <label class="dsn-select-cards"><input type="radio" name="select-card" id="change-card" value="register" <?php if((string)CakeSession::read('OrderKit.select_card') === "register") { ?> checked <?php }?>><span class="dsn-check-icon"></span> <label for="change-card" class="dsn-select-card">登録したカードを変更する</label></label>
                    <?php } ?>
                  </div>
                  <div class="dsn-input-security-code">
                    <div class="dsn-divider"></div>
                    <h4>セキュリティコードをご入力ください。</h4>
                    <div class="dsn-form">
                      <label>セキュリティコード<sup><span class="required">※</span></sup></label>
                      <input class="dsn-security-code" name="security_cd" placeholder="0123" size="6" maxlength="6">
                      <div class="dsn-form">
                        <?php echo $this->Flash->render('security_cd');?>
                      </div>
                    </div>
                  </div>
                  <div class="dsn-input-change-card">
                    <div class="dsn-divider"></div>
                    <h4>利用するカード情報をご入力ください。</h4>
                    <div class="dsn-form">
                      <label>クレジットカード番号<sup><span class="required">※</span></sup><br><span class="required">全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
                      <input type="tel" class="name focused" name="card_no" placeholder="0000-0000-0000-0000" size="20" maxlength="20" value="<?php echo CakeSession::read('Credit.card_no');?>">
                      <div class="dsn-form">
                        <?php echo $this->Flash->render('new_card_no');?>
                      </div>
                    </div>
                    <div class="dsn-form">
                      <label>セキュリティコード<sup><span class="required">※</span></sup></label>
                      <input type="tel" class="dsn-security-code focused" name="new_security_cd" placeholder="0123" size="6" maxlength="6" value="">
                      <?php echo $this->Flash->render('new_security_cd');?>
                    </div>
                    <div class="dsn-form">
                      <label>カード有効期限<sup><span class="required">※</span></sup></label>
                      <select class="dsn-select-month focused" name="expire_month">
                        <?php foreach ( $this->Html->creditcardExpireMonth() as $value => $string ) :?>
                        <option value="<?php echo $value;?>"<?php if ( $value === substr(CakeSession::read('Credit.expire'),0,2) ) echo " SELECTED";?>><?php echo $string;?></option>
                        <?php endforeach ?>
                      </select>
                      /
                      <select class="dsn-select-year focused" name="expire_year">
                        <?php foreach ( $this->Html->creditcardExpireYear() as $value => $string ) :?>
                        <option value="<?php echo $value;?>"<?php if ( (string) $value === substr(CakeSession::read('Credit.expire'),2,2) ) echo " SELECTED";?>><?php echo $string;?></option>
                        <?php endforeach ?>
                      </select>
                      <br>
                      <?php echo $this->Flash->render('expire');?>
                    </div>
                    <div class="dsn-form">
                      <label>カード名義<sup><span class="required">※</span></sup></label>
                      <input type="url" class="dsn-name holder_name focused" name="holder_name" placeholder="TERRADA MINIKURA" size="28" maxlength="30" value="<?php echo CakeSession::read('Credit.holder_name');?>" novalidate>
                      <?php echo $this->Flash->render('holder_name');?>
                    </div>
                  </div>
                <div class="dsn-divider"></div>
                <?php } ?>
                <div class="dsn-form">
                  <label>お届け先</label>
                      <select name="address_id" id="address_id" class="dsn-adress select-delivery focused">
                        <!--<option value="">以下からお選びください</option>-->
                        <?php foreach ( CakeSession::read('OrderKit.address_list') as $key => $value ) {?>
                        <option value="<?php echo $key;?>"<?php if ( $key === (int)CakeSession::read('OrderKit.address_id') ) echo " selected";?>><?php echo $value;?></option>
                        <?php } ?>
                      </select>
                  <?php echo $this->Flash->render('format_address');?>
                  <?php echo $this->Flash->render('customer_address_info');?>
                </div>
                <div class="dsn-input-new-adress">
                  <div class="dsn-form">
                    <label>お名前<sup><span class="required">※</span></sup></label>
                    <input type="text" name="lastname" class="dsn-name-last lastname focused" placeholder="寺田" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.lastname');?>">
                    <input type="text" name="firstname" class="dsn-name-first firstname focused" placeholder="太郎" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.firstname');?>">
                    <br>
                    <?php echo $this->Flash->render('lastname'); ?>
                    <?php echo $this->Flash->render('firstname'); ?>
                    <input type="hidden" name="lastname_kana" value="<?php echo CakeSession::read('Address.lastname_kana');?>">
                    <input type="hidden" name="firstname_kana" value="<?php echo CakeSession::read('Address.firstname_kana');?>">
                  </div>
                  <div class="dsn-divider"></div>
                  <div class="dsn-form">
                    <label>郵便番号<sup><span class="required">※</span></sup><br><span class="required">ハイフンありなし、どちらでもご入力いただけます。<br>入力すると以下の住所が自動で入力されます。</span></label>
                    <input type="tel" name="postal" id="postal" class="dsn-postal search_address_postal focused" placeholder="0123456" size="8" maxlength="8" value="<?php echo CakeSession::read('Address.postal');?>">
                    <?php echo $this->Flash->render('postal');?>
                  </div>
                  <div class="dsn-form">
                    <label>都道府県<span class="required">※</span></label>
                    <input type="text" name="pref" class="dsn-adress address_pref focused" placeholder="東京都" size="28" maxlength="50" value="<?php echo CakeSession::read('Address.pref');?>">
                    <?php echo $this->Flash->render('pref');?>
                  </div>
                  <div class="dsn-form">
                    <label>住所<span class="required">※</span></label>
                    <input type="text" name="address1" class="dsn-adress address_address1 focused" placeholder="品川区" size="28" maxlength="50" value="<?php echo CakeSession::read('Address.address1');?>">
                    <?php echo $this->Flash->render('address1');?>
                  </div>
                  <div class="dsn-form">
                    <label>番地<span class="required">※</span></label>
                    <input type="text" name="address2" class="dsn-adress address_address2 focused" placeholder="東品川2-2-28" size="28" maxlength="50" value="<?php echo CakeSession::read('Address.address2');?>">
                    <?php echo $this->Flash->render('address2');?>
                  </div>
                  <div class="dsn-form">
                    <label>建物名</label>
                    <input type="text" name="address3" class="dsn-adress focused" placeholder="Tビル" size="28" maxlength="50" value="<?php echo CakeSession::read('Address.address3');?>">
                    <?php echo $this->Flash->render('address3');?>
                  </div>
                  <div class="dsn-divider"></div>
                  <div class="dsn-form">
                    <label>電話番号<span class="required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
                    <input type="tel" name="tel1" class="dsn-tel focused" placeholder="01234567890" size="15" maxlength="15" value="<?php echo CakeSession::read('Address.tel1');?>">
                    <?php echo $this->Flash->render('tel1');?>
                  </div>
                  <div class="dsn-divider"></div>
                  <div class="dsn-form">
                    <label class="dsn-regist-adress">
                      <input type="checkbox" class="focused" id="regist-adress" name="insert-adress-list" <?php if (CakeSession::read('OrderKit.insert_address_list'))  { ?>checked <?php } ?>>
                      <span class="dsn-check-icon"></span> <label for="regist-adress" class="dsn-regist-adress">入力した住所をお届け先リストに登録する</label></label>
                  </div>
                </div>
                <div class="dsn-divider"></div>
                <div class="dsn-form">
                  <label>お届け希望日時</label>
                  <select name="datetime_cd" id="datetime_cd" class="dsn-delivery focused">
                    <?php foreach ( CakeSession::read('Address.select_delivery_list') as $key => $value ) {?>
                      <option value="<?php echo $value->datetime_cd;?>"<?php if ( $value->datetime_cd === CakeSession::read('Address.datetime_cd') ) echo " selected";?>><?php echo $value->text;?></option>
                    <?php } ?>
                  </select>
                  <div class="dsn-form">
                    <?php echo $this->Flash->render('datetime_cd');?>
                  </div>
                  <input type="hidden" name="select_delivery" id="select_delivery" value="<?php if (!empty(CakeSession::read('Address.select_delivery'))) : ?><?php echo h(CakeSession::read('Address.select_delivery'))?><?php else : ?><?php endif; ?>">
                </div>
              </div>
            </section>
          </div>
        </div>
        <section class="dsn-nextback fix dev-forefront">
          <button class="dsn-btn-next-full" type="submit" formnovalidate>確認へ <i class="fa fa-chevron-circle-right"></i></button>
        </section>
        </section>

        <input type="hidden" name="sneaker"      value="<?php echo h(CakeSession::read('Order.sneaker.sneaker')); ?>" />
      </form>

      <!--sneaker modal-->
      <div class="remodal dsn-items" data-remodal-id="modal-sneaker" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc" data-remodal-options="hashTracking:false">
        <div class="dsn-box">
          <div class="dsn-pict-box"><img src="/first_order_file/images/box_sneaker@1x.png" srcset="/first_order_file/images/box_sneaker@1x.png 1x, /first_order_file/images/box_sneaker@2x.png 2x" alt="minikura sneakers">
          </div>
          <div class="dsn-select-box">
            <h3>minikura sneakers</h3>
            <select class="dsn-item-number js-item-number js-item-sneaker" data-name="sneaker" data-box_type="sneaker">
              <?php for ($i = 0; $i <= Configure::read('app.first_order.max_box'); $i++):?>
              <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.sneaker.sneaker') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
              <?php endfor;?>
            </select>
          </div>
          <p class="dsn-size">W62cm×H44cm×D44cm</p>
          <p class="dsn-caption">二重構造の頑丈な段ボール「ダブルカートンボックス」を採用。<br>
            1ボックスにつき8足までお預かりできます。</p>
        </div>
        <a class="dsn-btn-return" data-remodal-action="close" class="" aria-label="Close"><i class="fa fa-chevron-circle-left"></i> 閉じる</a>
      </div>
    </div>
