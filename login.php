<?php

define('READFILE', true);

require_once 'classes/Authorization.php';
require_once 'classes/Request.php';
require_once 'classes/Config.php';
require_once 'classes/Log.php';

$config = include_once 'config.php';
$result = array();

try {
    $log = new Log($config['logsDir']);
    $log->write("Request: \r\n" . var_export($_REQUEST, 1));
    $request = Request::filter($_REQUEST);

    $authorization = new Authorization($config['pathToUsersBase']);
    $authorization->chechUser($request['login'], $request['password']);

    $configClass = new Config($config['configsDir'], $config['templatesDir']);
    $result['data'] = $configClass->generate($request['login']);
} catch (Exception $ex) {
    $code = $ex->getCode();
    $result['errors'][] = array(
        'code' => $code,
    );

    $log->write("Errors: \r\nCode: {$code}\r\nMessage: " . $ex->getMessage());
}

echo json_encode($result);
