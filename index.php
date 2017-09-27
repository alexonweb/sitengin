<?php

// Отключаем ошибки
error_reporting(0);
ini_set('display_errors', 0);


// Подгружаем class Site
// Directory separator может отличатся от ОС
require_once inc . DIRECTORY_SEPARATOR . 'Site.php';

$Site =& new Site;

$Site;

?>