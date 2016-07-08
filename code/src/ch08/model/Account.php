<?php namespace Model;

require_once 'Model.php';

class Account extends Model {
		
	public $userId;
	public $account_type;
	public $balance;

	public function getUserId() {
		return $this->userId;
	}

	public function getType() {
		return $this->account_type;
	}

	public function getBalance() {
		return $this->balance;
	}

	public function getTablename() {
		return 'accounts';
	}
}