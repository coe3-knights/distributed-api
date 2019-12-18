<?php
namespace Core;


/**
 * Class Method
 *
 * @package Core
 */
class Method {

 /**
   Checks if request method is POST
 * @method isPost
 * @return string     returns request method
 */
  public static function isPost(){
    return self::getRequestMethod() === 'POST';
  }


  /**
    Checks if request method is PUT
   * @method isPut
   * @return string     returns request method
   */
  public static function isPut(){
    return self::getRequestMethod() === 'PUT';
  }


  /**
     Checks if request method is GET
   * @method isGet
   * @return string     returns request method
   */
  public static function isGet(){
    return self::getRequestMethod() === 'GET';
  }


  /**
     Get request method
   * @method getRequestMethod
   * @return boolean     returns request method
   */
  public static function getRequestMethod(){
    return strtoupper($_SERVER['REQUEST_METHOD']);
  }

}
