<?php
/**
* @author Marcelo Barbosa
*
*/
namespace Anotherwise\Bonus;

require_once(SRC."database.php");

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model as Eloquent;

class DatabaseCapsule extends Capsule {
    function con($schema){
        if(isset($schema)){
            $this->addConnection(Database::connection($schema));
        } else {
            $this->addConnection(Database::connection());
        }
        $this->setAsGlobal();
        $this->bootEloquent();
    }
}
