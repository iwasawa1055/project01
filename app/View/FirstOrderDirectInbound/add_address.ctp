<?php echo $this->element('FirstOrderDirectInbound/first'); ?>
<meta name="robots" content="noindex,nofollow,noarchive">
<title>お届け先住所入力 - minikura</title>
<link href="/first_order_direct_inbound_file/css/first_order_direct_inbound/add_amazon_pay_dev.css" rel="stylesheet">
<?php echo $this->element('FirstOrderDirectInbound/header'); ?>
<?php echo $this->element('FirstOrderDirectInbound/nav'); ?>
<?php echo $this->element('FirstOrderDirectInbound/breadcrumb_list'); ?>

<div id="dsn-mybox page-top" class="dsn-select-signin">
  <!-- ADRESS -->
  <form method="post" action="/first_order_direct_inbound/confirm_address" novalidate>
    <section id="dsn-adress">
      <div class="dsn-wrapper">
        <div class="dsn-form">
          <label class="dsn-title">minikuraダイレクトとは？</label>
          <p>minikura専用キットを購入する事なく、お手持ちの段ボールやケースをそのままminikuraへ預けていただけるサービスです。
            <br>保管方法はminikuraHAKOと同じ、ボックス単位での保管となります。
            <br>ご自宅にある荷物を梱包して、ヤマト運輸へ集荷を依頼するかお客さま自身で着払いにてminikuraまでお送りください。
          </p>
          <p class="dsn-size">制限サイズ：120サイズ（3辺の合計が120cm以内）重さ15kgまで<br> 幅上限サイズ：59cm
            <br> 高さ上限サイズ：37cm
          </p>
          <label class="dsn-title">ご注意</label>
          <ul class="dsn-caution">
            <li>制限サイズより大きいサイズを預け入れした場合、ボックス料金が追加でかかりますので、ご注意ください。</li>
            <li>お送りいただいたボックスが保管が難しい場合は別途300円追加の上、弊社指定のボックスに入れ替え保管させていただきます。</li>
            <li>お送りいただいたボックスが取り出し時、配送に耐えられないと弊社が判断した場合は別途300円追加の上、弊社指定のボックスに入れ替え保管させていただきます。</li>
            <li>お預かり申し込みできないものを <a href="https://minikura.com/use_agreement/index.html#attachment1" target="_blank">minikura利用規約 <i class="fa fa-external-link-square"></i></a> でご確認いただき申し込みください。</li>
          </ul>
          <label id="scroll" data-section="scroll">預け入れ個数<span class="dsn-required">※</span></label>
          <select class="dsn-select-number" name="direct_inbound">
              <?php for ($i = 0; $i <= Configure::read('app.first_order.direct_inbound.max_box'); $i++):?>
              <option value="<?php echo $i;?>"<?php echo CakeSession::read('Order.direct_inbound.direct_inbound') == $i ? ' selected' : '' ;?>><?php echo h($i);?>箱</option>
              <?php endfor;?>
          </select>
          <br>
          <?php echo $this->Flash->render('direct_inbound');?>
        </div>

        <section id="dsn-signin-btns">
          <a class="dsn-btn dsn-btn-signin" href="#scroll" data-nav-section="scroll" data-scroll="50">minikuraで会員登録する <i class="fa fa-chevron-circle-right"></i></a>
          <?php if (!$customer->isEntry()): ?>
          <a class="dsn-btn dsn-btn-amazon" href="#scroll" data-nav-section="scroll" data-scroll="50">
            <a id="AmazonPayButtonDirect" class="dsn-btn-amazon">
            </a>
            <a id="Logout" >Logout</a>
          </a>
          <?php endif; ?>
        </section>

          <section id="dsn-signin">
            <input type="hidden" id="js-display_standard" value="<?php echo CakeSession::read('FirstOrderDirectInbound.display.standard');?>">
          <div class="dsn-divider"></div>

          <div class="dsn-form">
            <label>お名前<span class="dsn-required">※</span></label>
            <input type="text" name="lastname" class="dsn-name-last lastname focused" placeholder="寺田" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.lastname');?>">
            <input type="text" name="firstname" class="dsn-name-first firstname focused" placeholder="太郎" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.firstname');?>">
            <br>
            <?php echo $this->Flash->render('lastname'); ?>
            <?php echo $this->Flash->render('firstname'); ?>
          </div>
          <div class="dsn-form">
            <label>フリガナ<span class="dsn-required">※</span></label>
            <input type="text" name="lastname_kana" class="dsn-name-last-kana lastname_kana focused" placeholder="テラダ" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.lastname_kana');?>">
            <input type="text" name="firstname_kana" class="dsn-name-first-kana firstname_kana focused" placeholder="タロウ" size="10" maxlength="30" value="<?php echo CakeSession::read('Address.firstname_kana');?>">
            <br>
            <?php echo $this->Flash->render('lastname_kana'); ?>
            <?php echo $this->Flash->render('firstname_kana'); ?>
          </div>
          <div class="dsn-divider"></div>
          <div class="dsn-form">
            <label>郵便番号<span class="dsn-required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。<br>入力すると以下の住所が自動で入力されます。</span></label>
            <input type="tel" name="postal" id="postal" class="dsn-postal search_address_postal focused" placeholder="0123456" size="8" maxlength="8" value="<?php echo CakeSession::read('Address.postal');?>">
            <?php echo $this->Flash->render('postal');?>
          </div>
          <div class="dsn-form">
            <label>都道府県<span class="dsn-required">※</span></label>
            <input type="text" name="pref" class="dsn-adress1 address_pref focused" placeholder="東京都" size="28" maxlength="50" value="<?php echo CakeSession::read('Address.pref');?>">
            <?php echo $this->Flash->render('pref');?>
          </div>
          <div class="dsn-form">
            <label>住所<span class="dsn-required">※</span></label>
            <input type="text" name="address1" class="dsn-adress1 address_address1 focused" placeholder="品川区" size="28" maxlength="50" value="<?php echo CakeSession::read('Address.address1');?>">
            <?php echo $this->Flash->render('address1');?>
          </div>
          <div class="dsn-form">
            <label>番地<span class="dsn-required">※</span></label>
            <input type="text" name="address2" class="dsn-adress2 address_address2 focused" placeholder="東品川2-2-28" size="28" maxlength="50" value="<?php echo CakeSession::read('Address.address2');?>">
            <?php echo $this->Flash->render('address2');?>
          </div>
          <div class="dsn-form">
            <label>建物名</label>
            <input type="text" name="address3" class="dsn-build focused" placeholder="Tビル" size="28" maxlength="50" value="<?php echo CakeSession::read('Address.address3');?>">
            <?php echo $this->Flash->render('address3');?>
          </div>
          <div class="dsn-divider"></div>
          <div class="dsn-form">
            <label>電話番号<span class="dsn-required">※</span><br><span>全角半角、ハイフンありなし、どちらでもご入力いただけます。</span></label>
            <input type="tel" name="tel1" class="dsn-tel focused" placeholder="01234567890" size="15" maxlength="15" value="<?php echo CakeSession::read('Address.tel1');?>">
            <?php echo $this->Flash->render('tel1');?>
          </div>
          <div class="dsn-divider"></div>
          <div class="dsn-form">
            <label>預け入れ方法<span class="dsn-required">※</span></label>
            <label class="dsn-cargo-selected"><input type="radio" name="cargo" value="ヤマト運輸" id="yamato"  <?php if ( CakeSession::read('Address.cargo') === "ヤマト運輸" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="yamato" class="dsn-cargo-select"> ヤマト運輸に取りに来てもらう</label></label>
            <div class="dsn-yamato">
              <div class="dsn-form">
                <label>集荷希望日</label>
                <select name="date_cd" id="InboundDayCd" class="dsn-select-delivery focused dev-input-form">
                  <?php foreach ( CakeSession::read('Address.select_delivery_day_list') as $key => $value ) {?>
                  <option value="<?php echo $value->date_cd;?>"<?php if ( $value->date_cd === CakeSession::read('Address.date_cd') ) echo " selected";?>><?php echo $value->text;?></option>
                  <?php } ?>
                </select>
                <br>
                <?php echo $this->Flash->render('date_cd');?>
                <input type="hidden" name="select_delivery_day" id="select_delivery_day" value="<?php if (!empty(CakeSession::read('Address.select_delivery_day'))) : ?><?php echo h(CakeSession::read('Address.select_delivery_day'))?><?php else : ?><?php endif; ?>">
              </div>
              <div class="dsn-form">
                <label>集荷希望時間</label>
                <select name="time_cd" id="InboundTimeCd" class="dsn-select-delivery focused dev-input-form">
                  <?php foreach ( CakeSession::read('Address.select_delivery_time_list') as $key => $value ) {?>
                  <option value="<?php echo $value->time_cd;?>"<?php if ( $value->time_cd === CakeSession::read('Address.time_cd') ) echo " selected";?>><?php echo $value->text;?></option>
                  <?php } ?>
                </select>
                <br>
                <?php echo $this->Flash->render('time_cd');?>
                <input type="hidden" name="select_delivery_time" id="select_delivery_time" value="<?php if (!empty(CakeSession::read('Address.select_delivery_time'))) : ?><?php echo h(CakeSession::read('Address.select_delivery_time'))?><?php else : ?><?php endif; ?>">
              </div>
            </div>
            <label class="dsn-cargo-selected"><input type="radio" name="cargo" value="着払い" id="arrival" <?php if ( CakeSession::read('Address.cargo') === "着払い" ) echo " CHECKED";?>><span class="check-icon"></span> <label for="arrival" class="dsn-cargo-select"> 自分で送る（持ち込みで着払い）</label></label>
            <p class="dsn-arrival">着払いをご選択の場合はminikura運営事務局よりご連絡を差し上げます。<br> ※注意事項
              <br> ご連絡時のメールに記載する住所へ、ヤマト運輸の着払いでお送りください。
              <br> コンビニやヤマト営業所への持ち込みとなります。
            </p>
          </div>
          <section class="dsn-nextback">
            <a href="/first_order/add_order?back=true" class="dsn-btn-back"><i class="fa fa-chevron-circle-left"></i> 戻る</a>
            <button type="submit" class="dsn-btn-next">クレジットカード情報を入力 <i class="fa fa-chevron-circle-right"></i></button>
          </section>
        </section>
      </div>
    </section>
  </form>
</div>

<?php echo $this->element('FirstOrderDirectInbound/footer'); ?>
<?php echo $this->element('FirstOrderDirectInbound/js'); ?>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?libraries=places"></script>
<script src="/js/minikura/address.js"></script>
<script src="/js/jquery.airAutoKana.js"></script>
<script src="/first_order_direct_inbound_file/js/dsn-mybox.js"></script>
<script src="/first_order_direct_inbound_file/js/first_order_direct_inbound/add_address.js"></script>
<script src="/first_order_direct_inbound_file/js/first_order_direct_inbound/add_amazon_pay.js"></script>

<?php if (empty(CakeSession::read('FirstOrder.amazon_pay.access_token'))):?>
<script type='text/javascript' async='async' src="<?php echo Configure::read('app.amazon_pay.Widgets_url'); ?>"></script>
<?php endif; ?>

<?php echo $this->element('FirstOrderDirectInbound/last'); ?>
