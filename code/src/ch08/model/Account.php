<?php namespace Model;

require_once 'Model.php';

class Account extends Model {
		
	public $userId;
	public $type;
	public $balance;

	public function getUserId() {
		return $this->userId;
	}

	public function getType() {
		return $this->typeId;
	}

	public function getBalance() {
		return $this->balance;
	}

	public function getTablename() {
		return 'accounts';
	}
}