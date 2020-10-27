<?php

namespace App\Http\Controllers\Installation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use ConfigWriter;

class WelcomeController extends Controller
{

    /**
     * Set default language
     *
     * @param Request $request
     * @return void
     */
    public function welcome(Request $request)
    {
        // Verify language
        $request->validate([
            'language' => 'required|in:en,ar,fr'
        ]);

        // Change language
        $app     = new ConfigWriter('app');
        $app->set('locale', $request->language);
        $app->save();

        // Set language
        App::setLocale($request->language);

        // Success
        return response([]);
    }
}
