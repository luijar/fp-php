<?php namespace Model;

trait HiddenFields {
			
	// Database connection
	private $_db;

	// Hidden (not exposed) fields
	private $_hidden = ['id', '_db'];
}