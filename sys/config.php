<?php
error_reporting(0);
	function getClass($class) {
	   include_once '/class/'.$class.'.class.php';
	}
    spl_autoload_register('getClass');
?>