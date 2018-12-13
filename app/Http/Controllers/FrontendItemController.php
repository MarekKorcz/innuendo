<?php

namespace App\Http\Controllers;

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
     * @param string $vendorName
     * 
     * @return Item[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index($vendorName)
    {
        if ($vendorName !== null) {
            $vendor = Vendor::where('name', $vendorName)->first();
            
            if ($vendor !== null) {
                $items = Item::where('vendor_id', $vendor->id)->get();

                return $items;
            }
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * SHOW VENDOR ITEM to CUSTOMER
     * 
     * @param Item $item
     *
     * @return Item
     */
    public function show(Item $item)
    {
        if ($item !== null) {
            return $item;
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
