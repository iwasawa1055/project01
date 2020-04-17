<?php
// Manual Configure & Startup Configure
$config['site.static_content_url'] = 'https://minikura.com';
$config['site.env_name'] = 'development';
// sneakers top page
$config['site.sneakers.static_content_url'] = 'https://b-www.minikura.com/contents/sneakers/';

//* Env Switch
switch (true) {
    //** Console
    case (! isset($_SERVER['SERVER_NAME'])):
        break;

    //**
    case $_SERVER['HTTP_HOST'] === 'goto-contents.minikura.com':
    case $_SERVER['SERVER_NAME'] === 'goto-mypage.minikura.com':
        $config['site.mypage.url'] = 'https://goto-mypage.minikura.com';
		$config['site.static_content_url'] = 'https://goto-contents.minikura.com';
        break;
    case $_SERVER['HTTP_HOST'] === 'yoshida-www.minikura.com':
    case $_SERVER['SERVER_NAME'] === 'yoshida-mypage.minikura.com':
        $config['site.mypage.url'] = 'https://yoshida-mypage.minikura.com';
        $config['site.static_content_url'] = 'https://yoshida-www.minikura.com';

        $url = 'https://yoshida-user-api.minikura.com';
        $config['api.minikura.oem_key'] = 'mB9JCKud0_o_yQgYYhulLTpuR9plqU5BjkXU9pgb_tiyn16xwfxpSA--';
        $config['api.minikura.access_point.minikura_v3'] = $url . '/v3/warehouse/minikura';
        $config['api.minikura.access_point.minikura_v4'] = $url . '/v4/minikura';
        $config['api.minikura.access_point.minikura_v5'] = $url . '/v5/minikura';
        $config['api.minikura.access_point.gmopayment_v4'] = $url . '/v4/gmo_payment';
        $config['api.minikura.access_point.gmopayment_v5'] = $url . '/v5/gmo_payment';
        $config['api.minikura.access_point.cpss_v5'] = $url . '/v5/cpss';
        $config['api.minikura.access_point.amazon_pay_v3'] = $url . '/v3/payment/amazon_pay';
        $config['api.minikura.access_point.amazon_pay_v4'] = $url . '/v4/amazon_pay';
        $config['api.minikura.access_point.amazon_pay_v5'] = $url . '/v5/amazon_pay';
        $config['api.minikura.access_point.facebook_v5'] = $url . '/v5/facebook';
    break;
    case $_SERVER['HTTP_HOST'] === 'sato-www.minikura.com':
    case $_SERVER['SERVER_NAME'] === 'sato-mypage.minikura.com':
        $config['site.mypage.url'] = 'https://sato-mypage.minikura.com';
        $config['site.static_content_url'] = 'https://sato-www.minikura.com';

        $url = 'https://a-api.minikura.com';
        $config['api.minikura.oem_key'] = 'mB9JCKud0_o_yQgYYhulLTpuR9plqU5BjkXU9pgb_tiyn16xwfxpSA--';
        $config['api.minikura.access_point.minikura_v3'] = $url . '/v3/warehouse/minikura';
        $config['api.minikura.access_point.minikura_v4'] = $url . '/v4/minikura';
        $config['api.minikura.access_point.minikura_v5'] = $url . '/v5/minikura';
        $config['api.minikura.access_point.gmopayment_v4'] = $url . '/v4/gmo_payment';
        $config['api.minikura.access_point.gmopayment_v5'] = $url . '/v5/gmo_payment';
        $config['api.minikura.access_point.cpss_v5'] = $url . '/v5/cpss';
        $config['api.minikura.access_point.amazon_pay_v3'] = $url . '/v3/payment/amazon_pay';
        $config['api.minikura.access_point.amazon_pay_v4'] = $url . '/v4/amazon_pay';
        $config['api.minikura.access_point.amazon_pay_v5'] = $url . '/v5/amazon_pay';
        $config['api.minikura.access_point.facebook_v5'] = $url . '/v5/facebook';
        break;
    case $_SERVER['HTTP_HOST'] === 't-adachi-contents.minikura.com':
    case $_SERVER['SERVER_NAME'] === 't-adachi-mypage.minikura.com':
        $config['site.mypage.url'] = 'https://t-adachi-mypage.minikura.com';
        $config['site.static_content_url'] = 'https://t-adachi-contents.minikura.com';
        break;
    case $_SERVER['HTTP_HOST'] === 'yoshioka-contents.minikura.com':
    case $_SERVER['SERVER_NAME'] === 'yoshioka-mypage.minikura.com':
        $config['site.mypage.url'] = 'https://yoshioka-mypage.minikura.com';
        $config['site.static_content_url'] = 'https://yoshioka-contents.minikura.com';
        break;
    case $_SERVER['HTTP_HOST'] === 'ishikawa1053-contents.minikura.com':
    case $_SERVER['SERVER_NAME'] === 'ishikawa1053-mypage.minikura.com':
        $config['site.mypage.url'] = 'https://ishikawa1053-mypage.minikura.com';
        $config['site.static_content_url'] = 'https://ishikawa1053-contents.minikura.com';

        $url = 'https://dev-api.minikura.com';
        $config['api.minikura.oem_key'] = 'mB9JCKud0_o_yQgYYhulLTpuR9plqU5BjkXU9pgb_tiyn16xwfxpSA--';
        $config['api.minikura.access_point.minikura_v3'] = $url . '/v3/warehouse/minikura';
        $config['api.minikura.access_point.minikura_v4'] = $url . '/v4/minikura';
        $config['api.minikura.access_point.minikura_v5'] = $url . '/v5/minikura';
        $config['api.minikura.access_point.gmopayment_v4'] = $url . '/v4/gmo_payment';
        $config['api.minikura.access_point.gmopayment_v5'] = $url . '/v5/gmo_payment';
        $config['api.minikura.access_point.cpss_v5'] = $url . '/v5/cpss';
        $config['api.minikura.access_point.amazon_pay_v3'] = $url . '/v3/payment/amazon_pay';
        $config['api.minikura.access_point.amazon_pay_v4'] = $url . '/v4/amazon_pay';
        $config['api.minikura.access_point.amazon_pay_v5'] = $url . '/v5/amazon_pay';
        $config['api.minikura.access_point.facebook_v5'] = $url . '/v5/facebook';
        break;
    default:
        $config['site.mypage.url'] = 'https://' . $_SERVER['HTTP_HOST'];
}

//* strage server
$config['api.strage.host'] = '192.168.16.124';
$config['api.strage.file_dir'] = '/data/s/dev-image.minikura.com/app/webroot/i/';
$config['api.strage.url'] = 'http://dev-image.minikura.com:10080/i/';
$config['api.strage.ssh.username'] = 'minikura.com';
$config['api.strage.ssh.rsa.id_rsa_public'] = '/home/minikura.com/.ssh/id_rsa.pub';
$config['api.strage.ssh.rsa.id_rsa'] = '/home/minikura.com/.ssh/id_rsa';

// クリーニング費用設定
$config['app']['kit']['cleaning']['item_group_cd']['010'] = 900;
$config['app']['kit']['cleaning']['item_group_cd']['030'] = 3800;

$config['app']['kit']['cleaning']['work_type']['010'] = "030";
$config['app']['kit']['cleaning']['work_type']['030'] = "032";

// タイムアウト（秒）
$config['api.timeout'] = 40;
$config['api.connect_timeout'] = 40;
$config['api.user_agent'] = 'minikura';
/**
 * 恒久ログインlogout パラメータ
 */
$config['app']['login_cookie']['cookie_period'] = 60 * 60 * 24 * 180;
$config['app']['login_cookie']['param'] = 'logout';


/**
 * 会員登録用パラメタ―
 */
$config['app']['register']['birthyear']['birthyear_start'] = 1920;
$config['app']['register']['birthyear']['birthyear_default'] = 1980;

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

/**
 * Amazon Pay 環境変更
 */
$config['app']['amazon_pay']['Widgets_url'] = 'https://static-fe.payments-amazon.com/OffAmazonPayments/jp/sandbox/lpa/js/Widgets.js';
$config['app']['amazon_pay']['Amazon_MWS_EP'] = 'https://mws.amazonservices.jp/OffAmazonPayments_Sandbox/2013-01-01/';
$config['app']['amazon_pay']['profile_API_EP'] = 'https://api-sandbox.amazon.co.jp/user/profile';

/**
 * Amazon Pay 設定値
 */
$config['app']['amazon_pay']['merchant_id'] = 'A1MBRBB8GPQFL9'; // 出品者ID
$config['app']['amazon_pay']['access_key'] = 'AKIAIZZ2IUFQHH5JOZEQ';
$config['app']['amazon_pay']['secret_key'] = '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';
$config['app']['amazon_pay']['client_id'] = 'amzn1.application-oa2-client.9c0c92c3175948e3a4fd09147734998e';
$config['app']['amazon_pay']['region'] = 'jp';
$config['app']['amazon_pay']['sandbox'] = 'true';

/**
 * GMO 設定値
 */
$config['app']['gmo']['token_url'] = 'https://pt01.mul-pay.jp/ext/js/token.js';
$config['app']['gmo']['shop_id'] = 'tshop00019362';

/**
 * facebook 設定値
 */
$config['app']['facebook']['app_id'] = '382847972547430';
$config['app']['facebook']['version'] = 'v3.2';

/**
 * zendesk 設定値
 */
$config['app']['zendesk']['env_type'] = 'dev';
$config['app']['zendesk']['access_point'] = 'https://minikura1539678083.zendesk.com/api';
$config['app']['zendesk']['help_access_point'] = 'https://minikura.zendesk.com/api';
$config['app']['zendesk']['site_id'] = 'dev.minikura@terrada.co.jp/token';
$config['app']['zendesk']['site_token'] = 'NJQzszGxSQlxySvenn6i2C0Cjgnnlweye4sR0D38';

/**
 * Google Map API
 */
$config['app']['googlemap']['api']['key'] = 'AIzaSyDTUsXrnbPBzq2PQ58FcU5XJ53d417ZxEI';

/**
 * A8 コンバージョンタグ
 */
$config['app']['a8']['pid'] = 's00000000062001';

/**
 * エラー表示
 */
// CakePHP Debug Level
Configure::write('debug', 2);
// php display
ini_set('display_errors', '1');
error_reporting(E_ALL);

/*
* Exception時のメール送信関連
*/
// Error Mail Sender
$config['app.e.mail.flag'] = true;
$config['app.e.mail.env_name'] = '開発';
$config['app.e.mail.service_name'] = 'minikura.com';
$config['app.e.mail.sender.HOST'] = 'mail.minikura.com';
$config['app.e.mail.sender.PORT'] = 25;
$config['app.e.mail.sender.MAIL FROM'] = 'alert@minikura.com';
$config['app.e.mail.sender.MAIL FROM DISP'] = 'MINIKURA開発';
$config['app.e.mail.sender.USER'] = '';
$config['app.e.mail.sender.PASS'] = '';
// Receiver
$config['app.e.mail.receiver.warning.To'] = array('yasuda.soichi@terrada.co.jp','yoshida.shota@terrada.co.jp','sato.takashi@terrada.co.jp');
$config['app.e.mail.receiver.warning.Cc'] = array();
$config['app.e.mail.receiver.warning.Bcc'] = array();
$config['app.e.mail.receiver.defect.To'] = array('yasuda.soichi@terrada.co.jp','yoshida.shota@terrada.co.jp','sato.takashi@terrada.co.jp');
$config['app.e.mail.receiver.defect.Cc'] = array();
$config['app.e.mail.receiver.defect.Bcc'] = array();
$config['app.e.mail.receiver.critical.To'] = array('yasuda.soichi@terrada.co.jp','yoshida.shota@terrada.co.jp','sato.takashi@terrada.co.jp');
$config['app.e.mail.receiver.critical.Cc'] = array();
$config['app.e.mail.receiver.critical.Bcc'] = array();
$config['app.e.mail.receiver.fatal.To'] = array('yasuda.soichi@terrada.co.jp','yoshida.shota@terrada.co.jp','sato.takashi@terrada.co.jp');
$config['app.e.mail.receiver.fatal.Cc'] = array();
$config['app.e.mail.receiver.fatal.Bcc'] = array();
$config['app.e.mail.subject.default'] = '【 障害 】' . $config['app.e.mail.env_name'] . ' ' . $config['app.e.mail.service_name'] . ' システムエラー';
$config['app.e.mail.subject.warning'] = '【 警告 】' . $config['app.e.mail.env_name'] . ' ' . $config['app.e.mail.service_name'] . ' Warningエラー';
$config['app.e.mail.body.default'] = <<<MAIL_BODY
minikura.comでシステムエラーが発生しました。

１〜２回発生：システム担当者は営業時間内に調査してください。

３回連続発生：緊急調査対象です。プロジェクトリーダーに緊急対応を依頼ください。

※連続発生が条件です

MAIL_BODY;
$config['app.e.mail.body.warning'] = <<<MAIL_BODY
以下の可能性があります。
・URLを変更してアクセスした
・ページのリンク切れ

基本的には営業時間中に調査いたしますが、大量に発生している場合は、ページのリンク切れの可能性があり、緊急調査対象になりますのでプロジェクトリーダーに依頼ください


MAIL_BODY;
