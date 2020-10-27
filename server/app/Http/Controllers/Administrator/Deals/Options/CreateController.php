<?php

namespace App\Http\Controllers\Administrator\Deals\Options;

use App\Model\Field;
use App\Model\Category;
use App\Model\Currency;
use App\Model\Classified;
use App\Model\SettingsGeo;
use Illuminate\Support\Str;
use App\Model\FieldCategory;
use Illuminate\Http\Request;
use App\Model\FieldClassified;
use Khsing\World\Models\Country;
use App\Http\Controllers\Controller;

class CreateController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'administrator']);
        $this->authorize('admin-access-deals');
    }


    /**
     * Fetch data
     * @return [type] [description]
     */
    public function fetch()
    {   
        // Get currencies
        $currencies    = Currency::latest('name')->get();
        
        // Get categories
        $categories    = Category::whereParentId(false)->latest()->get();
        
        // Get default currency
        $currency      = SettingsGeo::with(array('currency' => function($query) {
            $query->select('id', 'code');
        }))->find(1);

        return response([
            'currencies'    => $currencies,
            'categories'    => $categories,
            'currency'      => $currency
        ]);
    }


    /**
     * Insert new deal in database
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {
		// Validate form
        $request->validate([
            'title'    => 'required|max:100',
            'category' => 'required|integer|exists:categories,id',
            'sub_cat'  => 'nullable|integer|exists:categories,id',
            'currency' => 'required|max:3|exists:currencies,code'
        ]);

        // Validate custom fields
        $errors = $this->validateCustomFields($request);

        // Fields are missing
        if ($errors) {
            return response([
                'errors' => $errors
            ], 422);
        }

        // create a short string
        $short_string     = uniqueId(12);

        // Count images
        if ($request->hasFile('images')) {
            $photosNumber = count($request->file('images'));
        }else{
            $photosNumber = 0;
        }

        // create deal
        $deal   = Classified::create([
            'short_id'     => $short_string,
            'unique_slug'  => Str::slug($request->title, '-') . "-" . $short_string,
            'user_id'      => auth()->id(),
            'title'        => $request->get('title'),
            'description'  => clean($request->get('description')),
            'category_id'  => $request->sub_cat ?? $request->category,
            'photosNumber' => $photosNumber,
            'video'        => $request->video,
            'price'        => $request->price,
            'currency'     => $request->currency,
            'locale'       => $this->locale($request->currency),
            'expires_at'   => now()->addYears(5),
            'isPending'    => false,
            'isActive'     => true
        ]);

        // Save custom fields
        $this->saveCustomFields($request, $deal);

        // Saved successfuly
        return response([]);
    }


    /**
     * Get currency locale
     * @param  [type] $currency [description]
     * @return [type]           [description]
     */
    public function locale($currency = null)
    {

        if (!is_null($currency)) {
            // Get locale
            $locale = Currency::where('code', $currency)->first();

            return $locale->locale;
        }

        return null;
    }



    /**
     * Validate custom fields
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    private function validateCustomFields(Request $request)
    {
        // Get category id
        $catId          = $request->sub_cat ? $request->sub_cat : $request->category;

        // Get category
        $category       = Category::whereId($catId)->firstOrFail();

        // Get fields
        $fields         = FieldCategory::with(array('field' => function($query) {
            $query->where('isRequired', true);
        }))->whereCategoryId($catId)->get();

        if (count($fields)) {

            // Get field from request
            $custom         = json_decode($request->fields);
            
            // Errors
            $errors         = [];
            
            $custom_fields  = [];
            $request_fields = []; 

            // Validate fields
            foreach ($fields as $field) {

                if (!is_null($field->field)) {
                    array_push($custom_fields, [
                        'slug' => $field->field->slug
                    ]);
                }

            }

            foreach ($custom as $c) {
                array_push($request_fields, [
                    'slug'  => $c->slug
                ]);
            }

            $result = array_map('unserialize', array_diff(array_map('serialize', $custom_fields), array_map('serialize', $request_fields)));

            // Custom fields missing?
            if (count($result)) {
                foreach ($result as $key => $value) {
                    $errors[$value['slug']] = ['required'];
                }
                return $errors;
            }

            // All is good
            return false;

        }else{
            return false;
        }

    }



    /**
     * Save custom fields
     * @param  Request    $request [description]
     * @param  Classified $deal    [description]
     * @return [type]              [description]
     */
    private function saveCustomFields(Request $request, Classified $deal)
    {
        // Get category id
        $catId          = $request->sub_cat ? $request->sub_cat : $request->category;

        // Get category
        $category        = Category::whereId($catId)->firstOrFail();
        
        // Get fields from database
        $fields_database = FieldCategory::with('field')->whereCategoryId($category->id)->get();

        // Check if fields exists for this category
        if (count($fields_database)) {
            
            // Get fields from request
            $fields_request  = json_decode($request->fields);


            foreach ($fields_request as $field) {

                // Get field by slug
                $get_field = Field::whereSlug($field->slug)->firstOrFail();

                // Check type
                switch ($get_field->type) {

                    // Dropdown
                    case 'dropdown':
                        
                        // Save custom field
                        FieldClassified::create([
                            'deal_id'  => $deal->id,
                            'field_id' => $get_field->id,
                            'options'  => $field->value
                        ]);

                        break;
                    
                    // Radio
                    case 'radio':
                        
                        // Save custom field
                        FieldClassified::create([
                            'deal_id'  => $deal->id,
                            'field_id' => $get_field->id,
                            'options'  => $field->value
                        ]);

                        break;

                    // Checkbox
                    case 'checkbox':

                        // Get only values from arrays
                        $newArray = array_values($field->value);

                        // Save custom field
                        FieldClassified::create([
                            'deal_id'  => $deal->id,
                            'field_id' => $get_field->id,
                            'options'  => json_encode($newArray, JSON_FORCE_OBJECT)
                        ]);

                        break;

                    default:
                        # code...
                        break;
                }

            }

        }else{

            // No fields to save
            return;

        }
        
    }
}
