<?php

namespace App\Http\Controllers;

use App\Item;
use App\Category;
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
            if ($this->validateOrderRequestProperties($request))
            {
                if ($request['order'] && $request['order_item'] &&
                    $this->checkOrderValues($request['order']) &&
                    $this->checkItemsValues($request['order_item']))
                {
                    if (!$order = Order::where('status', 0)->where('user_id', $this->user->id)->first())
                    {
                        $order = $this->createOrderEntity($request['order']);
                    }
                    if ($this->addItemsToOrder($order, $request['order_item']))
                    {
                        return response()->json(['message' => 'Success'], 200);
                    }
                }
                else if (!$request['order'] && $request['order_item'] && 
                         $this->checkItemsValues($request['order_item']))
                {
                    if (!$order = Order::where('status', 0)->where('user_id', $this->user->id)->first())
                    {
                        return response()->json(['error' => 'Bad request content'], 400);
                    }
                    if ($this->addItemsToOrder($order, $request['order_item']))
                    {
                        return response()->json(['message' => 'Success'], 200);
                    }
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
            // next step, add where status is different then 0
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
    
    private function validateOrderRequestProperties(Request $request)
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
    
    private function checkOrderValues($orderProperties)
    {
        if (!ctype_digit($orderProperties['status']) || !ctype_digit($orderProperties['vendor_id']) ||
            (is_numeric($orderProperties['status']) && floor($orderProperties['status']) != $orderProperties['status']) || 
            (is_numeric($orderProperties['vendor_id']) && floor($orderProperties['vendor_id']) != $orderProperties['vendor_id']))
        {
            return false;
        }
        if (!((int)$orderProperties['status'] == 0))
        {
            return false;
        }
        if (!((int)$orderProperties['vendor_id'] > 0))
        {
            return false;
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
        foreach ($orderItemProperties as $item) 
        {
            if ($this->checkIfItemBelongsToVendor($item['item_id'], $order->vendor_id) && 
                $itemObject = Item::where('id', $item['item_id'])->first())
            {
                if (!$orderItem = OrderItem::where('order_id', $order->id)->where('item_id', $item['item_id'])->first())
                {
                    $orderItem = new OrderItem();
                    $orderItem->quantity = $item['quantity'];
                    $orderItem->item_id = $itemObject->id;
                    $orderItem->order_id = $order->id;
                }
                else
                {
                    if ($item['quantity'] == 0)
                    {                        
                        $orderItem->delete();
                    }
                    else
                    {
                        $orderItem->quantity = $item['quantity'];
                    }
                }

                if ($item['quantity'] != 0)
                {
                    $orderItem->save();
                }
            }
        }
        
        $this->countOrderExecutionTimeAndTotalPrice($order);
        
        return true;
    }
    
    private function checkIfItemBelongsToVendor($itemId, $vendorId)
    {
        $exist = false;
        
        if ($categories = Category::where('vendor_id', $vendorId)->get())
        {
            foreach ($categories as $category)
            {
                if ($items = Item::where('category_id', $category->id)->get())
                {
                    foreach ($items as $item)
                    {
                        if ($item->id == $itemId)
                        {
                            $exist = true;
                        }
                    }
                }
            }
        }
        
        if ($exist)
        {
            return true;
        }
        return false;
    }
    
    private function countOrderExecutionTimeAndTotalPrice(Order $order)
    {
        if ($order != null)
        {
            $executionTime = 0;
            $totalPrice = 0;
            
            if ($itemOrders = OrderItem::where('order_id', $order->id)->get())
            {
                foreach ($itemOrders as $itemOrder)
                {
                    if ($item = Item::where('id', $itemOrder->item_id)->first())
                    {
                        $executionTime += ($item->manufacture_time * $itemOrder->quantity);
                        $totalPrice += ($item->price * $itemOrder->quantity);
                    }
                }
            }
            
            $order->execution_time = $executionTime;
            $order->price = $totalPrice;

            $order->save();
            
            return true;
        }
        return false;
    }
}
