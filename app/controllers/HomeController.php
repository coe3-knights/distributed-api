<?php
namespace App\Controllers;
use Core\Controller;

/**
 * Class HomeController
 *
 * @package App\Controllers
 */
class HomeController extends Controller{
    //HomeController constructor
    public function __construct($controller,$action){
        parent::__construct($controller,$action);
    }
    

   /**
   * default action to alert that the url lacks action string
   * @method indexAction
   */
    public function indexAction(){ 
      echo json_encode(array("messsage" => "Nothing passed to the API"));
    }
    
}