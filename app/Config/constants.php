<?php
/* Code */

// ユーザ区分
const CUSTOMER_DIVISION_CORPORATE = '1'; // 法人
const CUSTOMER_DIVISION_PRIVATE = '2'; // 個人

// 登録レベル
const CUSTOMER_REGIST_LEVEL_ENTRY = '1'; // 仮登録
const CUSTOMER_REGIST_LEVEL_CUSTOMER = '2'; // 本登録

// 支払状況
const CUSTOMER_PAYMENT_OK = '1'; // OK
const CUSTOMER_PAYMENT_NG = '2'; // NG

// 性別
const CUSTOMER_GENDER = [
    'm' => '男性',
    'f' => '女性',
];

// メールマガジン配信
const CUSTOMER_NEWSLETTER = [
    '0' => '配信しない',
    '1' => '配信する',
];

// 支払い方法
const PAYMENT_METHOD_CREDITCARD = 0; // クレジットカード
const PAYMENT_METHOD_ACCOUNTTRANSFER = 1; // 口座振替

// 支払い口座登録状況
const ACCOUNT_SITUATION_UNREGISTERED = 'unregistered';
const ACCOUNT_SITUATION_REGISTRATION = 'registration';

// 配送業者コード
const CARRIER_CD_JPPOST = '0';
const CARRIER_CD_YAMATO = '1';
const CARRIER_CD_SAGAWA = '2';
const CARRIER_CD_OTHER = '99';

// 配送パターン
const DELIVERY_ID_PICKUP_COOL = '2';
const DELIVERY_ID_PICKUP = '6';
const DELIVERY_ID_MANUAL = '7';

// 問い合わせ区分
const CONTACT_DIVISION_SERVICE = '1'; // サービスについて
const CONTACT_DIVISION_OUTBOUND = '2'; // 取り出しについて
const CONTACT_DIVISION_OPINION = '3'; // サービスへのご意見・ご相談

// 通知カテゴリID
const ANNOUNCEMENT_CATEGORY_ID_RECEIPT = 'INF283';
const ANNOUNCEMENT_CATEGORY_ID_BILLING = 'INF284';

// キットコード
const KIT_CD_HAKO = '64';
const KIT_CD_HAKO_APPAREL = '65';
const KIT_CD_HAKO_BOOK = '81';
const KIT_CD_MONO = '66';
const KIT_CD_MONO_APPAREL = '67';
const KIT_CD_MONO_BOOK = '82';
const KIT_CD_WINE_HAKO = '77';
const KIT_CD_WINE_MONO = '83';
const KIT_CD_CLEANING_PACK = '75';

// 商品コード
const PRODUCT_CD_MONO = '004025';
const PRODUCT_CD_HAKO = '004024';
const PRODUCT_CD_CARGO_JIBUN = '005090';
const PRODUCT_CD_CARGO_HITOMAKASE = '005100';
const PRODUCT_CD_CLEANING_PACK = '004029';
const PRODUCT_CD_SHOES_PACK = '005000';

// box_status, item_status
// キット購入・依頼
const BOXITEM_STATUS_BUYKIT_START = '10';
const BOXITEM_STATUS_BUYKIT_IN_PROGRESS = '20';
const BOXITEM_STATUS_BUYKIT_DONE = '30';
// 入庫・依頼
const BOXITEM_STATUS_INBOUND_START = '40';
const BOXITEM_STATUS_INBOUND_IN_PROGRESS = '60';
const BOXITEM_STATUS_INBOUND_DONE = '70';
// 出庫・依頼
const BOXITEM_STATUS_OUTBOUND_START = '180';
const BOXITEM_STATUS_OUTBOUND_IN_PROGRESS = '200';
const BOXITEM_STATUS_OUTBOUND_DONE = '210';
// 再入庫・依頼
// 220	完了
// 230	進行中
// オプション・依頼
// 130	進行中
// 140	完了
// 150	進行中
// 160	完了

/* Selecter */

// 入庫時選択オプション
const KIT_OPTION = [
    KIT_CD_MONO => ['MH' => 'あんしんオプション（162円/月）'],
    KIT_CD_MONO_APPAREL => ['MH' => 'あんしんオプション（162円/月）'],
    KIT_CD_MONO_BOOK => ['MH' => 'あんしんオプション（162円/月）'],
    KIT_CD_CLEANING_PACK => ['CH' => 'あんしんオプション（162円/月）'],
];

const KIT_NAME = [
    KIT_CD_HAKO => 'minikuraHAKO（レギュラーボックス）',
    KIT_CD_HAKO_APPAREL => 'minikuraHAKO（アパレルボックス）',
    KIT_CD_HAKO_BOOK => 'minikuraHAKO（ブックボックス）',
    KIT_CD_MONO => 'minikuraMONO（レギュラーボックス）',
    KIT_CD_MONO_APPAREL => 'minikuraMONO（アパレルボックス）',
    KIT_CD_MONO_BOOK => 'minikuraMONO（ブックボックス）',
    KIT_CD_WINE_HAKO => 'minikuraWine-HAKO-',
    KIT_CD_WINE_MONO => 'minikuraWine-MONO-',
    KIT_CD_CLEANING_PACK => 'minikuraクリーニングパック',
];

const PRODUCT_NAME = [
    PRODUCT_CD_MONO => 'minikuraMONO',
    PRODUCT_CD_HAKO => 'minikuraHAKO',
    PRODUCT_CD_CLEANING_PACK => 'minikuraクリーニングパック',
    PRODUCT_CD_SHOES_PACK => 'minikuraシューズパック',
    PRODUCT_CD_CARGO_JIBUN => 'minikura CARGO じぶんで',
    PRODUCT_CD_CARGO_HITOMAKASE => 'minikura CARGO ひとまかせ',
];

const INBOUND_DELIVERY_PICKUP = '6';
const INBOUND_DELIVERY_MANUAL = '7';
const INBOUND_CARRIER_YAMAYO = '1';
const INBOUND_CARRIER_JPPOST = '0';

const INBOUND_CARRIER_DELIVERY = [
    INBOUND_DELIVERY_PICKUP . '_' . INBOUND_CARRIER_YAMAYO => 'ヤマト運輸に集荷依頼する',
    INBOUND_DELIVERY_PICKUP . '_' . INBOUND_CARRIER_JPPOST => '日本郵便に集荷依頼する',
    INBOUND_DELIVERY_MANUAL => '自分で発送する',
];

const CONTACTUS_DIVISION = [
    '8' => 'お知らせについて',
    '11' => '退会について',
    '16' => 'minikuraへのご意見・ご相談',
    '17' => '溶解の申し込みについて',
    '12' => 'その他',
];

const INQUIRY_DIVISION = [
    '8' => 'minikuraについて',
    '16' => 'minikuraへのご意見・ご相談',
    '18' => 'アニメイト・コレクションについて',
    '12' => 'その他',
];
