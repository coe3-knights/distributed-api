<?php
namespace Core;
use Core\Application;
use App\Models;


/**
 * Class Controller
 *
 * @package Core
 */
class Controller extends Application{

    /**
     * controller name
     *
     * @var string
     */
    protected $_controller;

    /**
     * action name
     *
     * @var string
     */
    protected $_action;
    
    //controller constructor
    public function __construct($controller,$action){
        parent::__construct();
        $this->_controller = $controller; 
        $this->_action = $action;
    }
    
    /**
   * Load Object Model
   * @method load_model
   * @param  string  $params model name
   */
    protected function load_model($model){
        if(class_exists($model)){
            $this->{$model.'Model'} = new $model(strtolower($model));
        }
    }
}