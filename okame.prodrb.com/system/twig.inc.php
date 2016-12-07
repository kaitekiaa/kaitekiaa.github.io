<?php
/**
 * twigラッパクラス
 * smartyの＠用ラッパクラスMySmartyと同様の使い方が出来るように作成した。
 * 20110122-23 MasakazuOHNO
 *
 */

require_once('libs/Twig/Autoloader.php');
Twig_Autoloader::register();

class MyTwig
{
    private $__tpl_dir = '';
    private $__vars    = array();
    private $__loader  = null;
    private $__twig    = null;
    
    
    function __construct($tpl_dir='',$cache_dir='', $debug=true)
    {
        if ($tpl_dir=='' or !is_dir($tpl_dir)) {
            // テンプレートディレクトリの指定が不正な場合、
            // 公開ディレクトリをテンプレートディレクトリとする。
            $tpl_dir = dirname(dirname(__FILE__)).'/htdocs/';
        }
        $this->__tpl_dir = $tpl_dir;   
        $this->__loader  = new Twig_Loader_Filesystem($this->__tpl_dir);
        $this->__options = array();
        
        if ($cache_dir=='' or !is_dir($cache_dir)) {
            //キャッシュディレクトリ指定がない場合、
            // 親ディレクトリ以下のcache/twig/ディレクトリを
            //キャッシュディレクトリとして利用
            $cache_dir = dirname(dirname(__FILE__)) . '/cache/twig/';
        }
            
        $this->__options['cache'] = $cache_dir;
        // debug==false にするとテンプレ更新しても自動で反映してくれなくなる
        // ので、開発時にはdebug==trueにしておく。
        $this->__options['debug'] = $debug;
        
        //cache用ディレクトリがない場合、作成する。作成できない場合エラー表示して終了する。
        if (!is_dir($this->__options['cache'])) {
            if (!@mkdir($this->__options['cache'])) { 
                echo "cannot mkdir " . $this->__options['cache'];
                exit;
            }
        }
        //cache用ディレクトリに書き込みできない場合、エラー表示して終了する。
        if (!is_writable($this->__options['cache'])) {
            echo "cannot writable " . $this->__options['cache'];
            exit;
        }
        
        $this->__twig    = new Twig_Environment(
                                $this->__loader,
                                $this->__options
                                );
        
        //自動エスケープするための処理 
        // 下記の通り自動で有効化されるのでコメントアウト
        // The core, escaper, and optimizer extensions do not need to be added
        //  to the Twig environment, as they are registered by default. 
        // You can disable an already registered extension:
        //$escaper = new Twig_Extension_Escaper(true);
        //$this->__twig->addExtension($escaper);
        
        //gettext多言語化対応
        //ref: http://www.twig-project.org/doc/extensions/i18n.html
        $this->__twig->addExtension(new Twig_Extensions_Extension_I18n());
        $this->__twig->addExtension(new  MY_Extension());
    }
    
    /**
     * 引数で指定したテンプレートでHTML生成した文字列を返す。
     */
    function fetch($tpl_name)
    {
        $template = $this->__twig->loadTemplate($tpl_name);
        return $template->render($this->__vars);
    }
    
    /**
     * 引数で指定したテンプレートでHTML生成した文字列を返す。
     * fetchのエイリアス。
     */
    function render($tpl_name)
    {
        return $this->fetch($tpl_name);
    }
    
    /**
     * 引数で指定したテンプレートで表示
     */
    function display($tpl_name)
    {
        $template = $this->__twig->loadTemplate($tpl_name);
        $template->display($this->__vars);
    }
    
    /**
     * 変数アサイン
     */
    function assign($_n, $_v=null)
    {
        $_n = trim($_n);
        
        if ($_v===null) {
            if (is_array($_n)) {
                foreach ($_n as $k=>$v) {
                    $this->__vars[$k] = $v;
                }
            } else {
                //not assign
                return false;
            }
        } else if ($_n!='') {
            $this->__vars[$_n] = $_v;
        }
        return true;
    }
}


class MY_Extension extends Twig_Extension
{
    public function getFilters()
    {
        $list = array();
        $list['strimwidth'] = new Twig_Filter_Method($this, 'strimwidth');
        $list['tweet_result_filter'] = new Twig_Filter_Method($this, 'tweet_result_filter');
        $list['conv_tweet_text'] = new Twig_Filter_Method($this, 'conv_tweet_text');
        $list['count'] = new Twig_Filter_Method($this, 'count');
        return $list;
    }
    function callback_replace_mension($m)
    {
        $text = $m[0];
        $sn = $m[1];
        return '<a href="/b/'.$sn.'">' . htmlspecialchars($text, ENT_QUOTES, "UTF-8") . '</a>';

    }
    function conv_tweet_text($str)
    {
        $str = preg_replace_callback('/\@([0-9a-zA-Z_]+)/', array(&$this,'callback_replace_mension'), $str);
        return $str;
    }
    
    function tweet_result_filter($str)
    {
        return tweet_result_filter($str);
    }
    
    function strimwidth($str, $num=80)
    {
        return mb_strimwidth($str, 0, $num, '...', 'UTF-8');
    }
    
    function count($str)
    {
        return count($str);
    }
    public function getName()
    {
        return 'sample';
    }
}
