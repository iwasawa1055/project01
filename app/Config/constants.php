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

// デフォルトの性別
const CUSTOMER_DEFAULT_GENDER = 'm';

// デフォルトの生年月日(年)
const CUSTOMER_DEFAULT_BIRTH_YEAR = '1900';

// デフォルトの生年月日(月)
const CUSTOMER_DEFAULT_BIRTH_MONTH = '01';

// デフォルトの生年月日(日)
const CUSTOMER_DEFAULT_BIRTH_DAY = '01';

// デフォルトの生年月日
const CUSTOMER_DEFAULT_BIRTH = CUSTOMER_DEFAULT_BIRTH_YEAR.'-'.CUSTOMER_DEFAULT_BIRTH_MONTH.'-'.CUSTOMER_DEFAULT_BIRTH_DAY;

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

// 支払い方法(表示用)
const DISPLAY_PAYMENT_METHOD_CREDITCARD = 'クレジットカード';
const DISPLAY_PAYMENT_METHOD_AMAZON_PAY = 'Amazon Pay';

// 支払い口座登録状況
const ACCOUNT_SITUATION_UNREGISTERED = 'unregistered';
const ACCOUNT_SITUATION_REGISTRATION = 'registration';
const ACCOUNT_SITUATION_AMAZON_PAY = 'AmazonPay';

// 法人用支払い状況
const CORPORATE_PAYMENT_METHOD = [
    'unregistered' => '振替口座未登録',
    'registration' => '口座振替',
    'credit_card' => 'クレジットカード', 
];

// 配送業者コード
const CARRIER_CD_JPPOST = '0';
const CARRIER_CD_YAMATO = '1';
const CARRIER_CD_SAGAWA = '2';
const CARRIER_CD_OTHER = '99';

// 配送パターン
const DELIVERY_ID_PICKUP_COOL = '2';
const DELIVERY_ID_PICKUP = '6';
const DELIVERY_ID_MANUAL = '7';

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
const KIT_CD_STARTER_MONO = '200';
const KIT_CD_STARTER_MONO_APPAREL = '201';
const KIT_CD_STARTER_MONO_BOOK = '202';
const KIT_CD_HAKO_LIMITED_VER1 = '203';

// 商品コード
const PRODUCT_CD_MONO = '004025';
const PRODUCT_CD_HAKO = '004024';
const PRODUCT_CD_CARGO_JIBUN = '005090';
const PRODUCT_CD_CARGO_HITOMAKASE = '005100';
const PRODUCT_CD_CLEANING_PACK = '004029';
const PRODUCT_CD_SHOES_PACK = '005000';
const PRODUCT_CD_SNEAKERS = '005310';
const PRODUCT_CD_DIRECT_INBOUND = '005003';

// box_status, item_status
// キット購入・依頼
const BOXITEM_STATUS_BUYKIT_START = '10';
const BOXITEM_STATUS_BUYKIT_IN_PROGRESS = '20';
const BOXITEM_STATUS_BUYKIT_DONE = '30';
// 入庫・依頼
const BOXITEM_STATUS_INBOUND_START = '40';
const BOXITEM_STATUS_INBOUND_IN_PROGRESS = '60';
const BOXITEM_STATUS_INBOUND_DONE = '70';
// 出庫・依頼 一時
const BOXITEM_STATUS_OUTBOUND_LIMIT_START = '130';
const BOXITEM_STATUS_OUTBOUND_LIMIT_IN_PROGRESS = '140';
const BOXITEM_STATUS_OUTBOUND_LIMIT_DONE = '150';
const BOXITEM_STATUS_OUTBOUND_LIMIT_RETURN_DONE = '155';
const BOXITEM_STATUS_OUTBOUND_LIMIT_RETURN_IN_PROGRESS = '160';

// 出庫・依頼
const BOXITEM_STATUS_OUTBOUND_START = '180';
const BOXITEM_STATUS_OUTBOUND_IN_PROGRESS = '200';
const BOXITEM_STATUS_OUTBOUND_DONE = '210';

const BOX_STATUS_LIST = [
    '10' => '購入依頼中',
    '20' => '購入依頼中',
    '30' => '購入依頼中',
    '40' => 'お預かり中',
    '60' => '倉庫作業中',
    '70' => 'お預かり中',
    '130' => 'お預かり中',
    '155' => 'お預かり中',
    '160' => 'お預かり中',
    '180' => 'お預かり中',
    '200' => 'お預かり中',
    '150' => '取り出し済み',
    '210' => '取り出し済み',
];
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
    KIT_CD_STARTER_MONO => 'スターターキット minikuraMONO（レギュラーボックス）',
    KIT_CD_STARTER_MONO_APPAREL => 'スターターキット minikuraMONO（アパレルボックス）',
    KIT_CD_STARTER_MONO_BOOK => 'スターターキット minikuraMONO（ブックボックス）',
    KIT_CD_HAKO_LIMITED_VER1 => 'minikura HAKOお片付けパック'
];

const KIT_CODE_DISP_NAME_ARRAY =[
    'mono'          => array('code' => KIT_CD_MONO,             'name' => 'MONO レギュラーボックス'),
    'mono_apparel'  => array('code' => KIT_CD_MONO_APPAREL,     'name' => 'MONO アパレルボックス'),
    'mono_book'     => array('code' => KIT_CD_MONO_BOOK,        'name' => 'MONO ブックボックス'),
    'hako'          => array('code' => KIT_CD_HAKO,             'name' => 'HAKO レギュラーボックス'),
    'hako_apparel'  => array('code' => KIT_CD_HAKO_APPAREL,     'name' => 'HAKO アパレルボックス'),
    'hako_book'     => array('code' => KIT_CD_HAKO_BOOK,        'name' => 'HAKO ブックボックス'),
    'cleaning'      => array('code' => KIT_CD_CLEANING_PACK,    'name' => 'クリーニングパック'),
    'sneaker'       => array('code' => KIT_CD_SNEAKERS,         'name' => 'SNEAKERS'),
];

const PRODUCT_NAME = [
    PRODUCT_CD_MONO => 'minikuraMONO',
    PRODUCT_CD_HAKO => 'minikuraHAKO',
    PRODUCT_CD_CLEANING_PACK => 'minikuraクリーニングパック',
    PRODUCT_CD_SHOES_PACK => 'minikuraシューズパック',
    PRODUCT_CD_CARGO_JIBUN => 'minikura CARGO じぶんで',
    PRODUCT_CD_CARGO_HITOMAKASE => 'minikura CARGO ひとまかせ',
    PRODUCT_CD_SNEAKERS => 'minikura SNEAKERS',
    PRODUCT_CD_DIRECT_INBOUND => 'minikuraダイレクト',
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

// 問い合わせ区分
const CONTACT_DIVISION_ABOUTMINIKURA = '8'; // minikuraについて
const CONTACT_DIVISION_INFORMATION = '10'; // お知らせについて
const CONTACT_DIVISION_RESIGN = '11'; // 退会ついて
const CONTACT_DIVISION_OTHER = '12'; // その他
const CONTACT_DIVISION_BUG = '15'; // 不具合報告
const CONTACT_DIVISION_OPINION = '16'; // minikuraへのご意見・ご感想
const CONTACT_DIVISION_DISSOLUTION = '17'; //溶解の申し込みについて
const CONTACT_DIVISION_ANIMATECOLLECTION = '18'; //アニメイト・コレクションについて


const CONTACTUS_DIVISION = [
    CONTACT_DIVISION_INFORMATION => 'お知らせについて',
    CONTACT_DIVISION_RESIGN => '退会について',
    CONTACT_DIVISION_OPINION => 'minikuraへのご意見・ご相談',
    CONTACT_DIVISION_DISSOLUTION => '溶解の申し込みについて',
    CONTACT_DIVISION_BUG => '不具合報告',
    CONTACT_DIVISION_OTHER => 'その他',
];

const INQUIRY_DIVISION = [
    CONTACT_DIVISION_ABOUTMINIKURA => 'minikuraについて',
    CONTACT_DIVISION_OPINION => 'minikuraへのご意見・ご相談',
    CONTACT_DIVISION_ANIMATECOLLECTION => 'アニメイト・コレクションについて',
    CONTACT_DIVISION_BUG => '不具合報告',
    CONTACT_DIVISION_OTHER => 'その他',
];

const OEM_CD_LIST = [
    'minikura' => '',
    'sneakers' => 'sneakers',
];

const IN_USE_SERVICE = [
    'minikura' => [
        [
            'product' => 'mono',
            'name' => 'minikuraMONO',
            'product_cd' => PRODUCT_CD_MONO,
            'name_mobile' => 'minikura<br />MONO',
        ],
        [
            'product' => 'hako',
            'name' => 'minikuraHAKO',
            'product_cd' => PRODUCT_CD_HAKO,
            'name_mobile' => 'minikura<br />HAKO',
        ],
        [
            'product' => 'mono_direct',
            'name' => 'minikuraダイレクト',
            'product_cd' => PRODUCT_CD_DIRECT_INBOUND,
            'name_mobile' => 'minikura<br />ダイレクト',
        ],
        [
            'product' => 'cargo01',
            'name' => 'CARGO じぶんで',
            'product_cd' => PRODUCT_CD_CARGO_JIBUN,
            'name_mobile' => 'CARGO<br />じぶんで',
        ],
        [
            'product' => 'cargo02',
            'name' => 'CARGO ひとまかせ',
            'product_cd' => PRODUCT_CD_CARGO_HITOMAKASE,
            'name_mobile' => 'CARGO<br />ひとまかせ',
        ],
        [
            'product' => 'cleaning',
            'name' => 'クリーニングパック',
            'product_cd' => PRODUCT_CD_CLEANING_PACK,
            'name_mobile' => 'クリーニング<br />パック',
        ],
        [
            'product' => 'shoes',
            'name' => 'シューズパック',
            'product_cd' => PRODUCT_CD_SHOES_PACK,
            'name_mobile' => 'シューズ<br />パック',
        ],
    ],
    'sneakers' => [
        [
            'product' => 'sneakers',
            'name' => 'SNEAKERS',
            'product_cd' => PRODUCT_CD_SNEAKERS,
            'name_mobile' => 'SNEAKERS<br />　',
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

// ニュースRSS取得URL
const NEWS_FEED_URL = 'http://news.minikura.com/info/news/feed';
// ニュース新着記事件数
const NEWS_LASTEST_ARTICLE_LIMIT = 5;

const SORT_ORDER = [
    'box' => [
        'inbound_date' => 'お預かり日順 で', 
        'box_id' => 'ボックスID順 で', 
        'box_name' => 'ボックス名順 で'
    ],
    'item' => [
        'inbound_date' => 'お預かり日順 で', 
        'item_id' => 'アイテムID順 で', 
        'item_name' => 'アイテム名順 で'
    ],
    'item_grid' => [
        'inbound_date' => 'お預かり日順', 
        'item_id' => 'アイテムID順', 
        'item_name' => 'アイテム名順'
    ],
];

const SORT_DIRECTION = [
    'asc' => 'A〜Z',
    'desc' => 'Z〜A',
];

const RANK_RATE = [
    // rankレート（順番で除算する）
    // 1番目:100, 2番目:50, 3番目:33, 4番目:25
    'match' => 300,
    // マッチしたワードのユニーク数を乗算する
    // 4ワード:80, 3ワード:60, 2ワード:40, 1ワード:20
    'match_num' => 20,
    // マッチしたワードの総件数。件数分を乗算する
    'all_num' => 10,
    // ニアリーマッチの件数
    'neary_num' => 5,
];
// ニュース機能on off 切り替え
// 1:稼働中 0:停止中 
const NEWS_ACTIVE_FLAG = 1;

// お知らせで表示しないメッセージに含まれる文字
const NEWS_NO_DISP_CHECK_WORD_CLUB = 'クラウド部室';

//* 販売機能 振り込み用口座管理
const BANK_ACCOUNT_TYPE = [
    '1' => '普通',
    '2' => '当座',
];

//* 販売機能 ステータス
const SALES_STATUS_ON_SALE = '1'; // 販売中
const SALES_STATUS_IN_PURCHASE = '2'; // 購入手続き中
const SALES_STATUS_TRANSFER_ALLOWED = '3'; // 振込可能
const SALES_STATUS_IN_ORDER = '4'; // 送金依頼中
const SALES_STATUS_PENDING = '5'; // 送金保留
const SALES_STATUS_REMITTANCE_COMPLETED = '6'; // 送金済み
const SALES_STATUS_PURCHASE_CANCEL = '7'; // 購入キャンセル
const SALES_STATUS_SALES_CANCEL = '8'; // 販売キャンセル

/**
 * minikuraTRADE 手数料関連
 */
// 振込手数料
const TRANSFER_CHARGE_PRICE = 324;

// amazon pay エラー表示
const AMAZON_PAY_ERROR_URGING_INPUT = 'Amazon Pay の登録住所に誤りがあります。正しい住所を選択するか、正しい住所を新規追加してください。';
const AMAZON_PAY_ERROR_PAYMENT_FAILURE = 'Amazon Pay の決済に失敗しました。マイページから再度ご注文いただくか、お問い合わせください。';
const AMAZON_PAY_ERROR_PAYMENT_FAILURE_RETRY = 'Amazon Pay の決済に失敗しました。選択されたカードをご確認いただき再度ご注文ください。ご不明な点がございましたらお問い合わせください。';

// 入力情報 エラー表示
const INPUT_ERROR = '入力した内容に誤りがあります。';

// ポイントサービス エラー表示
const POINT_BALANCE_ERROR = 'ポイント残高の情報の取得に失敗しました。';
const POINT_HISTORY_ERROR = 'ポイント履歴の情報の取得に失敗しました。';
