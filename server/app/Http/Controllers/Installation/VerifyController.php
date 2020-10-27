<?php

namespace App\Http\Controllers\Installation;

use ConfigWriter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class VerifyController extends Controller
{

    /**
     * Validate your purchase
     *
     * @param Request $request
     * @return void
     */
    public function verify(Request $request)
    {
        // Validate request
        $request->validate([
            'code'     => 'required|max:36|min:36',
            'username' => 'required|max:149'
        ]);

        // Set code
        $envato     = new ConfigWriter('envato');
        $envato->set('purchaseCode', $request->code);
        $envato->set('envatoUsername', $request->username);
        $envato->save();

        // Clear cache
        Artisan::call('config:clear');

        // Success
        return response([]);
    }
}
