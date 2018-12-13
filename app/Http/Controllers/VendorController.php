<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\Order;
use App\Http\Request\VendorStore;
use App\Http\Request\VendorUpdate;
use App\Http\Request\OrderUpdate;
use App\Http\Controllers\Controller;

/**
 * Class VendorController
 * @package App\Http\Controllers
 */
class VendorController extends Controller
{
    /**
     * Logged user
     *
     * @var type 
     */
    private $user;
    
    /**
     * VendorController constructor.
     */
    public function __construct()
    {
        $this->middleware('jwt.auth');
        
        if (auth()->user() !== null) {
            $this->user = auth()->user();
        }
    }
    
    /**
     * CREATE new VENDOR ENTITY
     * 
     * @param VendorStore $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(VendorStore $request)
    {
        $validated = $request->validated();
        
        $vendor = Vendor::create($validated);

        return response()->json($vendor, 201);
    }

    /**
     * UPDATE vendor PROPERTIES
     * 
     * @param VendorUpdate $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(VendorUpdate $request)
    {
        $validated = $request->validated();
        
        if ($this->user !== null) {
            $vendor = Vendor::where('user_id', $this->user->id)->first();

            $vendor->update($validated);

            return response()->json($vendor, 200);
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    /**
     * SHOW vendor PROPERTIES
     * 
     * @return Vendor
     */
    public function show()
    {
        if ($this->user !== null) {
            $vendor = Vendor::where('user_id', $this->user->id)->first();        

            return $vendor;
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    /**                             ORDERS                                    */
    
    /**
     * SHOW ALL ORDERS attached to VENDOR
     * 
     * @return Order
     */
    public function orders()
    {
        if ($this->user !== null) {
            $vendor = Vendor::where('user_id', $this->user->id)->first();
            
            if ($vendor !== null) {
                $orders = Order::where('vendor_id', $vendor->id)->get();

                return $orders;
            }
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    /**
     * SHOW ORDER attached to VENDOR
     * 
     * @param Order $order
     * 
     * @return Order
     */
    public function showOrder(Order $order)
    {
        if ($this->user !== null && $order !== null) {
            $vendor = Vendor::where('user_id', $this->user->id)->first();  
            
            if ($vendor !== null) {
                $order = Order::where([
                    'id' => $order->id,
                    'vendor_id' => $vendor->id
                ])->get();

                return $order;
            }
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    /**
     * UPDATE ORDER attached to VENDOR
     * 
     * @param OrderUpdate $request
     * @param Order $order
     * 
     * @return Order
     */
    public function updateOrder(OrderUpdate $request, Order $order)
    {
        $validated = $request->validated();
        
        if ($this->user !== null && $order !== null) {
            $vendor = Vendor::where('user_id', $this->user->id)->first();  
            
            if ($vendor !== null) {
                $order = Order::where([
                    'id' => $order->id,
                    'vendor_id' => $vendor->id
                ])->get();
                
                if ($order !== null) {
                
                    $order->update($validated);

                    return response()->json($order, 200);
                }
            }
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    /**
     * DELETE vendor ORDER

     * @param Order $order
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function deleteOrder(Order $order)
    {
        if ($this->user !== null && $order !== null) {
            $vendor = Vendor::where('user_id', $this->user->id)->first();  
            
            if ($vendor !== null) {
                $order = Order::where([
                    'id' => $order->id,
                    'vendor_id' => $vendor->id
                ])->first();
                
                if ($order !== null) {

                    $order->delete();
                    return response()->json(null, 204);
                }
            }
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
