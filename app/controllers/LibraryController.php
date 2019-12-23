<?php
namespace App\Controllers;
use Core\Controller;
use Core\Method;
use Core\Token;
use App\Models\Books;
use \PDO;


/**
 * Class LibraryController
 *
 * @package App\Controllers
 */
class LibraryController extends Controller{
    //LibraryController constructor
    public function __construct($controller, $action){
        parent::__construct($controller, $action);
    }
    
    /**
   * for file upload
   * @method uploadAction
   */
    public function uploadAction(){ 
    	//checks for user request method
	    
        if(Method::isPost()){
              $register_vals = $_POST;
	       var_dump($_POST); die();
	     
              $file_type = $_FILES['file']['type'];
              $file_val = file_get_contents($_FILES['file']['tmp_name']);
              $register_vals['data'] = base64_encode($file_val);
              
              //check token expiration to authenticate user
              if(Token::tokenValidity()){
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
	                }else{
	                	//creates new Books Object
	                	$newBook = new Books();
	                    $newBook->uploadBook($register_vals);
	                    http_response_code(200);
	                    $success = array("message"=>"Book uploaded successfully");
	                    echo json_encode($success);
                    }
              }else{
	              http_response_code(400);
	              echo json_encode(array("error" => "Key pair values not passed to API"));
               }
            }else{
		     	http_response_code(300);
		     	$error = array("error"=>"Token Expired, Please Login!");
		     	echo json_encode($error);
		     }
           }
        }

    /**
   * for retrieving books from book table
   * @method booksAction
   */
    public function booksAction($id=''){
    	//checks for user request method
    	if(Method::isGet()){
              $register_vals = json_decode(file_get_contents('php://input'), true);


               $books = new Books();
			        if($id != ''){
	                  $id = (int)$id;
	                  $bookById = $books->findById($id);

		                  if($bookById->id){
			                   http_response_code(200);
	                           echo json_encode($bookById );
		                  }else{
                               http_response_code(400);
	                           echo json_encode(array("error"=>"No book found"));
		                  }

			        }else{
			         //PDO initilized here to take care of limited memory size
			         $db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USER,DB_PASSWORD);
			          $sth = $db->prepare("SELECT id,title,author,description FROM books");
					      $sth->execute();
					      $results = $sth->fetchALL(PDO::FETCH_ASSOC);
                      
                      http_response_code(200);
					     echo json_encode($results);
			        
			        }
	    }
    }


    /**
   * for book search
   * @method searchAction
   */
    public function searchAction($phrase){
    	//checks for user request Method
    	if(Method::isGet()){
              $register_vals = json_decode(file_get_contents('php://input'), true);

              	      //creates new Books Object
                      $books = new Books();
                      $result = $books->bookSearch($phrase);
                      
                      if(!empty($result) && !is_null($result)){
                      	 http_response_code(200);
	                     echo json_encode($result);
                      }else{
                      	 http_response_code(400);
                      	 $error = array("error" => "No result found");
	                     echo json_encode($error);
                      }


        }

}

}
