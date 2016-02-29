<?php
// Manual Configure & Startup Configure

// local devel
$config['devel'] = false;

//* Name
$config['app']['name'][] = 'Minikura';

//* Host
$config['app']['hosts']['prod'] = 'minikura.com';
$config['app']['hosts']['stag'] = 'stag.minikura.com';
$config['app']['hosts']['dev'] = 'dev.minikura.com';

//* User Agent
$config['app.user_agent'] = 'Minikura';


//* Request Method
if (! empty($_GET['request_method'])) {
    if (preg_match('/^(?:get|post|put|patch|delete)$/i', $_GET['request_method'])) {
        $config['app.request.method'] = strtoupper($_GET['request_method']);
    } else {
        new AppTerminalWarning(AppE::NOT_FOUND . 'request_method invalid', 404);
    }
} else {
    $config['app.request.method'] = 'GET';
}

//* Env
switch (true) {
    //** console
    case (! isset($_SERVER['SERVER_NAME'])):
        break;
    case $_SERVER['SERVER_NAME'] === 'minikura.com':
        // production
        CakeLog::drop('debug');
        CakeLog::drop('bench');
        Configure::load('EnvConfig/Production');
        break;
    case $_SERVER['SERVER_NAME'] === 'stag.minikura.com':
        // staging
        CakeLog::drop('debug');
        CakeLog::drop('bench');
        Configure::load('EnvConfig/Staging');
        break;
    default:
    // case $_SERVER['SERVER_NAME'] === 'dev.minikura.com':
        // development
        Configure::load('EnvConfig/Development');
        $config['api.oem_key'] = 'mB9JCKud0_o_yQgYYhulLTpuR9plqU5BjkXU9pgb_tiyn16xwfxpSA--';
        $config['api.minikura.schema'] = 'https://';
        $config['api.minikura.host'] = 'a-api.minikura.com';
        $config['api.minikura.access_point.minikura_v3'] = $config['api.minikura.schema'].$config['api.minikura.host'].'/v3/warehouse/minikura';
        $config['api.minikura.access_point.minikura_v4'] = $config['api.minikura.schema'].$config['api.minikura.host'].'/v4/minikura';
        $config['api.minikura.access_point.minikura_v5'] = $config['api.minikura.schema'].$config['api.minikura.host'].'/v5/minikura';
        $config['api.minikura.access_point.gmopayment_v4'] = $config['api.minikura.schema'].$config['api.minikura.host'].'/v4/gmo_payment';
}


//* Security
$config['app']['security']['XSS'] = true;
$config['app']['security']['Script_Injection'] = true;
$config['app']['security']['Null_Byte'] = true;
$config['app']['security']['Controlled'] = true;
$config['app']['security']['Path_Traversal'] = true;
$config['app']['security']['Tainted_Key'] = true;
$config['app']['security']['Click_Jacking'] = true;
$config['app']['security']['Session_Hyjack'] = true;
$config['app']['security']['Session_Fixasion'] = false;
$config['app']['security']['UTF7'] = true;
$config['app']['security']['CSRF'] = true;
$config['app']['security']['SQL_Injection'] = true;
$config['app']['security']['HTTP_Response'] = true;
$config['app']['security']['Inclusion'] = true;
$config['app']['security']['eval'] = true;
$config['app']['security']['Call_Back'] = true;
