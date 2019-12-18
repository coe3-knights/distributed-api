<?php
namespace Core;


/**
 * Class Helper
 *
 * @package Core
 */
class Helper{
       /**
         Removes illegal characters from a string
       * @method sanitize
       * @param  string     $dirty  input string
       * @return string     returns clean string
       */
       public static function sanitize($dirty){
            return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
        }
       


       /**
          Sanitize user input
       * @method get
       * @param string       $input input
       * @return boolean     returns clean string
       */
  
        public static function get($input){
            return trim(self::sanitize($input));
          }
}