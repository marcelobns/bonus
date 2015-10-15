<?php
/**
* @author Marcelo Barbosa
*
*/
namespace Anotherwise\Bonus;

class Controller {
	function __construct(){
		$this->view = new View();
	}
	function __call($method,$arguments) {
		if(method_exists($this->view, $method)){
			call_user_func_array(array($this->view, $method), $arguments);
		} else {
			throw new \Exception("Undefined method: $method()");
		}
	}
	function request($method){
		return (strtolower($_SERVER['REQUEST_METHOD']) == strtolower($method));
	}
	function redirect($link = ""){
		if(strpos($link, 'http') === false){
			$link = HREF.$link;
		}
		header("Location: $link");
	}
	function auth($user, $permissions = array()){
		$_SESSION['user'] = $user;
		$_SESSION['permissions'] = $permissions;
		return $_SESSION['user'];
	}
	function paginate($query, $limit = 15){
		$page = 1;
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}
		$offset = ($page-1)*$limit;
		$this->view->current_page = $page;
		$this->view->count_items = $query->count();
		$this->view->count_pages = ceil($this->view->count_items/$limit);
		return $query->skip($offset)->take($limit)->get();
	}
}
