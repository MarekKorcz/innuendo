<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\Http\Controllers\Controller;

/**
 * Class AdminController
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
//        $this->middleware('jwt.auth');
    }
    
    /**
     * Returns all available vendors
     * 
     * @return Vendor[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Vendor::all();
    }
    
    /**
     * @param Vendor $vendor
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Vendor $vendor)
    {
        $vendor->delete();

        return response()->json(null, 204);
    }
}
