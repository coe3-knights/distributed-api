<?php
namespace App\Models;
use Core\Model;
use Core\Token;

/**
 * Class Users
 *
 * @package App\Models
 */
class Users extends Model{
  
    //Users Controller    
    public function __construct($user=''){
        $table = 'users';
        parent::__construct($table);
            
    }
    

    /**
   * Find the user by username
   * @method findByUserName
   * @param  string     $username username of user
   * @return object | false      returns user object or false if one is not found
   */
    public function findByUserName($username){
        return $this->findFirstUser(['conditions'=>'username = ?', 'bind'=>[$username]]);
    }


     /**
   * Find the user by email
   * @method findByEmail
   * @param  string     $email email of user
   * @return object | false      returns user object or false if one is not found
   */    public function findByEmail($email){
        return $this->findFirstEmail(['conditions'=>'email = ?', 'bind'=>[$email]]);
    }


     /**
   * User login
   * @method login
   * @param  int     $id  id of user
   * @return string      returns JWT token for request authentication
   */
    public function login($id){        
        $token = Token::generateToken($id);
        return $token;
    }


    /**
   * Register New User
   * @method registerNewUser
   * @param  array     $params  array of conditions and binds
   */
    public function registerNewUser($params){
        $this->assign($params);
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->save();
    }    
}