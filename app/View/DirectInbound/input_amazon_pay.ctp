<?php $this->Html->script('direct_inbound/dsn-boxless.js', ['block' => 'scriptMinikura']); ?>
<?php $this->Html->script('direct_inbound/input_amazon_pay.js', ['block' => 'scriptMinikura']); ?>

<!-- 暫定的にFirstOrderのcssを読み込み -->
<?php $this->Html->css('/css/app.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/direct_inbound/dsn-boxless.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/direct_inbound/direct_inbound_dev.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/dsn-amazon-pay.css', ['block' => 'css']); ?>
<?php $this->Html->css('/css/direct_inbound/input_amazon_pay_dev.css', ['block' => 'css']); ?>

<?php $this->validationErrors['OrderKit'] = $validErrors; ?>

    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header"><i class="fa fa-arrow-circle-o-up"></i>預け入れ</h1>
      </div>
    </div>
    <div class="row">
      <form method="post" class="select-add-address-form" action="/direct_inbound/confirm_amazon_pay" novalidate>
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <h2>minikuraダイレクト</h2>
              <div class="form-group col-lg-12">
                <label>minikuraダイレクトとは？</label>
                <p>minikura専用キットを購入する事なく、お手持ちの段ボールやケースをそのままminikuraへ預けていただけるサービスです。
                  <br>保管方法はminikuraHAKOと同じ、ボックス単位での保管となります。
                  <br>ご自宅にある荷物を梱包して、ヤマト運輸へ集荷を依頼するかお客さま自身で着払いにてminikuraまでお送りください。
                </p>
                <p>制限サイズ：120サイズ（3辺の合計が120cm以内）重さ15kgまで
                  <br> 幅上限サイズ：59cm
                  <br> 高さ上限サイズ：37cm
                </p>
                <label>ご注意</label>
                <ul class="dsn-caution">
                  <li>制限サイズより大きいサイズを預け入れした場合、ボックス料金が追加でかかりますので、ご注意ください。</li>
                  <li>お送りいただいたボックスが保管が難しい場合は別途300円追加の上、弊社指定のボックスに入れ替え保管させていただきます。</li>
                  <li>お送りいただいたボックスが取り出し時、配送に耐えられないと弊社が判断した場合は別途300円追加の上、弊社指定のボックスに入れ替え保管させていただきます。</li>
                  <li>お預かり申し込みできないものを <a href="https://minikura.com/use_agreement/index.html#attachment1" target="_blank">minikrua利用規約 <i class="fa fa-external-link-square"></i></a> でご確認いただき申し込みください。</li>
                </ul>
              </div>
              <div class="form-group col-lg-12">
                <h3>預け入れ個数</h3>
                <select class="form-controlr dev-input-form" name="direct_inbound">
                  <?php for ($i = 0; $i <= Configure::read('app.first_order.direct_inbound.max_box'); $i++):?>
                  <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.direct_inbound.direct_inbound') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
                  <?php endfor;?>
                </select>
                <br>
                <?php echo $this->Flash->render('direct_inbound');?>
              </div>

              <div class="form-group col-lg-12">
                <h3>預け入れ方法</h3>
                <p class="dsn-select-cargo"><label><input type="radio" name="cargo" value="ヤマト運輸" id="yamato"  <?php if ( CakeSession::read('OrderKit.cargo') === "ヤマト運輸" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="yamato" class="dsn-cargo-select"> ヤマト運輸に取りに来てもらう</label>
                </p>
                <div class="dsn-yamato">

                  <div id="dsn-amazon-pay" class="form-group col-lg-12">
                    <div class="dsn-address">
                      <div id="addressBookWidgetDiv">
                      </div>
                    </div>
                    <div class="dsn-credit">
                      <div id="walletWidgetDiv">
                      </div>
                    </div>
                  </div>

                  <div class="dsn-form">
                    <div class="dsn-form">
                      <?php // アマゾンから取得した情報をバリデーション ?>
                      <?php echo $this->Flash->render('customer_amazon_pay_info');?>
                      <?php echo $this->Flash->render('postal');?>
                      <?php echo $this->Flash->render('pref');?>
                      <?php echo $this->Flash->render('address1');?>
                      <?php echo $this->Flash->render('address2');?>
                      <?php echo $this->Flash->render('tel1');?>
                    </div>
                  </div>

                  <div class="form-group col-lg-12 inbound_pickup_only">
                    <label>お名前</label>
                    <div class="dsn-form">
                      <input type="text" name="lastname" class="dsn-name-last lastname focused" placeholder="寺田" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.lastname');?>">
                      <input type="text" name="firstname" class="dsn-name-first firstname focused" placeholder="太郎" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.firstname');?>">
                      <br>
                      <?php echo $this->Flash->render('firstname');?>
                      <?php echo $this->Flash->render('lastname');?>
                    </div>
                  </div>

                  <div class="form-group col-lg-12">
                    <label>集荷の日程</label>
                      <select name="date_cd" id="InboundDayCd" class="form-controlr dev-input-form">
                        <?php foreach ( CakeSession::read('SelectTime.select_delivery_day_list') as $key => $value ) {?>
                        <option value="<?php echo $value->date_cd;?>"<?php if ( $value->date_cd === CakeSession::read('SelectTime.date_cd') ) echo " selected";?>><?php echo $value->text;?></option>
                        <?php } ?>
                      </select>
                    <?php echo $this->Flash->render('date_cd');?>
                    <input type="hidden" name="select_delivery_day" id="select_delivery_day" value="<?php if (!empty(CakeSession::read('SelectTime.select_delivery_day'))) : ?><?php echo h(CakeSession::read('SelectTime.select_delivery_day'))?><?php else : ?><?php endif; ?>">
                  </div>
                  <div class="form-group col-lg-12">
                    <label>集荷の時間</label>
                    <select name="time_cd" id="InboundTimeCd" class="form-controlr dev-input-form">
                      <?php foreach ( CakeSession::read('SelectTime.select_delivery_time_list') as $key => $value ) {?>
                      <option value="<?php echo $value->time_cd;?>"<?php if ( $value->time_cd === CakeSession::read('SelectTime.time_cd') ) echo " selected";?>><?php echo $value->text;?></option>
                      <?php } ?>
                    </select>
                    <?php echo $this->Flash->render('time_cd');?>
                    <input type="hidden" name="select_delivery_time" id="select_delivery_time" value="<?php if (!empty(CakeSession::read('SelectTime.select_delivery_time'))) : ?><?php echo h(CakeSession::read('SelectTime.select_delivery_time'))?><?php else : ?><?php endif; ?>">
                  </div>
                </div>
                <p class="dsn-select-cargo"><label><input type="radio" name="cargo" value="着払い" id="arrival" <?php if ( CakeSession::read('OrderKit.cargo') === "着払い" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="arrival" class="dsn-cargo-select"> 自分で送る（持ち込みで着払い）</label>
                </p>
                <p class="dsn-arrival">自分で送るをご選択の場合はminikura運営事務局よりご連絡を差し上げます。<br> ※注意事項
                  <br> ご連絡時のメールに記載する住所へ、ヤマト運輸の着払いでお送りください。
                  <br> コンビニやヤマト営業所への持ち込みとなります。</p>
              </div>
              <span class="col-lg-12 col-md-12 col-xs-12">
                <section class="nextback">
                  <button class="btn-next page-transition-link js-btn-submit" type="submit">
                    次へ
                    <i class="fa fa-chevron-circle-right"></i>
                  </button>
                </section>
              </span>
            </div>
          </div>
      </div>
    </div>
