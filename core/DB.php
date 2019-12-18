<?php
namespace Core; 
use \PDO;


/**
 * Class DB
 *
 * @package Core
 */
class DB{
    
    /**
     * DB instance object
     *
     * @var object
     */
    private static $_instance = null;


    /**
     * PDO instance object
     *
     * @var object
     */
    private $_pdo;


    /**
     * database query
     *
     * @var object
     */
    private $_query;


    /**
     * errors
     *
     * @var boolean
     */
    private $_error =false;

    /**
     * query result object
     *
     * @var object
     */
    private $_result;

    /**
     * number of result set returned
     *
     * @var int
     */
    private $_count =0;

    /**
     * id of last item insterted into the Model table
     *
     * @var int
     */
    private $_lastInsertedID=null;


    //DB constructor
    private function __construct(){
         
        try{
            
            $this->_pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USER,DB_PASSWORD);
            
        }catch(PDOException $e){
         die($e->getMessage());   
        }
    }
    

    
      /**
   * Gets DB instance
   * @method getInstance
   * @return object | false      returns BD object or false
   */
    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance = new DB();
        }
        
        return self::$_instance;
    }
    


      /**
   * Upload Book
   * @method uploadBook
   * @param  array     $params array of book properties
   * @param  file      $file_val uploaded file
   * @return object | false      returns query object or false if one is not found
   */
   public function uploadBook($params, $file_val){
        $file_val = base64_encode($file_val);

        $query = $this->_pdo->prepare("INSERT INTO books VALUES('',?,?,?,?,?)");
        $query->bindParam(1,$params['title']);
        $query->bindParam(2,$params['author']);
        $query->bindParam(3,$params['category']);
        $query->bindParam(4,$params['description']);
        $query->bindParam(5,$file_val);

        $query->execute();
        
        return $query;

    }


     
     /**
      query method
   * @method query
   * @param  string    $params query string
   * @param  array     $params array of binds
   * @return object | false      returns query object or false if one is not found
   */
    public function query($sql, $params = []){
        $this->_error = false;

        if($this->_query = $this->_pdo->prepare($sql)){
            $x=1;
            if(count($params)){
                foreach($params as $param){
                    $this->_query->bindValue($x,$param);
                    
                    $x++;
              }
            }  
           
            if($this->_query->execute()){
                $this->_result = $this->_query->fetchALL(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
                $this->_lastInsertedID = $this->_pdo->lastInsertId();
            }else{
                $this->_error = true;
            }
        } 
        
        return $this;
    }

    
    
    /**
      insert method
   * @method insert
   * @param  string    $table model object table name
   * @param  array     $fields array of column names
   * @return boolean   returns true or false
   */
    public function insert($table, $fields = []){
        $fieldString = '';
        $valueString = '';
        $values = [];
        
        foreach($fields as $field => $value){
            $fieldString .= '`' . $field . '`,';
            $valueString .= '?,';
            $values[] = $value;
        } 
        
        $fieldString = rtrim($fieldString,',');
        $valueString = rtrim($valueString,',');
        
        $sql = "INSERT INTO {$table} ({$fieldString}) VALUES ({$valueString})";
        if($this->query($sql, $values)->error()){
            return true;
        }else{
            return false;
        }
        
        }
    
    
    /**
      read data sets from Model Object Table
   * @method _read
   * @param  string    $params model Object Table name
   * @param  array     $params array of conditions and binds
   * @return boolean   returns true or false
   */
    protected function _read($table, $params = []){
        $conditionString = '';
        $bind = [];
        $order = '';
        $limit = '';
        
        //conditions
        if(isset($params['conditions'])){
            if(is_array($params['conditions'])){
                foreach($params['conditions'] as $condition){
                    $conditionString .= ' ' . $condition . ' AND';
                }
                
                $conditionString = trim($conditionString);
                $conditionString = rtrim($conditionString, ' AND');
            }else{
                 $conditionString = $params['conditions'];
            }
            
            if($conditionString != ''){
                $conditionString = ' WHERE '. $conditionString;
            }
            
            
            //bind
            if(array_key_exists('bind',$params)){
                $bind = $params['bind'];
            }
            
            
            //order
            if(array_key_exists('order',$params)){
                $order = ' ORDER BY ' . $params['order'];
            }
            
            
            //limit
            if(array_key_exists('limit',$params)){
                $limit = ' LIMIT ' . $params['limit'];
            }
            
            $sql = "SELECT * FROM {$table}{$conditionString}{$order}{$limit}";
            if($this->query($sql, $bind)){
                if(!count($this->_result)) return false;
                return true;
            }
        }
    }
    


    /**
   * Find a result set
   * @method find
   * @param  string  $table model object table name
   * @param  array   $params conditions of binds
   * @return array          array of rows or an empty array if none found
   */
    public function find($table, $params = []){
        if($this->_read($table, $params)){
            return $this->result();
        }
        return false;
    }
    

    /**
     search
   * @method ftSearch
   * @param  string    $table object model table name
   * @param  string     $phrase query string
   * @return object | false      returns query object or false if one is not found
   */

    public function ftSearch($table,$phrase){
     
     $sql = "SELECT * FROM {$table} WHERE title LIKE '%$phrase%' OR author LIKE '%$phrase%' OR description LIKE '%$phrase%'";
     $this->_query = $this->_pdo->prepare($sql); 

          if($this->_query->execute()){
              $this->_result = $this->_query->fetchALL(PDO::FETCH_OBJ);
            }else{
                $this->_error = true;
            }
          return $this->_result;

    }
    

    
    /**
   * Find the first object that matches the conditions
   * @method findFirst
   * @param  array     $params array of conditions and binds
   * @return object | false      returns Model object or false if one is not found
   */

    public function findFirst($table, $params = []){
       if($this->_read($table, $params)){
           return $this->first();
       }
        return false;
    }
    
    
    /**
      update a row in a table 
   * @method update
   * @param  string    $table model object table name
   * @param  int       $id id of the row
   * @param  array       $fields table fileds to update
   * @return boolean     returns true or false
   */
    public function update($table, $id, $fields = []){
       $fieldString = '';
       $values = [];
        foreach($fields as $field => $value){
            $fieldString .= ' ' . $field . ' = ?,';
            $values[] = $value;
        }
        
        $fieldString = trim($fieldString);
        $fieldString = rtrim($fieldString,',');
        
        $sql = "UPDATE {$table} SET {$fieldString} WHERE id = {$id}";
        
        if(!$this->query($sql,$values)->error()){
            return true;
        }else{
            return false;
        }
    }
    

    /**
      delete a row in a table 
   * @method delete
   * @param  string    $table model object table name
   * @param  int       $id id of the row
   * @return boolean     returns true or false
   */
    public function delete($table, $id){
        $sql = "DELETE FROM {$table} WHERE id = {$id}";
        
        if(!$this->query($sql)->error()){
            return true;
        }else{
            return false;
        }
    }
    

    /**
      update a row in a table 
   * @method result
   * @return object     returns result of a query
   */
    public function result(){
        return $this->_result;
    }
    

    /**
      gets first row of a query result set
   * @method first
   * @return object | array     returns query object or empty array
   */
    public function first(){
        return (!empty($this->_result)) ? $this->_result[0] : [];
    }
    

    /**
      gets number of results set queried
   * @method count
   * @return int    returns the value of the number of results set
   */
    public function count(){
        return $this->_count;
    }
    
/**
      get columns names of a Model Object
   * @method update
   * @param  string    $table model object table name
   * @return object     returns colmun names
   */
    public function get_columns($table){
        return $this->query("SHOW COLUMNS FROM {$table}")->result();
    }
    

    /**
     gets the id of the last item inserted
   * @method lastID
   * @return int      returns id of the last inserted element.
   */
    public function lastID(){
        return $this->_lastInsertedID;
    }

    /**
      Error indicator
   * @method error
   * @return boolean     returns true or false
   */
    public function error(){
            return $this->_error;
    }
}