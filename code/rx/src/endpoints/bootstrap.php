<?php
/**
 * Bootstrap database models and connection
 * @author luijar
 */
use Illuminate\Database\Capsule\Manager as DB;

$capsule = new DB();

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'rx_samples',
    'username'  => 'root',
    'password'  => 'secret',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
