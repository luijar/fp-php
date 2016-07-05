<?php namespace Model;

require_once 'Model.php';

class User extends Model {
	
	public $firstname;
	public $lastname;
	public $email;
		
	public function getFirstname() {
		return $this->firstname;
	}

	public function setFirstname($name) {
		$this->firstname = $name;
		return $this;	
	}

	public function getLastname() {
		return $this->lastname;
	}

	public function setLastname($name) {
		$this->lastname = $name;
		return $this;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}

	public function getTablename() {
		return 'users';
	}
}