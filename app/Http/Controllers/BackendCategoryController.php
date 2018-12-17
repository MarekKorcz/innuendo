<?php

namespace App\Http\Controllers;

use App\Category;
use App\Vendor;
use App\Http\Request\CategoryStore;
use App\Http\Request\CategoryUpdate;
use App\Http\Controllers\Controller;

/**
 * Class BackendCategoryController
 * @package App\Http\Controllers
 */
class BackendCategoryController extends Controller
{
    /**
     * Logged vendor
     *
     * @var type 
     */
    private $vendor;
    
    /**
     * BackendCategoryController constructor.
     */
    public function __construct()
    {
        $this->middleware('vendor.auth');
        
        if (auth()->user() !== null) {
            $this->vendor = Vendor::where('user_id', auth()->user()->id)->first();
        }
    }

    /**
     * SHOW vendor CATEGORIES
     * 
     * @return Category[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        if ($this->vendor !== null) 
        {
            $categories = Category::where('vendor_id', $this->vendor->id)->get();

            return $categories;
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * SHOW vendor CATEGORY
     * 
     * @param Category $category
     *
     * @return Category
     */
    public function show(Category $category)
    {
        if ($this->vendor !== null && $category !== null) 
        {
            $category = Category::where([
                'id' => $category->id,
                'vendor_id' => $this->vendor->id
            ])->first();

            return $category;
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    /**
     * CREATE new CATEGORY attached to VENDOR entity
     * 
     * @param CategoryStore $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryStore $request)
    {
        $validated = $request->validated();
        
        if ($this->vendor !== null) 
        {
            $validated['slug'] = str_slug($validated['name']);
            
            if (!Category::where('vendor_id', $this->vendor->id)->where('slug', $validated['slug'])->first())
            {
                $validated['vendor_id'] = $this->vendor->id;
                $category = Category::create($validated);

                return response()->json($category, 201);
            }
            return response()->json(['error' => 'Resource already exists'], 409);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * UPDATE vendor CATEGORY PROPERTIES
     * 
     * @param CategoryUpdate $request
     * @param Category $category
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryUpdate $request, Category $category)
    {
        $validated = $request->validated();
        
        if ($this->vendor !== null && $category !== null) 
        {
            $category = Category::where([
                'id' => $category->id,
                'vendor_id' => $this->vendor->id
            ])->first();
            $category->update($validated);
            
            return response()->json($category, 200);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * DELETE vendor CATEGORY

     * @param Category $category
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Category $category)
    {
        if ($this->vendor !== null && $category !== null) 
        {
            $category = Category::where([
                'id' => $category->id,
                'vendor_id' => $this->vendor->id
            ])->first();
            $category->delete();
            
            return response()->json(null, 204);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
