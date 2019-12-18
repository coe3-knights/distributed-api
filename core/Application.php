<?php 
namespace Core;
require_once __DIR__.'/../config/config.php';


/**
 * Class Application
 *
 * @package Core
 */
class Application{
    
    //app constructor
    public function __construct(){
        $this->_set_reporting();
        $this->_unregister_globals();
    }
    

    /**
   * Set Error Reporting Mode
   * @method _set_reporting
   */
    private function _set_reporting(){
        if(DEBUG){
            error_reporting(E_ALL);
            ini_set('display_errors',1);
        }else{
               error_reporting(0);
               ini_set('display_errors',0);
               ini_set('log_errors',1);
               ini_set('error_log',ROOT . DS . 'tmp' . DS . 'logs' . DS . 'errors.log' );
        }
    }
    
    /**
   * Unregister all globals
   * @method _unregister_globals
   */
    private function _unregister_globals(){
        if(ini_get('register_globals')){
            $globalsArry = ['_SESSION','_COOKIE','_POST','_GET','_REQUEST','_SERVER','_ENV','_FILES'];
            foreach($globalsArry as $g){
                foreach($GLOBALS[$g] as $k =>$v){
                    if($GLOBALS[$k] === $v){
                        unset($GLOBALS[$k]);
                    }
                }
            }
        }
    }
}