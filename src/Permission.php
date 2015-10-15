<?php
/**
* @author Marcelo Barbosa
*
*/
namespace Anotherwise\Bonus;

require_once(SRC."routes.php");

class Permission {
    public static function check($url){
        $routes = Routes::guest();
        foreach ($routes as $route => $array) {
			if(@preg_match($route, $url) || $route == $url){
				return $routes[$route];
			}
		}        
        if(isset($_SESSION['permissions']) && sizeof($_SESSION['permissions']) > 0){
            foreach ($_SESSION['permissions'] as $i => $value) {
                if(@preg_match($value['route'], $url) || $value['route'] == $url){
    				return $value['allow'];
    			}
            }
        }
        return false;
    }
}
