<?php

namespace App\Search;

use Illuminate\Http\Request;
use App\Model\Classified;

class DealSearch
{

	/**
	 * Apply filter on search
	 * @param  Request $filters [description]
	 * @return [type]           [description]
	 */
    public static function apply(Request $filters)
    {
    	// Start new query
        $deals = (new Classified)->newQuery();

        // Get keyword
        $keyword = $filters->query('q');

        // Search for deals based on keyword
        if ($filters->has('q')) {
            $deals->where('unique_slug', 'like', '%' . $keyword . '%')
            		 ->orWhere('title', 'like', '%' . $keyword . '%')
            		 ->orWhere('description', 'like', '%' . $keyword . '%');
        }

        // Category
        if ($filters->has('category')) {
            $deals->whereCategoryId($filters->query('category'));
        }

        // Sort resuls
        if ($filters->has('sort')) {
            switch ($filters->query('sort')) {
                case 'Newest':
                    $deals->latest();
                    break;
                case 'Oldest':
                    $deals->oldest();
                    break;
                case 'Popular':
                    $deals->with('statistics')->get()->sortBy(function($query)
                    {
                        return $query->statistics->count();
                    });
                    break;
                case 'Featured':
                    $deals->where('isUpgraded', true)->where('upgradedTo', 'featured');
                    break;
                case 'Urgent':
                    $deals->where('isUpgraded', true)->where('upgradedTo', 'urgent');
                    break;
                case 'Highlight':
                    $deals->where('isUpgraded', true)->where('upgradedTo', 'highlight');
                    break;
                
                default:
                    # code...
                    break;
            }
        }

        // Currency
        if ($filters->has('currency')) {
            $deals->whereCurrency($filters->query('currency'));
        }

        // price
        if ($filters->has('min') && !$filters->has('max')) {
            $deals->whereBetween('price', [$filters->query('min'), null]);
        }else if ($filters->has('max') && !$filters->has('min')) {
            $deals->whereBetween('price', [null, $filters->query('max')]);
        }else if ($filters->has('max') && $filters->has('min')) {
            $deals->whereBetween('price', [(int)$filters->query('min'), (int)$filters->query('max')]);
        }

        // Get the results and return them.
        return $deals->with('user')->active()->paginate(50);
    }
}
