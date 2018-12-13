<?php

namespace App\Http\Controllers;

use App\Item;
use App\Vendor;
use App\Http\Request\ItemStore;
use App\Http\Request\ItemUpdate;
use App\Http\Controllers\Controller;

/**
 * Class BackendItemController
 * @package App\Http\Controllers
 */
class BackendItemController extends Controller
{
    /**
     * Logged vendor
     *
     * @var type 
     */
    private $vendor;
    
    /**
     * BackendItemController constructor.
     */
    public function __construct()
    {
        $this->middleware('vendor.auth');
        
        if (auth()->user() !== null) {
            $this->vendor = Vendor::where('user_id', auth()->user()->id)->first();
        }
    }

    /**
     * SHOW vendor ITEMS
     * 
     * @return Item[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        if ($this->vendor !== null) {
            $items = Item::where('vendor_id', $this->vendor->id)->get();

            return $items;
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * SHOW vendor ITEM
     * 
     * @param Item $item
     *
     * @return Item
     */
    public function show(Item $item)
    {
        if ($this->vendor !== null && $item !== null) {
            $item = Item::where([
                'id' => $item->id,
                'vendor_id' => $this->vendor->id
            ])->first();

            return $item;
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    /**
     * CREATE new ITEM attached to VENDOR entity
     * 
     * @param ItemStore $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ItemStore $request)
    {
        $validated = $request->validated();
        
        if ($this->vendor !== null) {
            $validated['vendor_id'] = $this->vendor->id;
            $item = Item::create($validated);

            return response()->json($item, 201);
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * UPDATE vendor ITEM PROPERTIES
     * 
     * @param ItemUpdate $request
     * @param Item $item
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ItemUpdate $request, Item $item)
    {
        $validated = $request->validated();
        
        if ($this->vendor !== null && $item !== null) {
            $item = Item::where([
                'id' => $item->id,
                'vendor_id' => $this->vendor->id
            ])->first();

            $item->update($validated);
            return response()->json($item, 200);
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * DELETE vendor ITEM

     * @param Item $item
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Item $item)
    {
        if ($this->vendor !== null && $item !== null) {
            $item = Item::where([
                'id' => $item->id,
                'vendor_id' => $this->vendor->id
            ])->first();

            $item->delete();
            return response()->json(null, 204);
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
