<?php
/**
* @author Marcelo Barbosa
*
*/
namespace Anotherwise\Bonus;

require_once(SRC."routes.php");

class Permission {
    public static function check($url){
        $guest_routes = Routes::guest();

        foreach ($guest_routes as $route => $value) {
            $route = ltrim(rtrim($route, '/'), '/');
            $pos = strpos($route, '{');

            if(!strcmp($url, $route)){
                return $guest_routes[$route];
            } else if($pos && !substr_compare($url, $route, 0, $pos)) {
                return $guest_routes[$route];
            }
		}
        if(isset($_SESSION['permissions']) && sizeof($_SESSION['permissions']) > 0){
            foreach ($_SESSION['permissions'] as $i => $permission) {
                if(@preg_match($permission['route'], $url) || $permission['route'] == $url){
    				return $permission['allowed'];
    			}
            }
        }
        return false;
    }
}
