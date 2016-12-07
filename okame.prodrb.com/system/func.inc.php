<?php



function get_html_twitter_followers($sn)
{
    require_once "../system/func.inc.php";
    require_once "../system/config.inc.php";
    require_once "../system/db.inc.php";

/*
    $db = PDODatabase::getInstance();
    $_sn = $db->query($sn);
    $sql = "select * from b_followers_cache where screen_name = '$_sn' limit 1 ";
    $dbret = $db->fetch($db->query($sql));
    $tt = (int)((time()-strtotime($dbret['utime']))/(3600*24));
    if ($dbret and $tt<30) {
        return $dbret;
    }
*/
    $data = _get_html_twitter_followers($sn);
 //   $_data = $db->escape($data);
 //   $db->query("insert ignore into b_followers_cache set screen_name='$_sn', data = '$_data' on duplicate key update  data = '$_data' ");
var_dump($data);exit;
    return $data;
}
function _get_html_twitter_followers($sn)
{
    $url = 'https://api.twitter.com/1/followers/ids.json?cursor=-1&screen_name='. trim($sn);
    $html = get_html($url);
    return $html;
}
function get_html_twitter_friends($sn)
{
    require_once "../system/func.inc.php";
    require_once "../system/config.inc.php";
    require_once "../system/db.inc.php";

    $db = PDODatabase::getInstance();
    $_sn = $db->query($sn);
    $sql = "select * from b_friends_cache where screen_name = '$_sn' limit 1 ";
    $dbret = $db->fetch($db->query($sql));
    
    $tt = (int)((time()-strtotime($dbret['utime']))/(3600*24));
    if ($dbret and $tt<30) {
        return $dbret;
    }
    $data = _get_html_twitter_friends($sn);
    $_data = $db->escape($data);
    $db->query("insert ignore into b_friends_cache set screen_name='$_sn', data = '$_data' on duplicate key update  data = '$_data' ");
    return $data;
}
function _get_html_twitter_friends($sn)
{
    $url = 'https://api.twitter.com/1/friends/ids.json?cursor=-1&screen_name='. trim($sn);
    $html = get_html($url);
    return $html;
}
function get_html_twitter_lookup($sn) 
{
    $url = 'https://api.twitter.com/1/users/lookup.json?screen_name='.trim($sn).'&include_entities=true';
    $html = get_html($url);
    return $html;
}
function get_html_twitter_timeline($sn)
{
    $url = 'https://api.twitter.com/1/statuses/user_timeline.json?include_entities=true&include_rts=true&screen_name='.trim($sn).'&count=200';
    $html = get_html($url);
    return $html;
}
function tweet_result_filter($str)
{
    $aa = json_decode($str, true);    
    $ret = $str.'';
    //return var_export($aa['user']['id_str'], true);
    if (isset($aa['error'])) {
        switch (trim($aa['error'])) {
            case 'Could not authenticate with OAuth.':
                $ret = '<div class="error_msg">投稿時に認証エラーが発生しました。再度ログインし直す必要があります。</div>';
                break;
            case 'Status is a duplicate.':
                $ret = '<div class="error_msg">過去に同じ内容の投稿があるため投稿できませんでした。</div>';
                break;
            case 'Status is over 140 characters.':
                $ret = '<div class="error_msg">投稿文字数が１４０文字を超えています。</div>';
                break;
            default:
                return $aa['error'];
        }
    }
    $id = trim((string)$aa['id_str']);
    //return var_export($aa, true);
    if ($id!='') {
        $img = $aa['user']['profile_image_url'];
        $name = $aa['user']['screen_name'];
        $ret = '';
        $ret .= "<a target='_blank' href='http://twitter.com/#!/$name/status/$id' >";
        $ret .= '<img src="'. $img . '" border=0 />';
        $ret .= $name;
        $ret .= '</a>';
        //$ret .= 'a';
        return "<a href='http://twitter.com/#!/$name/status/$id' target='_blank' >Tweet成功</a>";
    }
    return $ret;
}
function get_mem($cache_id)
{
    require_once 'memcached.inc.php';
    return MyMemcached::getInstance()->get($cache_id);
}
function set_mem($cache_id, $data, $ttl=360)
{
    require_once 'memcached.inc.php';
    return MyMemcached::getInstance()->set($cache_id, $data, $ttl);
}

function get_html($url, $timeout =2 , $ua='Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:17.0) Gecko/20100101 Firefox/17.0')
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($ua!='') {
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    }
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 3); //リダイレクトの最大数？

    curl_setopt($ch, CURLOPT_HEADER, 1); //ヘッダ尾w返す
    //ob_start();
    $html = curl_exec($ch);

    return $html;
}

function SplitStr($string, $header, $footer) {
	$h = strpos($string, $header);
	if($h === false) return "";
	$h += strlen($header);
	$str2 = substr($string, $h);
	$f = strpos($str2, $footer);
	if($f === false) return "";
	
	return substr($str2, 0, $f);
} 



function ddd($var/*, $uastr = 'Safari'*/)
{

    $_var = "------" . date('Y-m-d H:i:s') . " : " . $_SERVER['REMOTE_ADDR'];
    $_var .= " : " . $_SERVER['REQUEST_URI'] . "\n";
    $_var .= var_export($var, true);

    $list = array();
    $list['180.131.109.30'] = true;

    $ua = $_SERVER['HTTP_USER_AGENT'];

    $log_dir = dirname(dirname(__FILE__)) . '/log/php_debug_log/';
    $log_dir .= date('Ym') . '/';
    if (!is_dir($log_dir)) {
        @mkdir($log_dir);
    }

    if (!is_dir($log_dir)) {
        @mkdir($log_dir);
    }
    if (!is_dir($log_dir)) {
        return false;
    }

    $log_file = $log_dir  . date('Y-m-d') . '.log';
    @file_put_contents($log_file, $_var. "\n", FILE_APPEND);
    return;
    if ($list[$_SERVER['REMOTE_ADDR']]) {
        @file_put_contents($log_file, $_var. "\n", FILE_APPEND);
        /*
        if (strpos($ua, $uastr)!==false) {
            @file_put_contents($log_file, $_var. "\n", FILE_APPEND);
        }
        */
    }
}


function dd($var, $uastr = 'Safari')
{
    $list = array();
    $list['192.168.11.1'] = true;
//    $list['110.5.6.252']    = true;

    $ua = $_SERVER['HTTP_USER_AGENT'];

    if ($list[$_SERVER['REMOTE_ADDR']]) {
        if (strpos($ua, $uastr)!==false) {
            var_dump($var);
            exit;
        }
    }
}


function sendmail32($to, $subj, $body, $name='[photrip.ad-da.jp]', $from='photirp@photrip.ad-da.jp')
{
    require_once 'Mail.php';

    $params = array(
        'host' => 'localhost',
        //'host' => 'mail.makebot.sh',
        'port' => '25',
        'auth' => false,
        'username' => '',
        'password' => '',
        'localhost' => 'photrip.ad-da.jp', //HELO
    );

    $recipients = $to;

    $headers['From']    = '"' . $name . '"' . '<'. $from .'>';
    $headers['To']      = $to;
    //$headers['Subject'] = mb_encode_mimeheader(mb_convert_encoding($subj, 'ISO-2022-JP', 'UTF-8'), 'ISO-2022-JP', 'B', "\n");
    $headers['Subject'] = '=?iso-2022-jp?B?' . base64_encode(mb_convert_encoding($subj, 'ISO-2022-JP', 'UTF-8')) . '?=';
    $body = mb_convert_encoding($body, 'ISO-2022-JP', 'UTF-8');

    $objMail =& Mail::factory('smtp', $params);
    //var_dump($objMail);
    $result = $objMail->send($recipients, $headers, $body);
    if (PEAR::isError($result)) {
    echo $result -> getMessage();
    return false;
    }
    return true;
}
function sendmail3($to, $subj, $body, $name='[@wiki]', $from='support@atfreaks.com')
{
    require_once 'libs/phpmailer/class.phpmailer.php';
   // error_reporting(-1);
    $recipients = $to;

    $mail = new PHPMailer;
    $mail->IsSMTP();
    $mail->Host = "mail.atwiki.jp";
    $mail->Hostname = "atwiki.atwiki.jp";
    $mail->SMTPAuth = false;
 // $mail->Timeout = 30;
    $mail->CharSet = "iso-2022-jp";
    $mail->Encoding = "7bit";

    $mail->From = $from;
    $mail->FromName = $name;

    $mail->AddAddress(trim($to));
    $mail->Subject = '=?iso-2022-jp?B?' . base64_encode(mb_convert_encoding($subj, 'ISO-2022-JP', 'UTF-8')) . '?=';
    $mail->Body = mb_convert_encoding($body, 'ISO-2022-JP', 'UTF-8');


    if (!$mail->Send()){
    echo("メールが送信できませんでした。エラー:".$mail->ErrorInfo);
    return false;
    }
    return true;
}

function checkAPILimit($user_id='')
{
    if ($user_id == '') {
        return false;
    }
    require_once "../system/config.inc.php";
    require_once "../system/db.inc.php";
    require_once "../system/twit.inc.php";
    
    $db = PDODatabase::getInstance();
    $_user_id = $db->escape($user_id);
    $sql = "select oauth_token,oauth_token_secret from users where id = $_user_id limit 1";
    $dbret = $db->fetch($db->query($sql));
    $oauth_token = $dbret['oauth_token'];
    $oauth_token_secret = $dbret['oauth_token_secret'];
    $connection = new TwitterOAuth(TWITTER_CONSUMER_KEY,
                                    TWITTER_CONSUMER_SECRET,
                                    $oauth_token,
                                    $oauth_token_secret);
    $option = array();
    $post = $connection->oAuthRequest( 'account/rate_limit_status', 'GET', $option);
    $_post = json_decode($post, true);
    
    $response = array();
    $response['hourly_limit'] = $_post['hourly_limit'];
    $response['remain_limit'] = $_post['remaining_hits'];
    $response['reset_time'] = date('Y-m-d H:i:s',strtotime($_post['reset_time']));
    
    return $response;
}



