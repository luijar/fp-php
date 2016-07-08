<?php namespace Model;

require_once 'Model.php';

abstract class Model {

	// Shared model fields
	public $id;
	public $created_at;
	public $update_at;

	// Database connection
	private $_db;

	// Hidden (not exposed) fields
	private $_hidden = ['id', '_db', '_hidden'];

	// ---- Model surface ---- //
	public function getId() {
		return $this->id;	
	}

	public function getCreatedAt() {
		return $this->createdAt;
	}

	public function getUpdatedAt() {
		return $this->updateAt;
	}

	public abstract function getTablename();


	// ---- Internal ---- //
	public function save() {		
		if(empty($this->id)) {
			throw new \RuntimeException('Cannot save a transient object');
		}	

		$db = $this->db();

		$columnValues = [];
		foreach(get_object_vars($this) as $prop => $val) {
			if(empty($val)) {
				continue;
			}
			print('Prop'. $prop . '  value  '. $value);			
			if(!in_array($prop, $this->_hidden)) {
				$columnValues[] = "{$prop}={$val}";
			}			
		}

		print_r($columnValues);

		$values = implode(',', $columnValues);
		$updateStmt = $db->prepare("UPDATE {$this->getTablename()} SET {$values} WHERE id = {$this->getId()}");
		// $name = 'Bob';
		// $updateStmt->bind_param('s', $name);
		// $updateStmt->execute();
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
			$instances[$count++] = $instance;
		}

		$result->free();
		return $instances;
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
		else {
			echo "Connected to : {$db->host_info} \n";
		}
		return $db;
	}
}