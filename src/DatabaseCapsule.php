<?php
/**
* @author Marcelo Barbosa
* 
*/
namespace bonus;
//TODO standalone SRC?
require_once(SRC."database.php");

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model as Eloquent;

class DatabaseCapsule extends Capsule {
    function __construct(){
        parent::__construct();
        $this->addConnection(Database::connection());
        $this->setAsGlobal();
        $this->bootEloquent();
    }
}
