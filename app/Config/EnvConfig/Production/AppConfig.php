<?php
// Manual Configure & Startup Configure
$config['site.name'] = 'minikura.com';
// 本サイトのURL（パスワードリセットメールの本文で使用）
$config['site.url'] = 'https://' . $_SERVER['HTTP_HOST'];
// 未ログイン時TOPメニューのリンク先
$config['site.top_page'] = 'https://' . $_SERVER['HTTP_HOST'];
// 静的コンテンツ用ドメイン
$config['site.static_content_url'] = 'https://minikura.com';
// 環境文字列（development, staging, production）
$config['site.env_name'] = 'production';
// sneakers top page
$config['site.sneakers.static_content_url'] = 'https://minikura.com/contents/sneakers/';

//* trade 
/*
* コンテンツ側からApacheでAlias設定中 
* 見た目コンテンツのURLを表示し、mypage側を参照する。絶対ドメインパスが必要になる
*/
$config['site.mypage.url'] = 'https://mypage.minikura.com';
$config['site.trade.url'] = 'https://minikura.com/trade/';

/**
 * エラー表示
 */
// CakePHP Debug Level
Configure::write('debug', 0);
// php display
ini_set('display_errors', '0');
error_reporting(E_ALL ^ E_NOTICE);

/**
 * API設定
 */
$config['api.minikura.oem_key'] = 'jaLt9UbT2rib9GJOclnTgsgDMqwT8BoXhYo.bLsGtnmHzVXQdX0ESw--';
$url = 'https://apiv3.minikura.com';
$config['api.minikura.access_point.minikura_v3'] = $url . '/v3/warehouse/minikura';
$config['api.minikura.access_point.minikura_v4'] = $url . '/v4/minikura';
$config['api.minikura.access_point.minikura_v5'] = $url . '/v5/minikura';
$config['api.minikura.access_point.gmopayment_v4'] = $url . '/v4/gmo_payment';
$config['api.minikura.access_point.gmopayment_v5'] = $url . '/v5/gmo_payment';
$config['api.minikura.access_point.cpss_v5'] = $url . '/v5/cpss';
$config['api.minikura.access_point.amazon_pay_v4'] = $url . '/v4/amazon_pay';
$config['api.minikura.access_point.amazon_pay_v5'] = $url . '/v5/amazon_pay';

/**
 * nike_snkrs用
 */
$config['api.sneakers.oem_key'] = 'ABSJCmGg6Uwm4m9031AbHtaeELCC3q10je0ZvTdfVDYp_x8Hzb8sCmgAQdtnmJ.QIX7HfB.hNKo-';

//* strage server
$config['api.strage.host'] = '192.168.16.191';
$config['api.strage.file_dir'] = '/data/s/image.minikura.com/app/webroot/i/';
$config['api.strage.url'] = 'http://image.minikura.com/i/';
$config['api.strage.ssh.username'] = 'minikura.com';
$config['api.strage.ssh.rsa.id_rsa_public'] = '/home/minikura.com/.ssh/id_rsa.pub';
$config['api.strage.ssh.rsa.id_rsa'] = '/home/minikura.com/.ssh/id_rsa';


/**
 * 恒久ログインlogout パラメータ
 */
$config['app']['login_cookie']['cookie_period'] = 60 * 60 * 24 * 180;
$config['app']['login_cookie']['param'] = 'logout';

/**
 * 会員登録用パラメタ―
 */
$config['app']['register']['birthyear']['birthyear_start'] = 1920;

/**
 * 静的ページからの遷移先変更
 */
$config['app']['lp_option']['param'] = 'option';
$config['app']['lp_code']['param'] = 'code';
$config['app']['sneaker_option']['param'] = 'key';

$config['app']['switch_redirect']['session_name'] = 'switch_pedirect_option';
$config['app']['switch_redirect']['param'] = 'option';

/**
 * 静的ページからの最大注文箱数
 */
$config['app']['first_order']['max_box'] = 20;
$config['app']['first_order']['direct_inbound']['max_box'] = 20;


// クリーニング費用設定
$config['app']['kit']['cleaning']['item_group_cd']['010'] = 700;
$config['app']['kit']['cleaning']['item_group_cd']['030'] = 3800;

$config['app']['kit']['cleaning']['work_type']['010'] = "030";
$config['app']['kit']['cleaning']['work_type']['030'] = "032";

/**
 * Amazon Pay 環境変更
 */
$config['app']['amazon_pay']['Widgets_url'] = 'https://static-fe.payments-amazon.com/OffAmazonPayments/jp/lpa/js/Widgets.js';
$config['app']['amazon_pay']['Amazon_MWS_EP'] = 'https://mws.amazonservices.jp/OffAmazonPayments/2013-01-01/';
$config['app']['amazon_pay']['profile_API_EP'] = 'https://api.amazon.co.jp/user/profile';

/**
 * Amazon Pay 設定値
 */
$config['app']['amazon_pay']['merchant_id'] = 'A1MBRBB8GPQFL9'; // 出品者ID
$config['app']['amazon_pay']['access_key'] = 'AKIAIZZ2IUFQHH5JOZEQ';
$config['app']['amazon_pay']['secret_key'] = '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';
$config['app']['amazon_pay']['client_id'] = 'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e';
$config['app']['amazon_pay']['region'] = 'jp';
$config['app']['amazon_pay']['sandbox'] = 'false';

/**
 * GMO 設定値
 */
$config['app']['gmo']['token_url'] = 'https://p01.mul-pay.jp/ext/js/token.js';
$config['app']['gmo']['shop_id'] = '9100111302184';

//*** Log
// 不要なログはDropします。
// CakeLog::drop('error');
// CakeLog::drop('mail');
CakeLog::drop('bench');
CakeLog::drop('debug');

/**
 * エラーメール設定
 */
// Error Mail Sender
$config['app.e.mail.flag'] = true;
$config['app.e.mail.env_name'] = '検証';
$config['app.e.mail.service_name'] = 'minikura.com';
$config['app.e.mail.sender.HOST'] = 'mail.minikura.com';
$config['app.e.mail.sender.PORT'] = 25;
$config['app.e.mail.sender.MAIL FROM'] = 'minikura@terrada.co.jp';
$config['app.e.mail.sender.MAIL FROM DISP'] = '寺田倉庫（minikura運営事務局）';
$config['app.e.mail.sender.USER'] = '';
$config['app.e.mail.sender.PASS'] = '';
// Receiver
$config['app.e.mail.receiver.warning.To'] = array('minikura-kikaku@terrada.co.jp');
$config['app.e.mail.receiver.warning.Cc'] = array();
$config['app.e.mail.receiver.warning.Bcc'] = array();
$config['app.e.mail.receiver.defect.To'] = array('minikura-kikaku@terrada.co.jp');
$config['app.e.mail.receiver.defect.Cc'] = array();
$config['app.e.mail.receiver.defect.Bcc'] = array();
$config['app.e.mail.receiver.critical.To'] = array('minikura-kikaku@terrada.co.jp');
$config['app.e.mail.receiver.critical.Cc'] = array();
$config['app.e.mail.receiver.critical.Bcc'] = array();
$config['app.e.mail.receiver.fatal.To'] = array('minikura-kikaku@terrada.co.jp');
$config['app.e.mail.receiver.fatal.Cc'] = array();
$config['app.e.mail.receiver.fatal.Bcc'] = array();
$config['app.e.mail.body'] = <<<MAIL_BODY
minikura.comでシステムエラーが発生しました。

１〜２回発生：システム担当者は営業時間内に調査してください。

３回連続発生：緊急調査対象です。プロジェクトリーダーに緊急対応を依頼ください。

※連続発生が条件です

MAIL_BODY;
