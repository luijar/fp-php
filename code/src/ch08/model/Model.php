<?php namespace Model;

require_once 'Model.php';

abstract class Model {

	// Shared model fields
	public $id;	

	// Database connection
	private $_db;

	// ---- Model surface ---- //
	public function getId() {
		return $this->id;	
	}

	public abstract function getTablename();


	public static function find($id) {
		if(empty($id)) {
			throw new \InvalidArgumentException('Expected valid ID value');
		}		

		$clazz = get_called_class();
		$prototype = new $clazz();
		$sql = "SELECT * FROM {$prototype->getTablename()} WHERE id = {$id}";

		$db = static::_connect();   		
		if(!$result = $db->query($sql)) {
		    die('There was an error running the query [' . $db->error . ']');
		}

		while($record = $result->fetch_assoc()) {
			$instance = new $clazz();
			foreach ($result->fetch_fields() as $field) {				
				if(property_exists($clazz, $field->name)) {										
					$instance->{$field->name} = $record[$field->name];
				}								
			}
			$instance->_db = $db;					
		}

		$result->free();

		return $instance;		
	}

	
	public function save() {		
		if(empty($this->id)) {
			throw new \RuntimeException('Cannot save a transient object');
		}	

		$db = $this->db();

		// Hidden (not exposed) fields
		$hidden = ['id', '_db'];

		$setClause = array();
		$columnValues = array();
		foreach(get_object_vars($this) as $prop => $val) {			
			if(!empty($val) && !in_array($prop, $hidden)) {
				$setClause[] = "{$prop}=?";				
				$columnValues[] = $val;
			}			
		}

		$setClause = implode(',', $setClause);
		$updateStmt = $db->prepare("UPDATE {$this->getTablename()} SET {$setClause} WHERE id = {$this->getId()}");						

		// Call bind_param with a dynamic array
		array_unshift($columnValues, str_repeat('s', count($columnValues)));		
		call_user_func_array(array($updateStmt, 'bind_param'), $this->_refValues($columnValues));
		$updateStmt->execute();	
		return $this;
	}

	public static function all() {		
		$clazz = get_called_class();
		$prototype = new $clazz();
		$sql = "SELECT * FROM {$prototype->getTablename()}";
		    		
		$db = static::_connect();   		
		if(!$result = $db->query($sql)) {
		    die('There was an error running the query [' . $db->error . ']');
		}

		$instances = array_fill(0, $result->num_rows, null);	

		$count = 0;
		while($record = $result->fetch_assoc()) {
			$instance = new $clazz();
			foreach ($result->fetch_fields() as $field) {				
				if(property_exists($clazz, $field->name)) {										
					$instance->{$field->name} = $record[$field->name];
				}								
			}		
			$instance->_db = $db;	
			$instances[$count++] = $instance;
		}

		$result->free();
		return $instances;
	}

	// ---- Internal ---- //
	private function _refValues($arr){
	    $refs = array();
        foreach($arr as $key => $value) {
        	$refs[$key] = &$arr[$key];
        }
            
        return $refs;
	}

	private function db() {
		if(empty($this->_db)) {
			$this->_db = static::_connect();				
		}
		return $this->_db;		
	}

	private static function _connect() {
		$db = new \mysqli("localhost", "root", "secret", "rx_samples");
		if ($db->connect_errno) {
	    	echo "Failed to connect to MySQL: ({$mysqli->connect_errno})  {$mysqli->connect_error}";
		}		
		return $db;
	}
}