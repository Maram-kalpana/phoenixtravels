<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\PackageHighlights;
use App\Models\PackageHighlightsGallery;
use App\Models\PackageHighlightsSightseeing;
use App\Models\PackageHighlightsProperty;

class HoneymoonPackageHighlightsController extends Controller{
    
    /**
     * Add Package Highlights
     */
    public function add(Request $request, $package_id = null, $id = null)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            
            $rules = [
                'highlights_short_description' => 'required|string|max:500',
                'highlights_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'youtube_link' => 'nullable|url',
                'gallery_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'sightseeing_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'd_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
            
            $messages = [
                'highlights_short_description.required' => 'Short description is required',
                'highlights_image.required' => 'Highlights image is required',
                'highlights_image.image' => 'Highlights image must be an image file',
                'gallery_image.*.image' => 'Gallery images must be image files',
                'sightseeing_image.*.image' => 'Sightseeing images must be image files',
                'd_image.*.image' => 'Property images must be image files',
            ];
            
            $validator = Validator::make($data, $rules, $messages);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ], 422);
            }
            
            try {
                // Create directories if they don't exist
                $this->createDirectories();
                
                // Handle main image upload
                $highlightImage = '';
                if ($request->hasFile('highlights_image')) {
                    $file = $request->file('highlights_image');
                    $highlightImage = time() . '_highlight.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/highlights'), $highlightImage);
                }
                
                // Create Package Highlights
                $highlight = new PackageHighlights();
                $highlight->package_id = $package_id;
                $highlight->short_description = $data['highlights_short_description'];
                $highlight->highlights_image = $highlightImage;
                $highlight->youtube_link = $data['youtube_link'] ?? null;
                $highlight->status = 1;
                $highlight->created_at = date('Y-m-d H:i:s');
                $highlight->save();
                
                // Save Gallery Images
                if ($request->hasFile('gallery_image')) {
                    foreach ($request->file('gallery_image') as $galleryImage) {
                        if ($galleryImage && $galleryImage->isValid()) {
                            $galleryName = time() . '_gallery_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                            $galleryImage->move(public_path('uploads/highlights'), $galleryName);
                            
                            $gallery = new PackageHighlightsGallery();
                            $gallery->highlight_id = $highlight->id;
                            $gallery->image = $galleryName;
                            $gallery->created_at = date('Y-m-d H:i:s');
                            $gallery->save();
                        }
                    }
                }
                
                // Save Sightseeing Images
                if ($request->hasFile('sightseeing_image')) {
                    foreach ($request->file('sightseeing_image') as $sightseeingImage) {
                        if ($sightseeingImage && $sightseeingImage->isValid()) {
                            $sightseeingName = time() . '_sightseeing_' . uniqid() . '.' . $sightseeingImage->getClientOriginalExtension();
                            $sightseeingImage->move(public_path('uploads/highlights'), $sightseeingName);
                            
                            $sightseeing = new PackageHighlightsSightseeing();
                            $sightseeing->highlight_id = $highlight->id;
                            $sightseeing->image = $sightseeingName;
                            $sightseeing->created_at = date('Y-m-d H:i:s');
                            $sightseeing->save();
                        }
                    }
                }
                
                // Save Property Photos
                if ($request->hasFile('d_image')) {
                    foreach ($request->file('d_image') as $propertyImage) {
                        if ($propertyImage && $propertyImage->isValid()) {
                            $propertyName = time() . '_property_' . uniqid() . '.' . $propertyImage->getClientOriginalExtension();
                            $propertyImage->move(public_path('uploads/highlights'), $propertyName);
                            
                            $property = new PackageHighlightsProperty();
                            $property->highlight_id = $highlight->id;
                            $property->image = $propertyName;
                            $property->created_at = date('Y-m-d H:i:s');
                            $property->save();
                        }
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => ['success' => ['Package highlights added successfully']]
                ], 200);
                
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => ['error' => [$e->getMessage()]]
                ], 500);
            }
        }
        
        return view('backend.honeymoon_highlights.add');
    }
    
    /**
     * Edit Package Highlights
     */
    public function edit(Request $request, $id = null){
        
        $highlight = PackageHighlights::with(['gallery', 'sightseeing', 'propertyPhotos'])->where('id', $id)->first();
        
        if (!$highlight) {
            return redirect()->back()->with('error', 'Package highlights not found');
        }
        
        if ($request->isMethod('post')) {
            $data = $request->all();
            
            $rules = [
                'highlights_short_description' => 'required|string|max:500',
                'highlights_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'youtube_link' => 'nullable|url',
                'gallery_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'sightseeing_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'd_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
            
            $messages = [
                'highlights_short_description.required' => 'Short description is required',
                'highlights_image.image' => 'Highlights image must be an image file',
                'gallery_image.*.image' => 'Gallery images must be image files',
                'sightseeing_image.*.image' => 'Sightseeing images must be image files',
                'd_image.*.image' => 'Property images must be image files',
            ];
            
            $validator = Validator::make($data, $rules, $messages);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ], 422);
            }
            
            try {
                // Create directories if they don't exist
                $this->createDirectories();
                
                // Update main image if new one is uploaded
                if ($request->hasFile('highlights_image')) {
                    // Delete old image
                    if ($highlight->highlights_image && file_exists(public_path('uploads/highlights/' . $highlight->highlights_image))) {
                        unlink(public_path('uploads/highlights/' . $highlight->highlights_image));
                    }
                    
                    $file = $request->file('highlights_image');
                    $highlightImage = time() . '_highlight.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/highlights'), $highlightImage);
                    $highlight->highlights_image = $highlightImage;
                }
                
                // Update fields
                $highlight->short_description = $data['highlights_short_description'];
                $highlight->youtube_link = $data['youtube_link'] ?? null;
                $highlight->updated_at = date('Y-m-d H:i:s');
                $highlight->save();
                
                // Handle Gallery Images - Delete old and add new
                if ($request->has('delete_gallery') && !empty($request->delete_gallery)) {
                    $deleteGalleryIds = explode(',', $request->delete_gallery);
                    foreach ($deleteGalleryIds as $galleryId) {
                        $gallery = PackageHighlightsGallery::find($galleryId);
                        if ($gallery) {
                            if (file_exists(public_path('uploads/highlights/gallery/' . $gallery->image))) {
                                unlink(public_path('uploads/highlights/gallery/' . $gallery->image));
                            }
                            $gallery->delete();
                        }
                    }
                }
                
                if ($request->hasFile('gallery_image')) {
                    foreach ($request->file('gallery_image') as $galleryImage) {
                        if ($galleryImage && $galleryImage->isValid()) {
                            $galleryName = time() . '_gallery_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                            $galleryImage->move(public_path('uploads/highlights/gallery'), $galleryName);
                            
                            $gallery = new PackageHighlightsGallery();
                            $gallery->highlight_id = $highlight->id;
                            $gallery->image = $galleryName;
                            $gallery->created_at = date('Y-m-d H:i:s');
                            $gallery->save();
                        }
                    }
                }
                
                // Handle Sightseeing Images
                if ($request->has('delete_sightseeing') && !empty($request->delete_sightseeing)) {
                    $deleteSightseeingIds = explode(',', $request->delete_sightseeing);
                    foreach ($deleteSightseeingIds as $sightseeingId) {
                        $sightseeing = PackageHighlightsSightseeing::find($sightseeingId);
                        if ($sightseeing) {
                            if (file_exists(public_path('uploads/highlights/sightseeing/' . $sightseeing->image))) {
                                unlink(public_path('uploads/highlights/sightseeing/' . $sightseeing->image));
                            }
                            $sightseeing->delete();
                        }
                    }
                }
                
                if ($request->hasFile('sightseeing_image')) {
                    foreach ($request->file('sightseeing_image') as $sightseeingImage) {
                        if ($sightseeingImage && $sightseeingImage->isValid()) {
                            $sightseeingName = time() . '_sightseeing_' . uniqid() . '.' . $sightseeingImage->getClientOriginalExtension();
                            $sightseeingImage->move(public_path('uploads/highlights/sightseeing'), $sightseeingName);
                            
                            $sightseeing = new PackageHighlightsSightseeing();
                            $sightseeing->highlight_id = $highlight->id;
                            $sightseeing->image = $sightseeingName;
                            $sightseeing->created_at = date('Y-m-d H:i:s');
                            $sightseeing->save();
                        }
                    }
                }
                
                // Handle Property Photos
                if ($request->has('delete_property') && !empty($request->delete_property)) {
                    $deletePropertyIds = explode(',', $request->delete_property);
                    foreach ($deletePropertyIds as $propertyId) {
                        $property = PackageHighlightsProperty::find($propertyId);
                        if ($property) {
                            if (file_exists(public_path('uploads/highlights/property/' . $property->image))) {
                                unlink(public_path('uploads/highlights/property/' . $property->image));
                            }
                            $property->delete();
                        }
                    }
                }
                
                if ($request->hasFile('d_image')) {
                    foreach ($request->file('d_image') as $propertyImage) {
                        if ($propertyImage && $propertyImage->isValid()) {
                            $propertyName = time() . '_property_' . uniqid() . '.' . $propertyImage->getClientOriginalExtension();
                            $propertyImage->move(public_path('uploads/highlights/property'), $propertyName);
                            
                            $property = new PackageHighlightsProperty();
                            $property->highlight_id = $highlight->id;
                            $property->image = $propertyName;
                            $property->created_at = date('Y-m-d H:i:s');
                            $property->save();
                        }
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => ['success' => ['Package highlights updated successfully']]
                ], 200);
                
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => ['error' => [$e->getMessage()]]
                ], 500);
            }
        }
        
        return view('backend.honeymoon_highlights.edit', compact('highlight'));
    }
    
    /**
     * List Package Highlights
     */
        public function list($package_id = null, $tours_id = null)
    {
        return view('backend.honeymoon_highlights.index', compact('package_id', 'tours_id'));
    }
    
    /**
     * Get Package Highlights Data for DataTable
     */
   public function getHighlightsData(Request $request, $package_id = null, $tours_id = null)
{
    // Get search value
    $search = isset($_GET['search']['value']) ? $_GET['search']['value'] : '';
    
    // Get limit
    $limit = isset($_GET['length']) ? (int)$_GET['length'] : 10;
    
    // Get offset
    $offset = isset($_GET['start']) ? (int)$_GET['start'] : 0;
    
    // Get order - with proper validation
    $orderType = 'desc';
    $nameOrder = 'id';
    
    if (isset($_GET['order']) && isset($_GET['order'][0])) {
        $orderType = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'desc';
        
        if (isset($_GET['columns']) && isset($_GET['columns'][$_GET['order'][0]['column']])) {
            $nameOrder = isset($_GET['columns'][$_GET['order'][0]['column']]['name']) 
                ? $_GET['columns'][$_GET['order'][0]['column']]['name'] 
                : 'id';
        }
    }
    
    if (empty($package_id)) {
        $package_id = $_GET['package_id'];
        $tours_id = $_GET['amp;tours_id'];
    }
    
    // Build query
    $query = PackageHighlights::query();
    
    if (!empty($package_id)) {
        $query->where('package_id', $package_id);
    }
    
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('short_description', 'like', '%' . $search . '%')
                ->orWhere('youtube_link', 'like', '%' . $search . '%');
        });
    }
    
    $total = $query->count();
    
    // Get data
    $highlights = $query->with(['gallery', 'sightseeing', 'propertyPhotos'])
        ->offset($offset)
        ->limit($limit)
        ->orderBy($nameOrder, $orderType)
        ->get();
         
    $i = 1 + $offset;
    $data = [];
    
    foreach ($highlights as $key => $val) {
        $status = $val->status ? "checked" : "";
        
        // Get image HTML
        $imageHtml = '';
        if ($val->highlights_image && file_exists(public_path('uploads/highlights/' . $val->highlights_image))) {
            $imageHtml = '<img src="' . asset('uploads/highlights/' . $val->highlights_image) . '" alt="image" width="50" height="50" style="object-fit:cover; border-radius: 4px;">';
        } else {
            $imageHtml = '<span class="badge bg-secondary">No Image</span>';
        }
        
        // Count gallery images
        $galleryCount = $val->gallery->count();
        $sightseeingCount = $val->sightseeing->count();
        $propertyCount = $val->propertyPhotos->count();
        
        // Generate edit URL
        $editUrl = route('domestic.package.highlights.edit', [
            'package_id' => $package_id,
            'tours_id' => $tours_id,
            'id' => $val->id
        ]);
        
        $data[] = array(
            $i++, // Serial number
            $imageHtml, // Image
            substr($val->short_description, 0, 50) . (strlen($val->short_description) > 50 ? '...' : ''), // Short description
            '<span class="badge bg-info">' . $galleryCount . '</span>', // Gallery count
            '<span class="badge bg-success">' . $sightseeingCount . '</span>', // Sightseeing count
            '<span class="badge bg-warning text-dark">' . $propertyCount . '</span>', // Property count
            $val->youtube_link ? '<a href="' . $val->youtube_link . '" target="_blank"><i class="bx bx-link"></i></a>' : 'N/A', // YouTube link
            "<div class='switch'>
                <div class='onoffswitch'>
                    <input type='checkbox' " . $status . " data-id='" . $val->id . "' class='onoffswitch-checkbox status-update' id='highlight" . $val->id . "'>
                    <label class='onoffswitch-label' for='highlight" . $val->id . "'>
                        <span class='onoffswitch-inner'></span>
                        <span class='onoffswitch-switch'></span>
                    </label>
                </div>
            </div>", // Status toggle
            date('d-m-Y', strtotime($val->created_at)), // Created date
            '<a href="' . $editUrl . '" class="editHighlight" data-id="' . $val->id . '">
                <i class="bx bxs-edit text-success" style="font-size: 22px;"></i>
            </a> | 
            <a href="javascript:void(0)" class="deleteHighlight" onclick="deleteHighlight(' . $val->id . ')">
                <i class="bx bxs-trash text-danger" style="font-size: 22px;"></i>
            </a>' // Actions
        );
    }
    
    $records['recordsTotal'] = $total;
    $records['recordsFiltered'] = $total;
    $records['data'] = $data;
    
    return response()->json($records);
}






    public function updateStatus(Request $request)
    {
        try {
            $highlight = PackageHighlights::find($request->id);
            if ($highlight) {
                $highlight->status = $request->status;
                $highlight->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Status updated successfully'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    /**
     * Delete Package Highlights
     */
    public function delete($package_id = null, $id = null)
    {
        try {
            $highlight = PackageHighlights::with(['gallery', 'sightseeing', 'propertyPhotos'])
                ->where('id', $id)
                ->where('package_id', $package_id)
                ->first();
                
            if (!$highlight) {
                return redirect()->back()->with('error', 'Package highlights not found');
            }
            
            // Delete main image
            if ($highlight->highlights_image && file_exists(public_path('uploads/highlights/' . $highlight->highlights_image))) {
                unlink(public_path('uploads/highlights/' . $highlight->highlights_image));
            }
            
            // Delete gallery images
            foreach ($highlight->gallery as $gallery) {
                if (file_exists(public_path('uploads/highlights/gallery/' . $gallery->image))) {
                    unlink(public_path('uploads/highlights/gallery/' . $gallery->image));
                }
                $gallery->delete();
            }
            
            // Delete sightseeing images
            foreach ($highlight->sightseeing as $sightseeing) {
                if (file_exists(public_path('uploads/highlights/sightseeing/' . $sightseeing->image))) {
                    unlink(public_path('uploads/highlights/sightseeing/' . $sightseeing->image));
                }
                $sightseeing->delete();
            }
            
            // Delete property photos
            foreach ($highlight->propertyPhotos as $property) {
                if (file_exists(public_path('uploads/highlights/property/' . $property->image))) {
                    unlink(public_path('uploads/highlights/property/' . $property->image));
                }
                $property->delete();
            }
            
            // Delete the main record
            $highlight->delete();
            
            return redirect()->route('domestic.package.highlights.list', $package_id)
                ->with('success', 'Package highlights deleted successfully');
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    
    /**
     * Create directories if they don't exist
     */
    private function createDirectories()
    {
        $paths = [
            public_path('uploads/highlights'),
            public_path('uploads/highlights/gallery'),
            public_path('uploads/highlights/sightseeing'),
            public_path('uploads/highlights/property'),
        ];
        
        foreach ($paths as $path) {
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
        }
    }
    
    
    
        public function deleteHighlight(Request $request){
            
        try {
            $highlight = PackageHighlights::with(['gallery', 'sightseeing', 'propertyPhotos'])
                ->where('id', $request->id)
                ->first();
                
            if (!$highlight) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package highlights not found'
                ]);
            }
            
            // Delete main image
            if ($highlight->highlights_image && file_exists(public_path('uploads/highlights/' . $highlight->highlights_image))) {
                unlink(public_path('uploads/highlights/' . $highlight->highlights_image));
            }
            
            // Delete gallery images
            foreach ($highlight->gallery as $gallery) {
                if (file_exists(public_path('uploads/highlights/gallery/' . $gallery->image))) {
                    unlink(public_path('uploads/highlights/gallery/' . $gallery->image));
                }
                $gallery->delete();
            }
            
            // Delete sightseeing images
            foreach ($highlight->sightseeing as $sightseeing) {
                if (file_exists(public_path('uploads/highlights/sightseeing/' . $sightseeing->image))) {
                    unlink(public_path('uploads/highlights/sightseeing/' . $sightseeing->image));
                }
                $sightseeing->delete();
            }
            
            // Delete property photos
            foreach ($highlight->propertyPhotos as $property) {
                if (file_exists(public_path('uploads/highlights/property/' . $property->image))) {
                    unlink(public_path('uploads/highlights/property/' . $property->image));
                }
                $property->delete();
            }
            
            // Delete the main record
            $highlight->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Package highlights deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get Single Highlight Data for Edit
     */
    public function getHighlightData($id)
    {
        try {
            $highlight = PackageHighlights::with(['gallery', 'sightseeing', 'propertyPhotos'])
                ->find($id);
                
            if ($highlight) {
                return response()->json([
                    'success' => true,
                    'data' => $highlight
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}