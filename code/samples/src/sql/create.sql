-- Create database tables
-- ========================================

use rx_samples;

CREATE TABLE users ( 
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	firstname VARCHAR(30) NOT NULL, 
	lastname VARCHAR(30) NOT NULL, 
	email VARCHAR(50), 
	created_at DATETIME NOT NULL default NOW(), 
	updated_at DATETIME
);


CREATE TABLE accounts (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT UNSIGNED not null,
	account_type CHAR(10),        
	balance FLOAT(10,4) DEFAULT 0,
	created_at DATETIME NOT NULL default NOW(),
	updated_at DATETIME
);


CREATE TABLE transactions (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT UNSIGNED not null,
	account_id INT UNSIGNED not null,
	tx_type CHAR(10),        
	amount FLOAT(10,4) DEFAULT 0,
	created_at DATETIME NOT NULL default NOW(),
	updated_at DATETIME
);





