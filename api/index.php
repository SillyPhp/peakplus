<?php
ini_set('display_errors', '1');
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../MainProject/vendor/autoload.php';
require __DIR__ . '/../MainProject/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../MainProject/common/config/bootstrap.php';
require __DIR__ . '/../MainProject/api/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../MainProject/common/config/main.php',
    require __DIR__ . '/../MainProject/common/config/main-local.php',
    require __DIR__ . '/../MainProject/api/config/main.php',
    require __DIR__ . '/../MainProject/api/config/main-local.php'
);

(new yii\web\Application($config))->run();
