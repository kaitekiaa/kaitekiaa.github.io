<?php

require_once dirname(dirname(__FILE__)) . '/system/common.inc.php';

$mayonnaise = new mayonnaise();

//初期化
$dish = array();

//画像処理部分から料理名を取得
//今は仮でフォームから料理名指定
//posデータ受け取る
$test = $mayonnaise->test();
$dish = $test;

//カロリー計算
$ans_dish = $mayonnaise->all_calorie($dish);

//マヨネーズかける処理
$test = $mayonnaise->decisionMayo($ans_dish);
$twig->assign('test', $test);
echo $twig->fetch('__result.html');