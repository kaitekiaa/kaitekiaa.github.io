<?php
/**
 * 例外用クラス。
 */
class MyException extends exception
{
    protected $mes;
     
     
    public function __construct($mes = '')
    {
        $this->mes = $mes;
    }


    public function message()
    {
        return $this->mes;
    }
}



?>
