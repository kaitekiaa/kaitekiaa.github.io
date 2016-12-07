<?php

require_once dirname(dirname(__FILE__)) . '/system/common.inc.php';

$mayonnaise = new mayonnaise();

//初期化
$dish = array();

//画像処理部分から料理名を取得
//今は仮でここで指定
$dish = ["えびせん"];


//カロリー計算
$ans_dish = $mayonnaise->all_calorie($dish);

//$twig->assign('test', $ans_dish);
//
//echo $twig->fetch('');
////マヨネーズかける処理
//$mayonnaise->decisionMayo($ans_dish);

//画面処理もかかないといけん