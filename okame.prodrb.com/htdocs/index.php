<?php

require_once dirname(dirname(__FILE__)) . '/system/common.inc.php';

echo $twig->fetch('_index.html');
exit;

