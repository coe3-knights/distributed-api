<?php
namespace App\Models;
use Core\Model;
use Core\Token;
use Core\DB;


/**
 * Class Books
 *
 * @package App\Models
 */
class Books extends Model{
    //Books Controller
    public function __construct(){
        $table = 'books';
        parent::__construct($table);

        $this->_softDelete = true;           
    }
     
    
    /**
   * Find the first Book by title
   * @method findByTitle
   * @param  string     $title title of book
   * @return object | false      returns book object or false if one is not found
   */
    public function findByTitle($title){
        return $this->findFirstBook(['conditions'=>'title = ?', 'bind'=>[$title]]);
    }
   

    /**
   * Upload a file into books table
   * @method uploadBook
   * @param  array     $params array of conditions and binds
   * @param  file      $file_val base64 encoded file
   * @return object | false      returns book object or false if one is not found
   */
    public function uploadBook($params){
        $this->assign($params);
        $this->save();
    }


    /**
   * Matches a query parameter to table entity of a book Object
   * @method bookSearch
   * @param  string      $query search query phrase
   * @return object | false      returns Model object or false if one is not found
   */
    public function bookSearch($phrase){
        return $this->search($phrase);
    }


    /**
   * Find the first Book by id
   * @method findById
   * @param  int     $id id of book
   * @return object | false      returns book object or false if one is not found
   */
    public function findById($id){
        return $this->findFirstBook(['conditions'=>'id = ?', 'bind'=>[$id]]);
    }

    
}
