<?php
require __DIR__ . '/../../vendor/autoload.php';

require_once 'bootstrap.php';

use Illuminate\Database\Capsule\Manager as DB;
use Carbon\Carbon;

// Query users
$stocks = DB::table('stocks')->get();

// Seed if empty
if($stocks->isEmpty()) {
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


print_r($users);


$option = $_GET['option'];

if ( $option == 1 ) {
    $data = [ 'a', 'b', 'c' ];
    // will encode to JSON array: ["a","b","c"]
    // accessed as example in JavaScript like: result[1] (returns "b")
} else {
    $data = [ 'name' => 'God', 'age' => -1 ];
    // will encode to JSON object: {"name":"God","age":-1}
    // accessed as example in JavaScript like: result.name or result['name'] (returns "God")
}

header('Content-type: application/json');
echo json_encode( $data );
