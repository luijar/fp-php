<?php
/**
 * Write seed account data to database and expose service endpoint
 * @author luijar
 */
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__. '/../bootstrap.php';

use Illuminate\Database\Capsule\Manager as DB;
use Carbon\Carbon;

// Query transactions
if(isset($_GET['id'])) {
    $accounts = DB::table('accounts')->where('user_id', '=', $_GET['id'])->get();
}
else {
    $accounts = DB::table('accounts')->get();
}

if($accounts->isEmpty()) {
    DB::table('accounts')->insert([
      'user_id'       => 2,
      'account_type'  => 'CHECKING',
      'balance'       =>  2310.4004,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now()
    ]);

    DB::table('accounts')->insert([
      'user_id'       => 2,
      'account_type'  => 'SAVINGS',
      'balance'       =>  5000.00,
      'created_at' => Carbon::now(),
      'updated_at' => Carbon::now()
    ]);
}

header('Content-type: application/json');
echo json_encode($accounts);
