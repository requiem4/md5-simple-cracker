<?php

include_once 'App.php';
if(!isset($argv)){
	$argv = [];
}
App::bootstrap($argv);
