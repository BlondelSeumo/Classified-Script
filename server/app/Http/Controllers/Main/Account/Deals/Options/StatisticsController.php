<?php

namespace App\Http\Controllers\Main\Account\Deals\Options;

use Carbon\Carbon;
use App\Model\Classified;
use App\Model\SettingsSeo;
use Illuminate\Http\Request;
use App\Model\SettingsGeneral;
use Illuminate\Support\Facades\DB;
use App\Model\ClassifiedStatistics;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function statistics(Request $request)
    {
        // Get deal
        $deal = Classified::whereUserId(auth()->id())->whereUniqueId($request->id)->firstOrFail();

        // Get statistics
        $statistics = new ClassifiedStatistics;

        // Get countries
        $countries = DB::table('classifieds_statistics')
                        ->select('countryCode as id', DB::raw('COUNT(*) as `value`'))
                        ->groupBy('countryCode')
                        ->get();

        $dates = collect();
        foreach( range( -15, 0 ) AS $i ) {
            $date = Carbon::now()->addDays( $i )->format( 'Y-m-d' );
            $dates->put( $date, 0);
        }

        // Get the post counts
        $posts = $statistics->where( 'last_visit', '>=', $dates->keys()->first() )
                     ->groupBy( 'date' )
                     ->orderBy( 'date' )
                     ->get( [
                         DB::raw( 'DATE( last_visit ) as date' ),
                         DB::raw( 'COUNT( * ) as "count"' )
                     ] )
                     ->pluck( 'count', 'date' );

        // Merge the two collections; any results in `$posts` will overwrite the zero-value in `$dates`
        $dates = $dates->merge( $posts );

    	return response([
            'deal'       => $deal->load('user'),
            'countries'  => $countries,
            'dates'      => $dates,
            'seo'        => $this->seo()
        ]);
    }

    /**
     * Generate page seo
     * @return [type] [description]
     */
    private function seo()
    {
        // Get settings general
        $general      = SettingsGeneral::find(1);
        
        // Get seo settings
        $seo          = SettingsSeo::find(1);
        
        // Generate title
        $title        = $general->title;
        
        // Get separator
        $separator    = $seo->separator;
        
        // Get description
        $description  = $seo->description;

        // Get favicon
        $favicon      = $general->favicon === null ? index('storage/app/uploads/default/settings/favicon/favicon.png') : index("storage/app/$general->favicon");

        return [
            'title'       => $title,
            'separator'   => $separator,
            'description' => $description,
            'favicon'     => $favicon
        ];
    }
}
