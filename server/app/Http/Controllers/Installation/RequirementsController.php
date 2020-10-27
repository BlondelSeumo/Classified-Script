<?php

namespace App\Http\Controllers\Installation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RequirementsController extends Controller
{

    /**
     * Check server requirements
     * To run EVEREST
     *
     * @return void
     */
    public function requirements()
    {
        // Check php version
        $php       = version_compare(PHP_VERSION, '7.2') >= 0 ? phpversion() : false;
        // Check loaded extensions
        $bcmath    = extension_loaded('bcmath') ? true   : false;
        $ctype     = extension_loaded('ctype') ? true    : false;
        $json      = extension_loaded('json') ? true     : false;
        $mbstring  = extension_loaded('mbstring') ? true : false;
        $openssl   = extension_loaded('openssl') ? true  : false;
        $pdo       = extension_loaded('pdo') ? true      : false;
        $tokenizer = extension_loaded('tokenizer') ? true: false;
        $xml       = extension_loaded('xml') ? true      : false;

        // Return response
        return response([
            'php'       => $php,
            'bcmath'    => $bcmath,
            'ctype'     => $ctype,
            'json'      => $json,
            'mbstring'  => $mbstring,
            'openssl'   => $openssl,
            'pdo'       => $pdo,
            'tokenizer' => $tokenizer,
            'xml'       => $xml
        ]);
    }
}
