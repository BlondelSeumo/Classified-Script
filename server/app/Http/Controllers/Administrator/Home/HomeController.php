<?php

namespace App\Http\Controllers\Administrator\Home;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Classified;
use App\Model\Comment;
use App\Model\Role;
use App\Model\Store;
use App\Model\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
    }


    /**
     * Get home page stats and data
     * @return [type] [description]
     */
    public function home()
    {
        // Generate seo
        $seo         = array(
            'title'      => 'EVEREST', 
            'separator'  => '-', 
            'complement' => 'Dashboard home', 
        );
        
        // Users statistics
        $users       = $this->users();
        
        // Deals statistics
        $deals       = $this->deals();
        
        // Comments statistics
        $comments    = $this->comments();
        
        // Shops statistics
        $shops       = $this->shops();

        // Top countries
        $countries   = $this->countries();


        // Return stats
    	return response()->json([
            'users'     => [
                'today'     => $users[0],
                'yesterday' => $users[1],
                'week'      => $users[2],
                'month'     => $users[3],
                'total'     => $users[4],
            ],
            'deals'     => [
                'today'     => $deals[0],
                'yesterday' => $deals[1],
                'week'      => $deals[2],
                'month'     => $deals[3],
                'total'     => $deals[4],
                'top'       => $deals[5],
            ],
            'comments'  => [
                'today'     => $comments[0],
                'yesterday' => $comments[1],
                'month'     => $comments[2],
                'total'     => $comments[3],
            ],
            'shops'     => [
                'today'     => $shops[0],
                'yesterday' => $shops[1],
                'month'     => $shops[2],
                'total'     => $shops[3],
                'recent'    => $shops[4],
            ],
            'seo'       => $seo,
            'countries' => $countries,
    	], 200);
    }


    /**
     * Get users statistics
     * @return [type] [description]
     */
    public function users()
    {
        // Get users statistics
        $users             = new User;
        
        // Get today's users
        $today             = $users->whereDate('created_at', Carbon::today())->count();
        
        // Yesterday users
        $yesterday         = $users->whereDate('created_at', Carbon::yesterday())->count();
        
        // This month users
        $month             = $users->whereDate('created_at', '>', now()->subDays(30))->count();
        
        // Total users
        $total             = $users->get()->count();

        // Get users last week
        $week              = collect();

        foreach( range( -7, 0 ) AS $i ) {
            $date = now()->addDays( $i )->format( 'Y-m-d' );
            $week->put( $date, 0);
        }

        $data = $users->where( 'created_at', '>=', $week->keys()->first() )
                      ->groupBy( 'date' )
                      ->orderBy( 'date' )
                      ->get([DB::raw( 'DATE( created_at ) as date' ), DB::raw( 'COUNT( * ) as "count"' )])
                     ->pluck('count', 'date');

        $week = $week->merge($data);
        
        // Return all statistics
        return $statistics = array($today, $yesterday, $week, $month, $total);
    }


    /**
     * Get deals statistics
     * @return [type] [description]
     */
    public function deals()
    {
        // Get deals statistics
        $deals             = new Classified;
        
        // Get today's deals
        $today             = $deals->whereDate('created_at', Carbon::today())->count();
        
        // Yesterday deals
        $yesterday         = $deals->whereDate('created_at', Carbon::yesterday())->count();
        
        // This month deals
        $month             = $deals->whereDate('created_at', '>', now()->subDays(30))->count();
        
        // Total deals
        $total             = $deals->get()->count();

        // Get deals last week
        $week              = collect();

        foreach( range( -7, 0 ) AS $i ) {
            $date = now()->addDays( $i )->format( 'Y-m-d' );
            $week->put( $date, 0);
        }

        $data = $deals->where( 'created_at', '>=', $week->keys()->first() )
                      ->groupBy( 'date' )
                      ->orderBy( 'date' )
                      ->get([DB::raw( 'DATE( created_at ) as date' ), DB::raw( 'COUNT( * ) as "count"' )])
                     ->pluck('count', 'date');

        $week = $week->merge($data);

        // Get top visited deals
        $top  = $deals->withCount('statistics')
                       ->with('user')
                       ->orderBy('statistics_count', 'desc')
                       ->take(10)
                       ->get();
        
        // Return all statistics
        return $statistics = array($today, $yesterday, $week, $month, $total, $top);
    }


    /**
     * Get comments statistics
     * @return [type] [description]
     */
    public function comments()
    {
        // Get comments statistics
        $comments             = new Comment;
        
        // Get today's comments
        $today             = $comments->whereDate('created_at', Carbon::today())->count();
        
        // Yesterday comments
        $yesterday         = $comments->whereDate('created_at', Carbon::yesterday())->count();
        
        // This month comments
        $month             = $comments->whereDate('created_at', '>', now()->subDays(30))->count();
        
        // Total comments
        $total             = $comments->get()->count();
        
        // Return all statistics
        return $statistics = array($today, $yesterday, $month, $total);
    }


    /**
     * Get shops statistics
     * @return [type] [description]
     */
    public function shops()
    {
        // Get shops statistics
        $shops             = new Store;
        
        // Get today's shops
        $today             = $shops->whereDate('created_at', Carbon::today())->count();
        
        // Yesterday shops
        $yesterday         = $shops->whereDate('created_at', Carbon::yesterday())->count();
        
        // This month shops
        $month             = $shops->whereDate('created_at', '>', now()->subDays(30))->count();
        
        // Total shops
        $total             = $shops->get()->count();

        // Recent shops
        $recent            = $shops->with('country')->latest()->take(10)->get();
        
        // Return all statistics
        return $statistics = array($today, $yesterday, $month, $total, $recent);
    }



    /**
     * Get top countries
     * @return [type] [description]
     */
    public function countries()
    {
        return DB::table('classifieds_statistics')
                    ->select('countryCode as id', DB::raw('COUNT(*) as `value`'))
                    ->groupBy('countryCode')
                    ->get();
    }
}
