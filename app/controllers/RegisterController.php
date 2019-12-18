<?php
namespace App\Controllers;
use Core\Controller;
use Core\Method;
use Core\Helper;
use App\Models\Users;



/**
 * Class RegisterController
 *
 * @package App\Controllers
 */
class RegisterController extends Controller{
    
    //RegisterController constructor
    public function __construct($controller, $action){
        parent::__construct($controller, $action);
    }
    

    /**
   * for user login
   * @method loginAction
   */
    public function loginAction(){ 
        //checks for user request method
        if(Method::isPost()){
            $register_vals = json_decode(file_get_contents('php://input'), true);
            $errors = [];
            foreach($register_vals as $key=>$val ){
                if(!empty($register_vals[".$key."])){
                    $errors[] = array("$key" => "Field cannot be empty");
                }
            }

            if(!empty($errors)){
                http_response_code(400);
                echo json_encode($errors);
            }else{
              //creates new Users Object
                $newUser = new Users();
                $user = $newUser->findByUserName($register_vals['username']);
                 $user = json_decode(json_encode($user),true);
                if($user && password_verify(Helper::get($register_vals['password']), $user['password'])){
                    $token = $newUser->login($user['id']);
                    http_response_code(200);
                    $success = array("message"=>"Login Successful",
                                     "token"=>$token);
                    echo json_encode($success);
                }else{
                    http_response_code(500);
                    $errors = array("message" => "Login Unsuccessful. Try again Later");
                    echo json_encode($errors);
                }
            }
         }
    }
    
    
       /**
       * for user registration
       * @method registerAction
       */
       public function registerAction(){
         //checks for user request method
         if(Method::isPost()){
            header("content-type: application/json");
            $register_vals = json_decode(file_get_contents('php://input'), true);
           
            if(!empty($register_vals)){
                $errors = [];
                foreach($register_vals as $key=>$val ){
                    if(empty($register_vals["$key"])){
                        $errors[] = array("$key" => "Field cannot be empty");
                    }
                }


                if(!empty($errors)){
                    http_response_code(400);
                    echo json_encode($errors);
                }

               //creates new Users Object
               $newUser = new Users();

               //checks if user already exists with that username or email
               $userByUsername = $newUser->findByUserName($register_vals['username']);
               $userByEmail = $newUser->findByEmail($register_vals['email']);

               $usernameFound = json_decode(json_encode($userByUsername),true);
               $emailFound = json_decode(json_encode($userByEmail),true);
               
               $usernameFound['username'] = strtolower($usernameFound['username']);
               $register_vals['username'] = strtolower($register_vals['username']);
               $userByFound['email'] = strtolower($userByFound['email']);
               $register_vals['email'] = strtolower($register_vals['email']);


               if($usernameFound['username'] == $register_vals['username']){
                $errors[] = array("error"=>"Username already exists");
               }

               if($emailFound['email'] == $register_vals['email']){
                $errors[] = array("error"=>"Email already exists");
               }
         

               if(!empty($errors)){
                http_response_code(400);
                  echo json_encode($errors);
               }else{
                    $newUser->registerNewUser($register_vals);
                    http_response_code(200);
                    $success = array("message"=>"User created successfully");
                    echo json_encode($success);
                }
         }else{
            echo json_encode(array("error" => "Key pair values not passed to API"));
         }
       }
    }
}