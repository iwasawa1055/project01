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

const PAYMENT_METHOD = [
    PAYMENT_METHOD_CREDITCARD => 'コーポレートカード',
    PAYMENT_METHOD_ACCOUNTTRANSFER => '口座振替',
];

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
const ANNOUNCEMENT_CATEGORY_ID_KIT_RECEIPT = 'INF080';

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
const KIT_CD_SNEAKERS = '120';

// 商品コード
const PRODUCT_CD_MONO = '004025';
const PRODUCT_CD_HAKO = '004024';
const PRODUCT_CD_CARGO_JIBUN = '005090';
const PRODUCT_CD_CARGO_HITOMAKASE = '005100';
const PRODUCT_CD_CLEANING_PACK = '004029';
const PRODUCT_CD_SHOES_PACK = '005000';
const PRODUCT_CD_SNEAKERS = '005310';

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
//    KIT_CD_SNEAKERS => ['XX' => 'スニーカーシュリンク処置'], // todo: 検討中
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
    KIT_CD_SNEAKERS => 'minikura SNEAKERS KIT',
];

const PRODUCT_NAME = [
    PRODUCT_CD_MONO => 'minikuraMONO',
    PRODUCT_CD_HAKO => 'minikuraHAKO',
    PRODUCT_CD_CLEANING_PACK => 'minikuraクリーニングパック',
    PRODUCT_CD_SHOES_PACK => 'minikuraシューズパック',
    PRODUCT_CD_CARGO_JIBUN => 'minikura CARGO じぶんで',
    PRODUCT_CD_CARGO_HITOMAKASE => 'minikura CARGO ひとまかせ',
    PRODUCT_CD_SNEAKERS => 'minikura SNEAKERS',
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

const INBOUND_CARRIER_DELIVERY_SNEAKERS = [
    INBOUND_DELIVERY_PICKUP . '_' . INBOUND_CARRIER_YAMAYO => 'ヤマト運輸に集荷依頼する',
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
    '11' => '退会について',
    '16' => 'minikuraへのご意見・ご相談',
    '18' => 'アニメイト・コレクションについて',
    '12' => 'その他',
];

const OEM_CD_LIST = [
    'minikura' => '',
    'sneakers' => 'sneakers',
];

const IN_USE_SERVICE = [
    'minikura' => [
        [
            'product' => 'mono',
            'name' => 'MONO',
            'product_cd' => PRODUCT_CD_MONO,
        ],
        [
            'product' => 'hako',
            'name' => 'HAKO',
            'product_cd' => PRODUCT_CD_HAKO,
        ],
        [
            'product' => 'cargo01',
            'name' => 'CARGO じぶんで',
            'product_cd' => PRODUCT_CD_CARGO_JIBUN,
        ],
        [
            'product' => 'cargo02',
            'name' => 'CARGO ひとまかせ',
            'product_cd' => PRODUCT_CD_CARGO_HITOMAKASE,
        ],
        [
            'product' => 'cleaning',
            'name' => 'クリーニングパック',
            'product_cd' => PRODUCT_CD_CLEANING_PACK,
        ],
        [
            'product' => 'shoes',
            'name' => 'シューズパック',
            'product_cd' => PRODUCT_CD_SHOES_PACK,
        ],
    ],
    'sneakers' => [
        [
            'product' => 'sneakers',
            'name' => 'SNEAKERS',
            'product_cd' => PRODUCT_CD_SNEAKERS,
        ],
    ],
];

const POINT_TYPE_GETU = 'GETU';

const POINT_TYPE = [
    'VIEW' => 'MONO VIEW オプション',
    'COP' => 'クリーニングオプション',
    'DOP' => 'データ化オプション',
    'WGF' => 'ギフト配送オプション',
    'SHKO' => '取り出し',
    'CPC1' => '初回ポイントプレゼント',
    POINT_TYPE_GETU => '月額保管',
];

const ISOLATE_ISLANDS = [
    '沖縄県',
];

const OUTBOUND_HAZMAT_NOT_EXIST = '0';
const OUTBOUND_HAZMAT_EXIST = '1';

const OUTBOUND_HAZMAT = [
    OUTBOUND_HAZMAT_EXIST => '含まれる',
    OUTBOUND_HAZMAT_NOT_EXIST => '含まれない',
];

const CONTACTUS_CD_ISOLATEISLANDS = '090';
