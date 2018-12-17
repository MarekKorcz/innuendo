<?php

namespace App\Http\Controllers;

use App\Item;
use App\Category;
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
     * @param string $categorySlug
     * @param Item $item
     *
     * @return Item
     */
    public function show($categorySlug, Item $item)
    {
        if ($this->vendor !== null && $item !== null) 
        {
            $category = Category::where([
                'slug' => $categorySlug,
                'vendor_id' => $this->vendor->id
            ])->first();
            
            if ($category !== null)
            {
                $item = Item::where([
                    'id' => $item->id,
                    'category_id' => $category->id
                ])->first();
                
                if ($item !== null)
                {
                    return $item;
                }
            }
            return response()->json(['error' => 'Resource not found'], 404);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    /**
     * CREATE new ITEM attached to VENDOR entity
     * 
     * @param ItemStore $request
     * @param string $categorySlug
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ItemStore $request, $categorySlug)
    {
        $validated = $request->validated();
        
        if ($this->vendor !== null && $categorySlug !== null) 
        {
            $category = Category::where([
                'slug' => $categorySlug,
                'vendor_id' => $this->vendor->id
            ])->first();
            
            if ($category !== null)
            {
                $validated['slug'] = str_slug($validated['name']);
                $validated['category_id'] = $category->id;
                
                if (!Item::where('slug', $validated['slug'])->where('category_id', $validated['category_id'])->first())
                {
                    $item = Item::create($validated);

                    return response()->json($item, 201);
                }
                return response()->json(['error' => 'Resource already exists'], 409);
            }
            return response()->json(['error' => 'Resource not found'], 404);
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
        
        if ($this->vendor !== null && $item !== null) 
        {
            $item = Item::where([
                'id' => $item->id
            ])->first();
            
            if ($item !== null)
            {
                $category = Category::where([
                    'id' => $item->category_id,
                    'vendor_id' => $this->vendor->id
                ])->first();
                
                if ($category !== null)
                {
                    $validated['slug'] = str_slug($validated['name']);
                    $item->update($validated);
                    
                    return response()->json($item, 200);
                }
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return response()->json(['error' => 'Resource not found'], 404);
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
        if ($this->vendor !== null && $item !== null) 
        {
            $item = Item::where([
                'id' => $item->id
            ])->first();
            
            if ($item !== null)
            {
                $category = Category::where([
                    'id' => $item->category_id,
                    'vendor_id' => $this->vendor->id
                ])->first();
                
                if ($category !== null)
                {
                    $item->delete();
                    return response()->json(null, 204);
                }
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return response()->json(['error' => 'Resource not found'], 404);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
