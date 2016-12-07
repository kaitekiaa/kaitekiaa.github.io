<?php


class PDODatabase
{


    static protected $_ins = array();
    public static function getInstance($dbname=DBNAME, $host=DBHOST, $user=DBUSER, $pass=DBPASS)
    {
        $cache_id = md5($dbname. $host. $user. $pass);
        
        
        if (!isset(self::$_ins[$cache_id])) {
            self::$_ins[$cache_id] = new self($dbname, $host, $user, $pass);
        }
        return self::$_ins[$cache_id];
    }
    function __construct($dbname, $host,$user,$pass)
    {
        $this->dbname = $dbname;
        $this->dsn = "mysql:dbname=".$dbname.";host=".$host;
	try {
		$this->dbh = new PDO($this->dsn, $user, $pass);
	} catch (PDOException $e){
		var_dump($e);exit;
		echo '<!--' . print_r($e->getMessage(), TRUE) . '-->'; 
	}

        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return ;
    }

    public function query2($sql, $values=array())
    {
        try {
            //$this->dbh->query($sql);
            $stmt = $this->dbh->prepare($sql);
            if (is_array($values) and count($values)>0) {
                $stmt->execute($values);
            } else {
                $stmt->execute();
            }
            return $stmt;
        } catch (PDOException $e) {
            echo '<!--' . var_dump($e->getMessage()) . '-->';
            return false;
        }

    }
    public function query($sql, $params = array())
    {
        try {
          $stmt = $this->dbh->prepare($sql);
          if (is_array($params) && count($params) > 0) {
            foreach ($params as $key => $val) {
              if (preg_match("/^[a-zA-Z0-9]+$/", $key) && strpos($sql, ":{$key}") !== false) {
                $stmt->bindParam(":{$key}", $val);
              }
            }
          }
        //      $stmt->execute();
          $stmt->execute($params);
          return $stmt;
        } catch (PDOException $e) {
	
          echo '<!--' . print_r($e->getMessage(), TRUE) . '-->';
          return FALSE;
        }
    }

    public function fetch($stmt)
    {
        try {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo '<!--' . var_dump($e->getMessage()) . '-->';
            return false;
        }
    }

    //互換性のためのメソッド（利用非推奨）
    function escape($str)
    {
        return mysql_escape_string($str);//
        return $this->dbh->quote($str);
    }
    function quote($str)
    {
        return $this->dbh->quote($str);
    }
    function insert_id()
    {
        return $this->dbh->lastInsertId();
    }
}
