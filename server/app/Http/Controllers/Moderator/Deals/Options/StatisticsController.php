<?php

namespace App\Http\Controllers\Moderator\Deals\Options;

use Illuminate\Http\Request;
use App\Model\Classified;
use App\Model\ClassifiedStatistics;
use Carbon\Carbon;
use DB;
use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
        $this->authorize('moderator-stats-deals');
    }


    /**
     * Get deal statistics
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function statistics(Request $request)
    {
    	// Check deal
    	$deal = Classified::whereUniqueId($request->id)->firstOrFail();

        // Get statistics
        $statistics = new ClassifiedStatistics;

        // Get countries
        $countries = DB::table('classifieds_statistics')
                        ->select('countryCode as id', DB::raw('COUNT(*) as `value`'))
                        ->groupBy('countryCode')
                        ->get();

        // Get continents
        $continents = DB::table('classifieds_statistics')
                        ->select('continent', DB::raw('COUNT(*) as `value`'))
                        ->groupBy('continent')
                        ->get();

        // Get robots
        $robots = DB::table('classifieds_statistics')
                        ->select('isRobot as robot', DB::raw('COUNT(*) as `value`'))
                        ->groupBy('isRobot')
                        ->get();

        $details = $statistics->latest('last_visit')->paginate(10);

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
            'robots'     => $robots,
            'details'    => $details,
            'views'      => $views,
            'dates'      => $dates,
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
        $statistics = ClassifiedStatistics::whereDealId($deal->id)->latest('last_visit')->paginate(10);

        // Return statistics
        return response($statistics);
    }
}
