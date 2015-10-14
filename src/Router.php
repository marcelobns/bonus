<?php
/**
* @author Marcelo Barbosa
*/
namespace Anotherwise\Bonus;

require_once(SRC."routes.php");

class Router {
	function __construct(){
		session_start();
		spl_autoload_register(array($this, "autoloader"));
		$this->mapping(@$_GET['url']);
	}
	function autoloader($class){
	    $file = str_replace('\\', '/', $class).".php";
	    if (file_exists($file)) {
	        require_once($file);
	    }
	}
	function mapping($url){
		$map = $this->getRoute($url, Routes::map());
		$controller = ucfirst($map['controller']);
		$controller = MODULES.$controller;
		$error = MODULES."Errors";
		$action = @$map['action'];
		$param = isset($map['param']) ? $map['param'] : array();

		if(!Permission::check($url)){
			$page = new $error;
			$page->error403();
		} else if(class_exists($controller) && method_exists($controller, $action)) {
			$controller = new $controller;
			call_user_func_array(array($controller, $action), $param);
		} else {
			$page = new $error;
			$page->error404();
		}
	}

	function getRoute($url, $map) {
		foreach ($map as $route => $array) {
			if(preg_match($route, $url) || $route == $url){
				$pin = $route;
			}
		}
		if(isset($pin)){
			$url = explode('/', $url);
			foreach($map[$pin] as $key => $value) {
				if(is_numeric($value)){
					if($key == "action" && empty($url[$value])){
						@$url[$value] = "index";
					}
					$map[$pin][$key] = $url[$value];
					unset($url[$value]);
				}
				if(is_array($value)){
					foreach ($url as $i => $value) {
						if(in_array($value, $map[$pin])){
							unset($url[$i]);
						}
					}
					$map[$pin][$key] = $url;
				}
			}
			return $map[$pin];
		}
	}
}
