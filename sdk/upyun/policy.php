<?php
require 'Upyun_3_0_0.php';

define('USER_NAME', 'tester');
define('PWD', 'grjxv2mxELR3');
define('BUCKET', 'sdkimg');
$config = new Config(BUCKET, USER_NAME, PWD);
$config->setFormApiKey('Mv83tlocuzkmfKKUFbz2s04FzTw=');

$data['save-key'] = $_GET['save_path'];
$data['expiration'] = time() + 120;
$data['bucket'] = BUCKET;
$policy = Util::base64Json($data);
$method = 'POST';
$uri = '/' . $data['bucket'];
$signature_class = new Signature;
$signature = $signature_class->getBodySignature($config, $method, $uri, null, $policy);
echo json_encode(array(
    'policy' => $policy,
    'authorization' => $signature
));
