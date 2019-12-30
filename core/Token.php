<?php
namespace Core;
use \Firebase\JWT\JWT;
use Core\DB;

require_once __DIR__.'/../config/config.php';


/**
 * Class Token
 *
 * @package Core
 */
class Token{

     /**
     * id of model object
     *
     * @var int
     */	
  protected $_id;


  /**
   * generates JWT token
   * @method generateToken
   * @param  $id  if of model object
   * @return string      token
   */
  public static function generateToken($id){
  	$payload = [
				'iat' => time(),
				'iss' => 'knights-library.herokuapp.com',
				'exp' => time() + (60*60),
				'userId' => $id
			  ];

	$token = JWT::encode($payload, SECRET_KEY);
  	return $token;
  }



  /**
   * checks JWT token expiration
   * @method tokenValidity
   * @return boolean      returns true or false
   */
  public  static function tokenValidity() {
	                     $token = self::getBearerToken();
	                     $payload = JWT::decode($token, SECRET_KEY, ['HS256']);
			   if($payload){
                               $db = DB::getInstance();
				$user = $db->findFirst('users',['conditions' => 'id = ?', 'bind' => [$payload->userId]]);
		
				if(is_null($user)){
					http_response_code(108);
					$error = array("error"=>"User not Found");
					echo json_encode($error);
					return false;
				}

			   return true;
			}else{
				http_response_code(302);
				$error = array("error"=>"Token Expired");
				echo json_encode($error);
			}
		}


 
 /**
   * gets Authorization Header
   * @method getAuthorizationHeader
   * @return array      returns array of headers
   */
  public static function getAuthorizationHeader(){
	        $headers = null;
	        if (isset($_SERVER['Authorization'])) {
	            $headers = trim($_SERVER["Authorization"]);
	        }
	        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
	            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	        } elseif (function_exists('apache_request_headers')) {
	            $requestHeaders = apache_request_headers();
	            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
	            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
	            if (isset($requestHeaders['Authorization'])) {
	                $headers = trim($requestHeaders['Authorization']);
	            }
	        }
	        return $headers;
	    }
	    


		    /**
	   * gets JWT Token from Authorization Header
	   * @method getBearerToken
	   * @return string      returns JWT token
	   */
	    public static function getBearerToken() {
	        $headers = self::getAuthorizationHeader();
	        // HEADER: Get the access token from the header
	        if (!empty($headers)) {
	            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
	                return $matches[1];
	            }
	        }
	        http_response_code(301);
	        $error = array("error"=>"Authorization Header Not Found");
	        echo json_encode($error);
	    }

}
