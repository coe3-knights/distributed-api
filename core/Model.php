<?php
namespace Core;
use Core\DB;
use Core\Helper;
use App\Models\Users;
use App\Models\Books;


/**
 * Class Model
 *
 * @package Core
 */
class Model{
  
    /**
     * PDO object instance
     *
     * @var object
     */
    protected $_db;


    /**
     * Object Model Table name
     *
     * @var string
     */
    protected $_table;

    /**
     * Object Model Name
     *
     * @var string
     */
    protected $_modelName;

    /**
     * For soft delete or permanent delete 
     *
     * @var Boolean
     */
    protected $_softDelete = false;

    /**
     * Column Names of a Model Object
     *
     * @var array
     */
    protected $_columnNames = [];

    /**
     * id value of Model Object
     *
     * @var int
     */
    public $id;

    
    //model constructor
    public function __construct($table){
        $this->_db = DB::getInstance();
        $this->_table = $table;
        $this->_setTableColumns();
        $this->_modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->_table)));
    }
    
    /**
   * set the column tables for a Model Object
   * @method _setTableColumns
   */
    protected function _setTableColumns(){
        $columns = $this->get_columns();
        
        foreach($columns as $column){
            $columnName = $column->Field;
            $this->_columnNames[] = $column->Field;
            $this->{$columnName} = null;
        }
    }
    
     /**
   * query database table for model to get column information
   * @method get_columns
   * @return object      columns object
   */
    public function get_columns(){
        return $this->_db->get_columns($this->_table);
    }

    
    /**
   * Find a result set
   * @method find
   * @param  array  $params conditions
   * @return array          array of rows or an empty array if none found
   */
    public function find($params = []){
        $results = [];
        $resultsQuery = $this->_db->find($this->_table, $params);

        foreach($resultsQuery as $result){
            $obj = new $this->_modelName($this->_table);
            if($resultsQuery){
            $obj->populatedObjData($result);  
            }
           
            $results[] = $obj;
            } 
            
           return $results;
        }

       /**
   * Find the first object that matches the conditions
   * @method findFirst
   * @param  array     $params array of conditions and binds
   * @return object | false      returns Model object or false if one is not found
   */   
    public function findFirst($params = []){
       $resultQuery = $this->_db->findFirst($this->_table, $params);
       $result = new $this->_modelName($this->_table);
        
       if($resultQuery){
           $result->populatedObjData($resultQuery);
       }
        
        
       return $result;
   }

   /**
   * Find the first User object that matches a request
   * @method findFirstUser
   * @param  array     $params array of conditions and binds
   * @return object | false      returns Model object or false if one is not found
   */
   public function findFirstUser($params = []){
       $resultQuery = $this->_db->findFirst($this->_table, $params);
       $result = new Users($this->_table);
        
       if($resultQuery){
           $result->populatedObjData($resultQuery);
       }
        
        
       return $result;
   }


   /**
   * Find the first Book object that matches the request
   * @method findFirstBook
   * @param  array     $params array of conditions and binds
   * @return object | false      returns Model object or false if one is not found
   */
   public function findFirstBook($params = []){
       $resultQuery = $this->_db->findFirst($this->_table, $params);
       $result = new Books($this->_table);
        
       if($resultQuery){
           $result->populatedObjData($resultQuery);
       }
        
        
       return $result;
   }
     
     /**
   * Upload a file into the database table of a Model Object
   * @method uploadFile
   * @param  array     $params array of conditions and binds
   * @param  file      $file_val base64 encoded file
   * @return object | false      returns Model object or false if one is not found
   */
    public function uploadFile($params){
      $resultsQuery = $this->_db->uploadBook($params);
      return $resultsQuery;
    }

     
      /**
   * Matches a query parameter to table entity of a Model Object
   * @method search
   * @param  string      $query search query phrase
   * @return object | false      returns Model object or false if one is not found
   */
     public function search($query){
       $resultQuery = $this->_db->ftSearch($this->_table,$query);
       return $resultQuery;
   }
    

       /**
   * Assigning values to keys of a Model Object
   * @method populatedObjData
   * @param  object      $result result of a Model Object 
   */
      protected function populatedObjData($result){
          foreach($result as $key => $val){
             $this->$key = $val;  
            
        }
      }

    
    /**
   * Finds a row for this model by id
   * @method findById
   * @param  integer   $id id of the object to return
   * @return object        Model Object
   */
   public function findById($id){
       return $this->findFirst(['conditions' => 'id = ?', 'bind' => [$id]]);
   }
    
    
    /**
   * Save the current properties to the database
   * @method save
   * @return boolean
   */
    public function save(){
        $fields = [];
        
        foreach($this->_columnNames as $column){
            $fields[$column] = $this->$column;
        }
        

        //determine whether to update or to insert
        if(property_exists($this, 'id') && $this->id != ''){
            return $this->update($this->id, $fields);
        }else{
            return $this->insert($fields);
        }
    }
    

    /**
   * Insert a row into the database
   * @method insert
   * @param  array $fields associative array ['field'=>'value']
   * @return boolean       returns if the insert was successful
   */
    public function insert($fields){
        if(empty($fields)) return false;
        return $this->_db->insert($this->_table, $fields);
    }
    

    /**
   * Update a row in the database
   * @method update
   * @param  array $fields associative array of fields to update ['field'=>'value']
   * @return boolean       if the update was successful
   */
    public function update($fields){
        if(empty($fields) || $id == '') return false;
        return $this->_db->update($this->_table, $id, $fields);
    }

    
  /**
   * Delete a row in the database, could also be soft delete
   * @method delete
   * @return boolean      [description]
   */
    public function delete($id = ''){
        if($id == '' && $this->id == '') return false;
        $id = ($id == '') ? $this->id : $id; 
        
        if($this->_softDelete){
            return $this->update($id, ['deleted' => 1]);
        }
      
        return $this->_db->delete($this->_table, $id);
    }
    
  
    /**
   * Used to run a manual query on this model's table
   * @method query
   * @param  [type] $sql  [description]
   * @param  array  $bind [description]
   * @return [type]       [description]
   */  
    public function query($sql, $bind = []){
        return $this->_db->query($sql, $bind);
    }  
    
    
    /**
   * Returns an object with only the properties set. Removes all methods. Can be used to save memory for large datasets.
   * @method data
   * @return object
   */
    public function data(){
        $data = new stdClass();
        
        foreach($this->_columnNames as $column){
            $data->column = $this->column;
        }
    }
    
    
    /**
   * Update the object with an associative array
   * @method assign
   * @param  array  $param assigning values to column fields to be used for database storage
   * @return object          returns a model object allows for chaining.
   */
    public function assign($params){
      if(!empty($params)){
          foreach($params as $key => $val){
              if(in_array($key, $this->_columnNames)){
                  $this->$key = Helper::sanitize($val);
              }

          }
      }
    }
    
    


 
    
}
