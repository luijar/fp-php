<?php
/**
 * Write seed user data to database and expose service endpoint
 * @author luijar
 */
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__. '/../bootstrap.php';

use Illuminate\Database\Capsule\Manager as DB;
use Carbon\Carbon;

// Query users
$users = DB::table('users')->get();

// Seed if empty
if($users->isEmpty()) {
    DB::table('users')->insert([
      'firstname'  => 'Joe',
      'lastname'   => 'Doe',
      'email'      => 'joe.doe@functionalphp.com',
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now()
    ]);

    DB::table('users')->insert([
      'firstname'  => 'Luis',
      'lastname'   => 'Atencio',
      'email'      => 'luis.atencio@functionalphp.com',
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now()
    ]);

    DB::table('users')->insert([
      'firstname'  => 'Ana',
      'lastname'   => 'Gonzalez',
      'email'      => 'ana.gonzalez@functionalphp.com',
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now()
    ]);

    DB::table('users')->insert([
      'firstname'  => 'Luke',
      'lastname'   => 'Atencio',
      'email'      => 'luke.atencio@functionalphp.com',
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now()
    ]);
}
header("HTTP/1.1 200 OK");
header('Content-type: application/json');
echo json_encode($users);
