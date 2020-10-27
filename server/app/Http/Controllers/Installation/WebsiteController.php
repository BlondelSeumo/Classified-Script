<?php

namespace App\Http\Controllers\Installation;

use App\Model\SettingsSeo;
use Illuminate\Http\Request;
use App\Model\SettingsGeneral;
use App\Http\Controllers\Controller;

class WebsiteController extends Controller
{

    /**
     * Update website settings
     *
     * @param Request $request
     * @return void
     */
    public function website(Request $request)
    {
        // Validate request
        $request->validate([
            'title'     => 'required|max: 60',
            'tagline'   => 'required|max: 100',
            'separator' => 'required|max: 60',
            'direction' => 'required|boolean',
        ]);

        // Update general settings
        SettingsGeneral::whereId(1)->update([
            'title'   => $request->title,
            'tagline' => $request->tagline,
            'isRTL'   => $request->direction,
        ]);

        // Update seo settings
        SettingsSeo::whereId(1)->update([
            'separator' => $request->separator
        ]);

        // Success
        return response([]);
    }
}
