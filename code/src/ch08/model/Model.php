<?php namespace Model;

abstract class Model {
	
	public $id;
	public $createdAt;
	public $updateAt;

	private $_db;

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
		$this->db();
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