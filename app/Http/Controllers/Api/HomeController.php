<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\TestimonialsModel;
use App\Models\ToursCategoryModel;
use App\Models\ToursModel;
use App\Models\PackageModel;
use App\Models\PackagesImagesModel;
use App\Models\ItineraryModel;
use Illuminate\Http\Request;
use Validator;
use Exception;

class HomeController extends Controller{
    
     public function index(Request $request){
        try {
            $categoryMap = [
                'tour_category' => fn() => $this->formatCategories(
                    ToursCategoryModel::where('status', 1)->get()
                ),
                'tour_india' => fn() => $this->getToursByCategory(1),
                'tour_international' => fn() => $this->getToursByCategory(2),
                'tour_honeymoon' => fn() => $this->getToursByCategory(3),
                'tour_cruise' => fn() => $this->getToursByCategory(4),
            ];
            
            $data = [];
            foreach ($categoryMap as $key => $callback) {
                $result = $callback();
                if ($key === 'tour_category' && $result->isEmpty()) {
                    throw new Exception(__('api_msg.data_not_found'));
                }
                $data[$key] = $result;
            }
            
            // Get testimonials
            $testimonials = TestimonialsModel::select('name', 'role', 'message')
                ->where('status', 1)
                ->get();
            
            // Format the response according to the desired structure
            $formattedData = [
                'options' => $this->formatOptions($data['tour_category'] ?? collect()),
                'tours' => $this->formatToursSection($data),
                'testimonials_list' => $this->formatTestimonialsList($testimonials)
            ];
            
            return APIResponse(200, __('api_msg.get_record_successfully'), $formattedData);
            
        } catch (Exception $e) {
            return response()->json([
                'status' => 400,
                'errors' => $e->getMessage()
            ]);
        }
    }
    
    private function formatCategories($categories){
        return $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'image' => asset($category->image) ? asset($category->image) : '',
                'type' => $category->type,
            ];
        });
    }
    
    private function getToursByCategory($categoryId){
        $tours = ToursModel::select('id', 'tour_name', 'description', 'image')
            ->where('tour_category_id', $categoryId)
            ->where('status', 1)
            ->get();
        
        return $tours->map(function ($tour) {
            return [
                'id' => $tour->id,
                'tour_name' => $tour->tour_name,
                'description' => $tour->description,
                'image' => $tour->image ? asset($tour->image) : '',
                'start_price'=>getMinPrice($tour->id),
                
            ];
        });
    }
    
    /**
     * Format options section combining categories and menu items
     */
    private function formatOptions($categories)
    {
        $options = [];
        
        // Add package type options from categories
        foreach ($categories as $category) {
            $options[] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'image' => $category['image'],
                'type' => $category['type']
            ];
        }
        
        
        
        return $options;
    }
    
    /**
     * Format tours section with titles and packages
     */
    private function formatToursSection($data)
    {
        $tours = [];
        
        $tourSections = [
            ['key' => 'tour_india', 'title' => 'Explore India Packages','category_id'=>1],
            ['key' => 'tour_international', 'title' => 'International Packages','category_id'=>2],
            ['key' => 'tour_honeymoon', 'title' => 'Honeymoon Packages','category_id'=>3],
            ['key' => 'tour_cruise', 'title' => 'Cruise Packages','category_id'=>4]
        ];
        
        foreach ($tourSections as $section) {
            if (isset($data[$section['key']]) && $data[$section['key']]->isNotEmpty()) {
                $tours[] = [
                     'category_id' => $section['category_id'],
                    'title' => $section['title'],
                    'packages' => $data[$section['key']]->toArray()
                ];
            }
        }
        
        return $tours;
    }
    
    /**
     * Format testimonials list
     */
    private function formatTestimonialsList($testimonials)
    {
        return $testimonials->map(function ($testimonial) {
            return [
                'name' => $testimonial->name,
                'role' => $testimonial->role,
                'message' => $testimonial->message
            ];
        })->toArray();
    }
     public function tour_category_by_tour(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'tour_category_id' => 'required|numeric',
                'per_page' => 'nullable|numeric|min:1|max:100',
                'page' => 'nullable|numeric|min:1'
            ], [
                'tour_category_id.required' => __('api_msg.please_enter_required_fields'),
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validator->errors()->first()
                ]);
            }
            $tour_category_id = $request->tour_category_id;
            $perPage = $request->per_page ?? 15; // Default 15 items per page
            $tours = ToursModel::where('tour_category_id', $tour_category_id)
                ->where('status', 1)
                ->select('id', 'tour_category_id', 'tour_name', 'description', 'image', 'status', 'created_at', 'updated_at')
                ->paginate($perPage);
            if ($tours->isEmpty()) {
                return APIResponse(200, __('api_msg.data_not_found'));
            }
            $formattedTours = collect($tours->items())->map(fn($tour) => $this->formatTourDetails($tour));
            $response = [
                'data' => $formattedTours,
                'pagination' => [
                    'current_page' => $tours->currentPage(),
                    'last_page' => $tours->lastPage(),
                    'per_page' => $tours->perPage(),
                    'total' => $tours->total()
                ]
            ];
    
            return APIResponse(200, __('api_msg.get_record_successfully'), $response);
    
        } catch (Exception $e) {
            return $this->handleError($e);
        }
    }
    
    
    private function formatTourDetails($tour){
        return [
            'id' => $tour->id,
            'tour_category' => getTourCategoryName($tour->tour_category_id),
            'tour_name' => $tour->tour_name,
            'description' => $tour->description,
            'image' => $tour->image ? asset($tour->image) : '',
            'start_from' => 'Starting From ₹'.' '.getMinPrice($tour->id)
        ];
    }
    
     
   

    public function plan_by_price(Request $request){
        try {
          $validation = Validator::make(
                $request->all(),
                [
                    'type' => 'required',
                    'package_id' => 'required'
                ],
                [
                    'type.required' => __('api_msg.please_enter_required_fields'),
                    'package_id.required' => __('api_msg.please_enter_required_fields')
                ]
            );
            if ($validation->fails()) {
                $errorMessage = $validation->errors()->first();
                return APIResponse(400, $errorMessage ?: __('api_msg.validation_error'));
            }
          $type = $request->type;
          $package_id = $request->package_id;
          if($type == 'Standard'){
              $get_package_price = PackageModel::select('s_individual')->where('id',$package_id)->where('status',1)->first();
          }elseif($type == 'Deluxe'){
              $get_package_price = PackageModel::select('d_individual')->where('id',$package_id)->where('status',1)->first();
          }else{
              $get_package_price = PackageModel::select('p_individual')->where('id',$package_id)->where('status',1)->first();
          }
          $price = $get_package_price;
          return APIResponse(200, __('api_msg.get_record_successfully'), $get_package_price);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

}