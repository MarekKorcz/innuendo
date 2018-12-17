<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Http\Request\OrderStore;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class OrderController
 * @package App\Http\Controllers
 */
class OrderController extends Controller
{
    /**
     * Logged user
     *
     * @var type 
     */
    private $user;
    
    /**
     * OrderController constructor.
     */
    public function __construct()
    {
        $this->middleware('jwt.auth');
        
        if (auth()->user() !== null) {
            $this->user = auth()->user();
        }
    }

    /**
     * SHOW user ORDERS
     * 
     * @return Order[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        if ($this->user !== null) {
            $orders = Order::where('user_id', $this->user->id)->get();

            return $orders;
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * SHOW user ORDER
     * 
     * @param Order $order
     *
     * @return Order
     */
    public function show(Order $order)
    {
        if ($this->user !== null && $order !== null) {
            $order = Order::where([
                'id' => $order->id,
                'vendor_id' => $this->user->id
            ])->first();

            return $order;
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    /**
     * CREATE new ORDER attached to USER entity
     * 
     * @param OrderStore $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($this->user !== null) 
        {
            if ($orderItems = $request['order_item']) 
            {
                unset($request['order_item']);
                $order = $request;
                
                $order['user_id'] = $this->user->id;
                
                // iterate through items and add their total manufacture time
                // sign it to order manufacture time
                // and so on
                
                return $order;
            
//                $order = Order::create($validated);
//
//                $orderItems = $validated['orderItems'];
//
//                foreach ($orderItems as $item) {
//                    $orderItem = new OrderItem();
//                    $orderItem->setQuantity($item['quantity']);
//                    $orderItem->setItemId($item['item_id']);
//                    $orderItem->setOrderId($order->id);
//                    $orderItem->save();
//                }
//
//                return response()->json($order, 201);
            } 
            else 
            {
                return response()->json(['error' => 'Missing crucial parameters'], 422);
            }
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
