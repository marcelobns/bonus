<?php
/**
* @Author Marcelo Barbosa
* 2015-09-04
*/
namespace bonus;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Pagination\Paginator;

class Model extends Eloquent {
    function __construct(){
        new DatabaseCapsule();
    }

    function fill(array $attributes){
        parent::fill($attributes);
    }

    static function raw($conditions){
        foreach ($conditions as $key => $value) {
            $conditions[$key] = "'$value'";
        }
        return urldecode(http_build_query($conditions, '', ' and '));
    }
}
