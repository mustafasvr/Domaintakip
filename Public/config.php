<?php



$config['db']['host'] = 'localhost';

$config['db']['port'] = '3306';

$config['db']['username'] = 'linkcinc_alanaditicaret';

$config['db']['password'] = '&{6ZCXP-kv-x';

$config['db']['dbname'] = 'linkcinc_fakedoma_newforum';



$config['fullUnicode'] = true;
$config['enableTfa'] = false;


if ($_SERVER['REMOTE_ADDR'] == '176.33.96.219') {
    $config['debug'] = true;
    $config['development']['enabled'] = true;
}