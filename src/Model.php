<?php
/**
* @author Marcelo Barbosa
*
*/
namespace Anotherwise\Bonus;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Pagination\Paginator;

class Model extends Eloquent {
    function __construct(){
        $capsule = new DatabaseCapsule();
        $capsule->con($this->schema);
    }
    function fill(array $attributes){
        parent::fill($attributes);
    }
    protected static function raw($conditions){
        foreach ($conditions as $key => $value) {
            $conditions[$key] = "'$value'";
        }
        return urldecode(http_build_query($conditions, '', ' and '));
    }    
}
