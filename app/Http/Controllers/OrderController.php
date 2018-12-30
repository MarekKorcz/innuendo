<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
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
     * HANDLES an ORDER presence and ADDS ITEMS to it
     */
    public function add(Request $request)
    {
        if ($this->user !== null)
        {
            if ($this->validateOrderRequest($request))
            {
                if ($this->checkItemsValues($request['order_item']))
                {
                    return ['it works'];
                    // fix statements for two conditions
                    // 1. request with order and item order
                    // 2. request with item order, alone
//                    if ($request['order'])
//                    {
//                        if (!$order = Order::where('status', 0)->where('user_id', $this->user->id)->first())
//                        {
//                            $order = $this->createOrderEntity($request['order']);
//                        }
//                        return $this->addItemsToOrder($order, $request['order_item']);
//                    }
                }
            }
            return response()->json(['error' => 'Bad request content'], 400);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
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
    
    private function validateOrderRequest(Request $request)
    {
        $keys = array_keys($request->all());
        
        if (count($keys) == 2)
        {
            if (in_array('order', $keys) && in_array('order_item', $keys)) 
            {
                if (count($request['order']) == 2 && isset($request['order']['status']) && isset($request['order']['vendor_id']) &&
                    is_array($request['order_item']) && $this->validateItemOrderProperties($request['order_item']))
                {
                    return true;
                }
                return false;
            }
            else 
            {
                return false;
            }
        }
        else if (count($keys) == 1)
        {
            if (in_array('order_item', $keys)) 
            {
                if (is_array($request['order_item']) && $this->validateItemOrderProperties($request['order_item']))
                {
                    return true;
                }
                return false;
            }
            else 
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    
    private function validateItemOrderProperties($orderItems)
    {
        $counter = count($orderItems);
        
        for ($i = 0; $i < $counter; $i++)
        {
            if (!isset($orderItems[$i]['quantity']) || !isset($orderItems[$i]['item_id']))
            {
                return false;
            }
        }
        return true;
    }
    
    private function checkItemsValues($orderItemProperties)
    {
        foreach($orderItemProperties as $row => $value)
        {
            foreach($value as $innerRow => $innerValue)
            {
                if ((is_numeric($innerValue) && floor($innerValue) != $innerValue) || !ctype_digit($innerValue))
                {
                    return false;
                }
                if ($innerRow == "quantity")
                {
                    if (!((int)$innerValue >= 0))
                    {
                        return false;
                    }
                }
                if ($innerRow == "item_id")
                {
                    if (!((int)$innerValue > 0))
                    {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    private function createOrderEntity($orderProperties) 
    {
        if (is_int((int)$orderProperties['status']) && is_int((int)$orderProperties['vendor_id']) &&
            $orderProperties['status'] == 0 && $orderProperties['vendor_id'] > 0)
        {
            $status = (int)$orderProperties['status'];
            $vendorId = (int)$orderProperties['vendor_id'];
                    
            $order = new Order();
            $order->status = $status;
            $order->user_id = $this->user->id;
            $order->vendor_id = $vendorId;
            
            $order->save();
        
            return $order;
        }
        return false;
    }
    
    private function addItemsToOrder(Order $order, $orderItemProperties)
    {
        // add total time counter variable
        // add price variable
        
        foreach ($orderItemProperties as $item) 
        {
            // get each item from db, take preparation time, add to total time variable, same with prices
            
            if (!$orderItem = OrderItem::where('order_id', $order->id)->where('item_id', $item['item_id'])->first())
            {
                $orderItem = new OrderItem();
                $orderItem->quantity = $item['quantity'];
                $orderItem->item_id = $item['item_id'];
                $orderItem->order_id = $order->id;
            }
            else
            {
                $orderItem->quantity = $orderItem->quantity + $item['quantity'];
            }
            
            // if quantity = 0, delete this item order

            $orderItem->save();
        }
        
        // add total time and price to order

        return $orderItemProperties;
    }
}
