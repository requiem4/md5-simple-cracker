<?php

use controllers\MainController;
use core\SimpleOrm;
class App {
	public static function bootstrap($argv){
		$config = include 'config/config.php';
		self::loadFiles();
		self::setUpDatabase($config);
		return self::loadController($argv);
	}
	private static function loadFiles(){
		spl_autoload_register(function($className) {
			$className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
			include_once __DIR__ . '/' . $className . '.php';
		});
	}
	private static function setUpDatabase($config){
		if(!isset($config['db'])){
			die(printf('Wrong database configuration. "db" key doesn`t exists'));
		}
		$database = $config['db'];
		if(!isset($database['host'])
			|| !isset($database['username'])
			|| !isset($database['password'])){
			die(printf('Check database properties'));
		}
		$conn = new mysqli($database['host'], $database['username'], $database['password']);

		if ($conn->connect_error)
			die(sprintf('Unable to connect to the database. %s', $conn->connect_error));

		SimpleOrm::useConnection($conn, $database['database']);
	}
	private static function loadController($argv){
		$controller = new MainController();
		if(php_sapi_name() === 'cli'){
			if(isset($argv[2]) && $argv[2] === 'hard'){
				return $controller->runHardDecode();
			} else {
				return $controller->runDecode();
			}
		}
		if(!empty($_POST['action']) && method_exists($controller,$_POST['action'])){
			return call_user_func(array($controller, $_POST['action']));
		}
		return $controller->index();
	}
}
