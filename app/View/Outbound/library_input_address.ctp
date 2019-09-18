<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="keywords" content="minikura,あずける,トラクルーム,収納スペース">
    <meta name="description" content="箱であずかる収納サービス minikura。宅配便とWebでカンタン、詰めて送るだけ。クラウド収納でお部屋はもっと広くなる！">
    <meta property="og:locale" content="ja_JP" />
    <meta property="og:site_name"  content="minikura" />
    <meta property="og:title" content="minikura" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo Configure::read('site.static_content_url');?>" />
    <meta property="og:description" content="箱であずかる収納サービス minikura。宅配便とWebでカンタン、詰めて送るだけ。クラウド収納でお部屋はもっと広くなる！" />
    <meta name="twitter:card" content="summary" />
    <meta name="format-detection" content="telephone=no">
    <title><?php $this->Title->p(); ?></title>
<?php
  echo $this->fetch('meta');

  $this->Html->css('bootstrap.min', ['inline' => false]);
  $this->Html->css('font-awesome.min', ['inline' => false]);
  $this->Html->css('metisMenu.min', ['inline' => false]);
  $this->Html->css('animsition', ['inline' => false]);
  $this->Html->css('remodal', ['inline' => false]);
  $this->Html->css('remodal-theme', ['inline' => false]);
  $this->Html->css('app', ['inline' => false]);
  $this->Html->css('app_dev', ['inline' => false]);
  $this->Html->css('style', ['inline' => false]);

  echo $this->fetch('css');
?>

<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
<link rel="icon" type="images/png" href="/images/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="images/png" href="/images/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="/images/manifest.json">
<link rel="mask-icon" href="/images/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ff0000">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-K4MN3W');</script>
<!-- End Google Tag Manager -->
</head>

<body id="">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K4MN3W"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <div class='airloader-overlay'>
        <div class="loader">Loading...</div>
    </div>

    <div id="wrapper">
        <!--nav-->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?php echo $this->element('default_header_logo'); ?>
            </div>
            <?php echo $this->element('navbar_right'); ?>
            <?php echo $this->element('sidebar'); ?>
        </nav>
        <form method="POST" action="/outbound/library_input_address" name="form">
        <div id="page-wrapper" class="wrapper library">
            <h1 class="page-header"><i class="fa fa-arrow-circle-o-down"></i> minikura Library</h1>
            <ul class="pagenation">
                <li><span class="number">1</span><span class="txt">アイテム<br>選択</span>
                </li>
                <li class="on"><span class="number">2</span><span class="txt">配送情報<br>入力</span>
                </li>
                <li><span class="number">3</span><span class="txt">確認</span>
                </li>
                <li><span class="number">4</span><span class="txt">完了</span>
                </li>
            </ul>
            <?php if (isset($credit_error)) : ?>
            <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i><?php echo $credit_error; ?></div>
            <?php endif; ?>
            <?php if (isset($datetime_cd_error)) : ?>
            <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i><?php echo $datetime_cd_error; ?></div>
            <?php endif; ?>
            <p class="page-caption">minikura Libraryで取り出すアイテムの配送情報を入力します。</p>
            <ul class="input-info">
                <li>
                    <label class="headline">配送住所</label>
                    <select class="address" name="address" id="address_id" data-datetime_cd="<?php echo h($datetime_cd); ?>">
                        <?php foreach ($addressList as $data) : ?>
                          <option value="<?php echo $data['address_id']; ?>" <?php echo (isset($_POST['address']) && ($_POST['address'] == $data['address_id'])) ? 'selected' : ''; ?>>
                            <?php echo h("〒${data['postal']} ${data['pref']}${data['address1']}${data['address2']}${data['address3']}　${data['lastname']}${data['firstname']}"); ?>
                          </option>
                        <?php endforeach; ?>;
                        <option value="add" <?php echo (isset($_POST['address']) && $_POST['address'] == 'add') ? 'selected' : ''; ?>>お届先を追加する</option>
                    </select>
                </li>
                <li class="input-address">
                    <ul class="add-address">
                        <li>
                            <label>郵便番号</label>
                            <input id="postal" name="data[CustomerAddress][postal]" type="tel" placeholder="例：140-0002" class='search_address_postal' value="<?php echo isset($this->request->data['CustomerAddress']['postal']) ? $this->request->data['CustomerAddress']['postal'] : ''; ?>">
                            <p class="txt-caption">入力すると住所が自動で反映されます。</p>
                            <?php echo $this->Form->error('CustomerAddress.postal', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                        <li>
                            <label>都道府県</label>
                            <input name="data[CustomerAddress][pref]" type="text" placeholder="例：東京都" class='address_pref' value="<?php echo isset($this->request->data['CustomerAddress']['pref']) ? $this->request->data['CustomerAddress']['pref'] : ''; ?>">
                            <?php echo $this->Form->error('CustomerAddress.pref', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                        <li>
                            <label>市区郡</label>
                            <input name="data[CustomerAddress][address1]" type="text" placeholder="例：品川区" class='address_address1' value="<?php echo isset($this->request->data['CustomerAddress']['address1']) ? $this->request->data['CustomerAddress']['address1'] : ''; ?>">
                            <?php echo $this->Form->error('CustomerAddress.address1', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                        <li>
                            <label>町域以降</label>
                            <input name="data[CustomerAddress][address2]" type="text" placeholder="例：東品川2-6-10" class='address_address2' value="<?php echo isset($this->request->data['CustomerAddress']['address2']) ? $this->request->data['CustomerAddress']['address2'] : ''; ?>">
                            <?php echo $this->Form->error('CustomerAddress.address2', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                        <li>
                            <label>建物名</label>
                            <input name="data[CustomerAddress][address3]" type="text" placeholder="例：Tビル" class='address_address3' value="<?php echo isset($this->request->data['CustomerAddress']['address3']) ? $this->request->data['CustomerAddress']['address3'] : ''; ?>">
                            <?php echo $this->Form->error('CustomerAddress.address3', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                        <li>
                            <label>電話番号</label>
                            <input name="data[CustomerAddress][tel1]" type="tel" placeholder="例：0312345678" value="<?php echo isset($this->request->data['CustomerAddress']['tel1']) ? $this->request->data['CustomerAddress']['tel1'] : ''; ?>">
                            <?php echo $this->Form->error('CustomerAddress.tel1', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                        <li>
                            <label>お名前 姓</label>
                            <input name="data[CustomerAddress][lastname]" class="lastname" type="text" placeholder="例：寺田" value="<?php echo isset($this->request->data['CustomerAddress']['lastname']) ? $this->request->data['CustomerAddress']['lastname'] : ''; ?>">
                            <?php echo $this->Form->error('CustomerAddress.lastname', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                        <li>
                            <label>お名前 姓(カナ)</label>
                            <input name="data[CustomerAddress][lastname_kana]" class="lastname_kana" type="text" placeholder="例：テラダ" value="<?php echo isset($this->request->data['CustomerAddress']['lastname_kana']) ? $this->request->data['CustomerAddress']['lastname_kana'] : ''; ?>">
                            <?php echo $this->Form->error('CustomerAddress.lastname_kana', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                        <li>
                            <label>お名前 名</label>
                            <input name="data[CustomerAddress][firstname]" class="firstname" type="text" placeholder="例：太郎" value="<?php echo isset($this->request->data['CustomerAddress']['firstname']) ? $this->request->data['CustomerAddress']['firstname'] : ''; ?>">
                            <?php echo $this->Form->error('CustomerAddress.firstname', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                        <li>
                            <label>お名前 名(カナ)</label>
                            <input name="data[CustomerAddress][firstname_kana]" class="firstname_kana" type="text" placeholder="例：タロウ" value="<?php echo isset($this->request->data['CustomerAddress']['firstname_kana']) ? $this->request->data['CustomerAddress']['firstname_kana'] : ''; ?>">
                            <?php echo $this->Form->error('CustomerAddress.firstname_kana', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                        </li>
                    </ul>
                    <label class="input-check">
                        <input type="checkbox" class="cb-square" name="data[CustomerAddress][register_address_book]" value="1"<?php echo isset($this->request->data['CustomerAddress']['register_address_book']) ? 'checked' : ''; ?>><span class="icon"></span><span class="label-txt">アドレスブックに登録する</span>
                    </label>
                </li>

                <?php if ($yumail === true) : ?>
                <li>
                    <label class="headline">配送方法</label>
                    <ul class="li-address">
                        <li class="note">メール便での配送</li>
                        <li>1冊のみのお取り出しの場合、メール便での配送となります。<br>お届け希望日時および伝票追跡ができません。</li>
                    </ul>
                </li>
                <?php else : ?>
                <li>
                    <label class="headline">お届け希望日時</label>
                    <select name="datetime_cd" data-datetime_cd='<?php echo isset($datetime_cd) ? $datetime_cd : '0000-00-00'; ?>' id="datetime_cd">
                    </select>
                </li>
                <?php endif; ?>

                <?php if (is_array($default_card)) : ?>
                <li>
                    <label class="headline">決済</label>
                    <ul class="li-credit">
                        <input type="hidden" name="resister_credit" id="resister_credit" value="0">
                        <li>ご登録のクレジットカード</li>
                        <li><?php echo $default_card['card_no']; ?></li>
                        <li><?php echo $default_card['holder_name']; ?></li>
                        <!--<li>
                            <label>セキュリティコード</label>
                            <input name="security_cd" id="securitycode" placeholder="例：123">
                            <p class="txt-caption">カード裏面に記載された3〜4桁の番号をご入力ください。</p>
                        </li>-->
                    </ul>
                </li>

                <?php else : ?>
                <li>
                    <ul class="add-credit">
                        <input type="hidden" name="resister_credit" id="resister_credit" value="1">
                        <li>
                            <div id="gmo_credit_card_info"></div>
                            <div id="gmo_validate_error"></div>
                        </li>
                        <li>
                            <label>クレジットカード番号</label>
                            <input id="cardno" placeholder="例：1234-5678-1234-5678">
                        </li>
                        <li>
                            <label>セキュリティコード</label>
                            <input name="security_cd" id="securitycode" placeholder="例：123">
                            <p class="txt-caption">カード裏面に記載された3〜4桁の番号をご入力ください。</p>
                        </li>
                        <li>
                            <a class='title-description'>セキュリティコードとは？</a>
                            <ul class="inline-description">
                                <li>
                                    <label>セキュリティコードとは？</label>
                                    <p>クレジットカード番号とは異なる、3桁または4桁の番号で、第三者がお客様のクレジットカードを不正利用出来ないようにする役割があります。カードの種類によってセキュリティコードの記載位置が異なりますので、下記をご覧ください。</p>
                                </li>
                                <li>
                                    <ul class="">
                                        <li>
                                            <label>Visa または Mastercard 等の場合</label>
                                            <p>カードの裏面の署名欄に記入されている3桁の番号です。カード番号の下3桁か、その後に記載されています。</p>
                                            <p><img src="/images/visa.png" alt=""></p>
                                        </li>
                                        <li>
                                            <label>American Express の場合</label>
                                            <p>カードの表面に記入されている4桁の番号です。カード番号の下4桁か、その後に記載されています。</p>
                                            <p><img src="/images/amex.png" alt=""></p>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <label>有効期限</label>
                            <ul class="expiration">
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
                            <label>クレジットカード名義</label>
                            <input id="holdername" placeholder="例：TARO TERRADA">
                            <p class="txt-caption">（※半角大文字英字 半角スペース . - ・）</p>
                            <input type="hidden" id="shop_id" value="<?php echo Configure::read('app.gmo.shop_id'); ?>">
                        </li>
                        <li>
                            <a class='title-description'>クレジットカード情報の取り扱いについて</a>
                            <ul class="inline-description">
                                <li>
                                    <label>クレジットカード情報の取扱いについて</label>
                                    <p>クレジットカード情報をご本人様より直接ご提供いただく事に関し、以下の項目を明示いたします。ご同意いただける場合は注文手続きへお進みください。</p>
                                </li>
                                <li>
                                    <label>クレジットカード情報の利用目的</label>
                                    <p>当社サービスのご利用にクレジットカード決済を希望するお客様のサービス代金決済処理のため、および同決済に関するお問い合わせに対応するため</p>
                                </li>
                                <li>
                                    <label>取得者名</label>
                                    <p>寺田倉庫株式会社</p>
                                </li>
                                <li>
                                    <label>提供先名</label>
                                    <p>株式会社日本カードネットワーク及びGMOペイメントゲートウェイ<br> （以下「決済代行会社」といいます）
                                    </p>
                                </li>
                                <li>
                                    <label>保存期間</label>
                                    <p>当社サービスのご利用にかかる契約・利用目的の終了時およびこれに付随する業務の終了時から７年間また、クレジットカード情報を決済代行会社に提供することについて以下の項目を明示いたします。</p>
                                </li>
                                <li>
                                    <label>決済代行会社に提供する目的</label>
                                    <p>当社サービスのご利用にクレジットカード決済を希望するお客様のサービス代金決済処理のため、および同決済に関するお問い合わせに対応するため</p>
                                </li>
                                <li>
                                    <label>決済代行会社に提供する個人情報の項目</label>
                                    <p>クレジットカード契約者名、クレジットカード番号、有効期限、セキュリティコード（CVV）</p>
                                </li>
                                <li>
                                    <label>クレジットカード情報提供の手段または方法</label>
                                    <p>WebサイトからのSSL通信による伝送</p>
                                </li>
                                <li>
                                    <label>クレジットカード情報の提供を受ける者または提供を受ける者の組織の種類および属性</label>
                                    <p>クレジットカード決済代行会社</p>
                                </li>
                                <li>
                                    <label>当社と決済代行会社との間の個人情報の取り扱いに関する契約</label>
                                    <p>有り</p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <li>
                    <section class="l-input-pnt">
                        <label class="headline">ポイントのご利用</label>
                        <ul class="l-pnt-detail">
                            <li>
                                <p class="txt-pnt">お持ちのポイントをご利用料金に割り当てることが出来ます。<br>
                                    1ポイント1円として100ポイント以上の残高から10ポイント単位でご利用いただけます。</p>
                            </li>
                            <li>
                              <p class="txt-pnt">お持ちのポイントをご利用料金に割り当てることが出来ます。<br>
                                1ポイント1円として100ポイント以上の残高から10ポイント単位でご利用いただけます。</p>
                            </li>
                            <li>
                              <h3 class="title-pnt-sub">今回のご利用料金合計<span class="val"><?php echo number_format($outbound_total_price);?></span>円</h3>
                            </li>
                            <li>
                              <h3 class="title-pnt-sub">現在のお持ちのポイント<span class="val"><?php echo number_format($point_balance);?></span>ポイント</h3>
                            </li>
                            <li>
                              <h3 class="title-pnt-sub">ご利用可能ポイント<span class="val"><?php echo number_format($use_possible_point);?></span>ポイント</h3>
                            </li>
                            <li>
                                <p class="txt-pnt">ご利用状況によっては、お申込みされたポイントをご利用できない場合がございます。<br>取り出しのお知らせやオプションのお知らせにはポイント料金調整前の価格が表示されます。ご了承ください。
                                </p>
                            </li>
                            <li>
                                <label class="headline">ご利用になるポイントを入力ください</label>
                                <?php echo $this->Form->input('PointUseImmediate.use_point', ['id' => 'use_point', 'class' => 'use_point', 'type' => 'text', 'placeholder'=>'例：100', 'error' => false, 'label' => false, 'div' => false]); ?>
                                <?php echo $this->Form->error('PointUseImmediate.use_point', null, ['wrap' => 'p', 'class' => 'valid-il']) ?>
                            </li>
                        </ul>
                    </section>
                </li>
            </ul>
        </div>
        </form>
        <!--footer-->
        <footer>
            <nav class="footer-nav">
                <li><a href="https://minikura-logitech.com/" target="_blank">運営チーム</a>
                </li>
                <li><a href="/privacy/">個人情報について</a>
                </li>
                <li><a href="/security_policy/">セキュリティポリシー</a>
                </li>
                <li><a href="/commercial_transaction/">特定商取引に関する表記について</a>
                </li>
                <li><a href="/use_agreement/">利用規約</a>
                </li>
                <li><a href="/contact_us/">お問い合わせ</a>
                </li>
            </nav>
            <p class="copyright">© Warehouse TERRADA</p>
        </footer>
        <div class="nav-fixed">
            <ul>
                <li><a class="btn-d-gray" href="/outbound/library_select_item">戻る</a></li>
                <li><button class="btn-red" id="execute">確認</button></li>
            </ul>
        </div>
    </div>
    <?php
      $this->Html->script('jquery.min', ['inline' => false]);
      $this->Html->script('jquery.easing', ['inline' => false]);
      $this->Html->script('bootstrap.min', ['inline' => false]);
      $this->Html->script('metisMenu.min', ['inline' => false]);
      $this->Html->script('animsition.min', ['inline' => false]);
      $this->Html->script('remodal.min', ['inline' => false]);
      $this->Html->script('iziModal.min', ['inline' => false]);
      $this->Html->script('app', ['inline' => false]);
      $this->Html->script('app_dev', ['inline' => false]);
      $this->Html->script('jquery.airCenter', ['inline' => false]);
      $this->Html->script('https://maps.google.com/maps/api/js?key=' . Configure::read('app.googlemap.api.key') . '&libraries=places', ['inline' => false]);
      $this->Html->script('minikura/address', ['inline' => false]);
      $this->Html->script('jquery.airAutoKana.js', ['inline' => false]);

      $this->Html->script(Configure::read("app.gmo.token_url"), ['inline' => false]);
      $this->Html->script('libGmoCreditCardPayment', ['inline' => false]);
      $this->Html->script('outbound/library_input_address', ['inline' => false]);

      echo $this->fetch('script');
      echo $this->fetch('scriptMinikura');
    ?>
<!--[if lte IE 9]>
    <script type="text/javascript" src="/js/jquery.placeholder.min.js"></script>
    <script>
    $(function () {
        $('input, textarea').placeholder();
    });
    </script>
<![endif]-->
</body>
</html>
