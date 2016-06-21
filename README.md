# fufudao/yii2-base
YII base components, you can use yii1 in yii2

usage for index.php:

<?php
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true); //for yii1 and yii2
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3); //for yii1
defined('YII_ENV') or define('YII_ENV', 'dev'); //for yii2

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/fufudao/fufudao-yii2-base/Yii.php');
require(__DIR__ . '/../config/bootstrap.php');

$config2 = yii\helpers\ArrayHelper::merge(
require(__DIR__ . '/../config/main.php'),
require(__DIR__ . '/../config/main-local.php')
);

$yiiConfig=__DIR__.'/../config/1_main-local.php';
$config1 = require_once($yiiConfig);

Yii::run($config1,$config2);
