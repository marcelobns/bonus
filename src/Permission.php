<?php
/**
* @author Marcelo Barbosa
*
*/
namespace Anotherwise\Bonus;

require_once(SRC."routes.php");

class Permission {
    public static function check($url){
<<<<<<< HEAD
        $routes = Routes::guest();
        foreach ($routes as $route => $array) {
			if(@preg_match($route, $url) || $route == $url){
				return $routes[$route];
			}
		}
        if(isset($_SESSION['permissions']) && sizeof($_SESSION['permissions']) > 0){
=======
    	if(isset($_SESSION['permissions']) && sizeof($_SESSION['permissions']) > 0){
>>>>>>> 8c23afd94e943cdfba10584d7bce7b2fa1d92e01
            foreach ($_SESSION['permissions'] as $i => $value) {
                if(@preg_match($value['route'], $url) || $value['route'] == $url){
			return $value['allow'];
		}
            }
        }
    	
        $routes = Routes::guest();
        foreach ($routes as $route => $array) {
		if(@preg_match($route, $url) || $route == $url){
			return $routes[$route];
		}
	}        
        return false;
    }
}
