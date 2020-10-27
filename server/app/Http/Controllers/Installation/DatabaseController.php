<?php

namespace App\Http\Controllers\Installation;

use ConfigWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class DatabaseController extends Controller
{
    public function database(Request $request)
    {
        // Validate request
        $request->validate([
            'host'     => 'required',
            'database' => 'required',
            'username' => 'required',
            'port'     => 'required|integer',
        ]);

        // Set database
        $database     = new ConfigWriter('database');
        $database->set('connections.mysql.host', $request->host);
        $database->set('connections.mysql.port', $request->port);
        $database->set('connections.mysql.database', $request->database);
        $database->set('connections.mysql.username', $request->username);
        $database->set('connections.mysql.password', $request->password);
        $database->save();

        Artisan::call('cache:clear');

        // Check connection
        try {

            if (!DB::connection()->getPdo()) {

                return response('error_connection', 422);

            }else{

                // Migrate tables and seed the database
                Artisan::call('migrate:refresh --seed');

            }

        } catch (\Throwable $th) {
            throw $th;
        }

        // Success connected
        return response([]);
    }
}
