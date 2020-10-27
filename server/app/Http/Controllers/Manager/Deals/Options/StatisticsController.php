<?php

namespace App\Http\Controllers\Manager\Deals\Options;

use App\Http\Controllers\Controller;
use App\Model\Classified;
use App\Model\ClassifiedStatistics;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    
    function __construct()
    {
    	$this->middleware(['auth:api', 'manager']);
    }



    /**
     * Get deal statistics
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function statistics(Request $request)
    {
    	// Check deal
    	$deal       = Classified::whereUniqueId($request->id)->firstOrFail();

        // Get statistics
        $statistics = new ClassifiedStatistics;

        // Get countries
        $countries  = DB::table('classifieds_statistics')
                        ->select('countryCode as id', DB::raw('COUNT(*) as `value`'))
                        ->groupBy('countryCode')
                        ->get();

        // Get continents
        $continents = DB::table('classifieds_statistics')
                        ->select('continent', DB::raw('COUNT(*) as `value`'))
                        ->groupBy('continent')
                        ->get();

        // Get devices
        $devices = DB::table('classifieds_statistics')
                        ->select('deviceName as device', DB::raw('COUNT(*) as `value`'))
                        ->groupBy('deviceName')
                        ->get();

        // Get platforms
        $platforms = DB::table('classifieds_statistics')
                        ->select(DB::raw('CONCAT(platformName," ",platformVersion) as platform, COUNT(*) as `value`'))
                        ->groupBy('platformName', 'platformVersion')
                        ->get();

        // Get browsers
        $browsers = DB::table('classifieds_statistics')
                        ->select(DB::raw('CONCAT(browserName," ",browserVersion) as browser, COUNT(*) as `value`'))
                        ->groupBy('browserName', 'browserVersion')
                        ->get();

        // Get phones
        $phones = DB::table('classifieds_statistics')
                        ->select('isPhone as phone', DB::raw('COUNT(*) as `value`'))
                        ->groupBy('isPhone')
                        ->get();

        // Get desktops
        $desktops = DB::table('classifieds_statistics')
                        ->select('isDesktop as desktop', DB::raw('COUNT(*) as `value`'))
                        ->groupBy('isDesktop')
                        ->get();

        // Get tablets
        $tablets = DB::table('classifieds_statistics')
                        ->select('isTablet as tablet', DB::raw('COUNT(*) as `value`'))
                        ->groupBy('isTablet')
                        ->get();

        // Get robots
        $robots = DB::table('classifieds_statistics')
                        ->select('isRobot as robot', DB::raw('COUNT(*) as `value`'))
                        ->groupBy('isRobot')
                        ->get();

        $details = $statistics->latest('last_visit')->paginate(50);

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

        // Total views
        $views = $statistics->count();

    	return response([
            'deal'       => $deal->load('user'),
            'countries'  => $countries,
            'continents' => $continents,
            'devices'    => $devices,
            'platforms'    => $platforms,
            'browsers'    => $browsers,
            'phones'    => $phones,
            'desktops'    => $desktops,
            'tablets'    => $tablets,
            'robots'    => $robots,
            'details'    => $details,
            'views'    => $views,
            'dates'    => $dates,
        ]);
    }


    /**
     * Get more details
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function more($id)
    {
        // Check deal
        $deal       = Classified::whereUniqueId($id)->firstOrFail();
        
        // Get statistics
        //$statistics = ClassifiedStatistics::whereDealId($deal->id)->latest('last_visit')->paginate(100);
        $statistics = ClassifiedStatistics::latest('last_visit')->paginate(100);

        // Return statistics
        return response($statistics);
    }
}
