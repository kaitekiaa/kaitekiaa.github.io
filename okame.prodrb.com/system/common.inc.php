<?php
error_reporting(E_ALL);
//error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors','On');

chdir(dirname(__FILE__));

//設定読み込み
require_once 'config.inc.php';

//共通関数読み込み
require_once 'func.inc.php';

//例外クラス
require_once 'exception.inc.php';

//twig操作用クラス
require_once 'twig.inc.php';
$twig = new MyTwig(TEMPLATE_DIR, TWIG_CACHE_DIR, true);

//データベース管理クラス
require_once 'db.inc.php';
$db = PDODatabase::getInstance();

//カロリー計算クラス
require_once 'calorie.inc.php';
