<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
use App\Vendor;
use App\Http\Controllers\Controller;

/**
 * Class FrontendItemController
 * @package App\Http\Controllers
 */
class FrontendItemController extends Controller
{
    /**
     * SHOW VENDOR ITEMS to CUSTOMER
     * 
     * @param string $vendorSlug
     * @param string $categorySlug
     * 
     * @return Item[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index($vendorSlug, $categorySlug)
    {
        if ($vendorSlug !== null) 
        {
            $vendor = Vendor::where([
                'slug' => $vendorSlug
            ])->first();
            
            if ($vendor !== null && $categorySlug !== null) 
            {
                $category = Category::where([
                    'slug' => $categorySlug,
                    'vendor_id' => $vendor->id
                ])->first();
                
                if ($category !== null)
                {
                    $items = Item::where([
                        'category_id' => $category->id
                    ])->get();

                    if ($items !== null)
                    {
                        return $items;
                    }
                }
            }
            return response()->json(['error' => 'Resource not found'], 404);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * SHOW VENDOR ITEM to CUSTOMER
     * 
     * @param string $vendorSlug
     * @param string $categorySlug
     * @param string $itemSlug
     *
     * @return Item
     */
    public function show($vendorSlug, $categorySlug, $itemSlug)
    {
        if ($vendorSlug !== null) 
        {
            $vendor = Vendor::where([
                'slug' => $vendorSlug
            ])->first();
            
            if ($vendor !== null && $categorySlug !== null) 
            {
                $category = Category::where([
                    'slug' => $categorySlug,
                    'vendor_id' => $vendor->id
                ])->first();
                
                if ($category !== null)
                {
                    $item = Item::where([
                        'slug' => $itemSlug,
                        'category_id' => $category->id
                    ])->first();

                    if ($item !== null)
                    {
                        return $item;
                    }
                }
            }
            return response()->json(['error' => 'Resource not found'], 404);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
