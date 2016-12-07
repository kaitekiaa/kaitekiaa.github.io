<?php

require_once 'db.inc.php';

//マヨネーズをかけるかかけないか判断
class mayonnaise{
    //撮影した料理画像の総カロリーを計算
    public function all_calorie($dish){
        //初期化
        $all_calorie = 0;
        $mayo = array();
        $ans = null;

        //料理ごとに辞書からカロリー取得
        foreach($dish as $key => $value){

            //DBから料理情報取得
            $info = $this->getCalorie($value);

            //DBに料理が含まれている時の処理
            if($info != null){
//                //総カロリーに加算
//                $all_calorie += $info["calorie"];
//                //マヨネーズをかけるか配列に格納
//                $mayo[$key] = $info["mayo"];
//                $mayo[$key] = $info["mayo"];
            }else{
                //マヨラーに渡していいの？
            }
        }

        $ans = array(
            'all_calorie' => $all_calorie,
            'mayo' => $mayo
        );

        return($ans);
    }

    //マヨネーズかける処理
    public function decisionMayo($ans_dish){
        //初期化
        $character_num = array(0,1);
        $character = null;
        //キャラクター決定しキャラ情報取得
        $rand_key = array_rand($character_num, 1);
        $character = $this->getCharacter($character_num[$rand_key]);

        //総カロリー判定
        if($ans_dish["all_calorie"] > $character["border"]){
            //マヨネーズかけない処理
        }else{
            //マヨネーズかける処理
            foreach($ans_dish["mayo"] as $value){
                //マヨネーズをかける料理ごとに処理？
            }
        }
    }

    //DBから指定した料理の情報を取得する
    public function getCalorie($name) {
        $sql = "SELECT * FROM `recipes` WHERE `name`=:name";
        $db = PDODatabase::getInstance();
        $dbres = $db->query($sql, array('name'=>$name));
        $dbret = $db->fetch($dbres);
        return($dbret);
    }

    //DBからキャラクターの情報を取得する
    public function getCharacter($id){
        $db = PDODatabase::getInstance();
        $sql = "SELECT * FROM `character` WHERE `id`=:id LIMIT 1";
        $dbres = $db->query($sql, array('id'=>$id));
        $dbret = $db->fetch($dbres);
        return($dbret);
    }
}