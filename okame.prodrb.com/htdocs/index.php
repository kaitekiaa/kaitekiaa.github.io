<?php

require_once dirname(dirname(__FILE__)) . '/system/common.inc.php';

var_dump('test');
echo $twig->fetch('_index.html');
exit;

