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
     * @param string $categoryName
     * 
     * @return Category[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index($categoryName)
    {
        if ($categoryName !== null) {
            $vendor = Vendor::where('name', $categoryName)->first();
            
            if ($vendor !== null) {
                $categories = Category::where('vendor_id', $vendor->id)->get();

                return $categories;
            }
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * SHOW VENDOR CATEGORY to CUSTOMER
     * 
     * @param Category $category
     *
     * @return Category
     */
    public function show(Category $category)
    {
        if ($category !== null) {
            return $category;
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
