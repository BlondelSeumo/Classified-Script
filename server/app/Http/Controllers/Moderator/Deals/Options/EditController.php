<?php

namespace App\Http\Controllers\Moderator\Deals\Options;

use App\Model\Field;
use App\Model\Category;
use App\Model\Currency;
use App\Model\Classified;
use Illuminate\Support\Str;
use App\Model\FieldCategory;
use Illuminate\Http\Request;
use App\Model\FieldClassified;
use Khsing\World\Models\Country;
use App\Http\Controllers\Controller;

class EditController extends Controller
{
    function __construct()
    {
    	$this->middleware(['auth:api', 'moderator']);
        $this->authorize('moderator-edit-deals');
    }


    /**
     * Edit deal
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function edit($id)
    {
		// Check deal
		$deal       = Classified::isNotAdminDeals()->whereUniqueId($id)->firstOrFail();

		// Get parent categories
		$parents    = Category::parent()->latest('title')->get();

		// Get parent categories
		if (!$deal->category->parent_id) {

			$subs   = [];

		}else{

			$p      = Category::whereId($deal->category_id)->first();

			$subs   = Category::whereParentId($p->parent_id)->get();

		}
		
		// Get fields
		$fields     = $deal->fields()->with('field')->get();

		// Get form fields
		$formFields = $this->formFields($deal);
		
		// Get currencies
		$currencies = Currency::latest('name')->get();

    	return response([
			'deal'       => $deal,
			'fields'     => $fields,
			'formFields' => $formFields,
			'currencies' => $currencies,
			'parents'    => $parents,
			'subs'       => $subs
    	]);
    }


   	/**
	 * Update deal
	 *
	 * @param Request $request
	 * @return void
	 */
	public function update(Request $request)
	{
		// Check if deal exists
		$deal = Classified::isNotAdminDeals()->whereUniqueId($request->id)->firstOrFail();

		// Validate request
		$request->validate([
			'title'    => 'required|max:100',
            'category' => 'nullable|integer|exists:categories,id',
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

        // Count images
        if ($request->hasFile('images')) {
            $photosNumber = count($request->file('images'));
        }else{
            $photosNumber = 0;
        }

        // update deal
        $deal->update([
            'title'        => $request->get('title'),
            'description'  => clean($request->get('description')),
            'category_id'  => $request->sub_cat ?? $request->category,
            'photosNumber' => $photosNumber,
            'video'        => $request->video,
            'price'        => $request->price,
            'currency'     => $request->currency,
            'locale'       => $this->locale($request->currency)
        ]);

        // Save custom fields
        $this->saveCustomFields($request, $deal);

        // Saved successfuly
        return response([]);
	}



    /**
	 * Get deal fields
	 *
	 * @param Classified $deal
	 * @return void
	 */
	public function formFields(Classified $deal)
	{
		$response = array();

		// Loop through fields
		foreach ($deal->fields()->with('field')->get() as $field) {
			if ($field->field->type === 'dropdown') {
				array_push($response, ['slug' => $field->field->slug, 'value' => $field->options]);
			}else if ($field->field->type === 'checkbox') {
				$values = [];
				foreach (json_decode( $field->options, true ) as $key => $v) {
					array_push($values, $v);
				}
				array_push($response, [
					'slug' => $field->field->slug,
					'value' => $values
				]);
			}else if ($field->field->type === 'radio') {
				array_push($response, ['slug' => $field->field->slug, 'value' => $field->options]);
			}
		}

		// Return response
		return $response;
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
		// Delete old custom fields
		$old             = FieldClassified::whereDealId($deal->id)->delete();

        // Get category id
        $catId           = $request->sub_cat ? $request->sub_cat : $request->category;

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
						if (count($newArray)) {
							FieldClassified::create([
								'deal_id'  => $deal->id,
								'field_id' => $get_field->id,
								'options'  => json_encode($newArray, JSON_FORCE_OBJECT)
							]);
						}

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
}
