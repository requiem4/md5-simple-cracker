<?php

namespace core;
/**
 * Class Controller
 * @package core
 */
class Controller
{
	public function render($view, $params){
		$viewPath = __DIR__ .'/../views/' . $view . '.php';
		$layoutPath = __DIR__ .'/../views/layout.php';
		foreach ($params as $name => $param) {
			${$name} = $param;
		}
		if(isset($params['isAjax'])){
			return ['status' => $params['status']];
		}
		if(file_exists($viewPath)){
			ob_start();
			include($viewPath);
			$content=ob_get_contents();
			ob_end_clean();
			return include($layoutPath);
		} else {
			die('File ' . $viewPath .' doesn`t exists');
		}
	}
}
