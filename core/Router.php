<?php
namespace Core;
use App\Controllers as Controller;

require_once __DIR__.'/../config/config.php';


/**
 * Class Router
 *
 * @package Core
 */
class Router{
    

    /**
   * URL Routing
   * @method route
   * @param  string     $url url of request
   */
    public static function route($url){
        //controller
        $controller = (isset($url[0]) && $url[0] != '') ? ucwords($url[0]).'Controller' : DEFAULT_CONTROLLER.'Controller';
        $controller_name = str_replace('Controller','',$controller);
        array_shift($url);
        
        //action
        $action = (isset($url[0]) && $url[0] != '') ? $url[0].'Action' : 'indexAction';
        $action_name = (isset($url[0]) && $url[0] != '') ? $url[0] : 'index';
        array_shift($url);
       
        //params
        $queryParams = $url;
        
        //instantiate a controller class object
        if($controller == "LibraryController"){
            $dispatch = new Controller\LibraryController($controller_name, $action);
        }else if($controller == "RegisterController"){
            $dispatch = new Controller\RegisterController($controller_name, $action);
        }else{
            $dispatch = new Controller\HomeController($controller_name, $action);
        }
        
        //call the action method of the instantiated class object
        if(method_exists($dispatch,$action)){
            call_user_func_array([$dispatch,$action],$queryParams);
        }else{
            die('That method does not exists in the controller \"' .$controller_name. '\"');
        }
    }
    
}
















