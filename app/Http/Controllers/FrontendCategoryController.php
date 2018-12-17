<?php

namespace App\Http\Controllers;

use App\Category;
use App\Vendor;
use App\Http\Controllers\Controller;

/**
 * Class FrontendCategoryController
 * @package App\Http\Controllers
 */
class FrontendCategoryController extends Controller
{
    /**
     * SHOW VENDOR CATEGORIES to CUSTOMER
     * 
     * @param string $vendorSlug
     * 
     * @return Category[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index($vendorSlug)
    {
        if ($vendorSlug !== null) 
        {
            $vendor = Vendor::where('slug', $vendorSlug)->first();
            
            if ($vendor !== null) 
            {
                return Category::where('vendor_id', $vendor->id)->first();
            }
        }        
        return response()->json(['error' => 'Resource not found'], 404);
    }

    /**
     * SHOW VENDOR CATEGORY to CUSTOMER
     * 
     * @param string $vendorSlug
     * @param string $categorySlug
     *
     * @return Category
     */
    public function show($vendorSlug, $categorySlug)
    {
        if ($vendorSlug !== null && $categorySlug !== null) 
        {
            $vendor = Vendor::where('slug', $vendorSlug)->first();
            
            if ($vendor !== null)
            {
                $category = Category::where([
                    'slug' => $categorySlug,
                    'vendor_id' => $vendor->id
                ])->first();
                
                if ($category !== null)
                {
                    return $category;
                }
            }
        }
        return response()->json(['error' => 'Resource not found'], 404);
    }
}
