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
    function __construct($schema){
        parent::__construct();
        $this->addConnection(Database::connection($schema));
        $this->setAsGlobal();
        $this->bootEloquent();
    }
}
