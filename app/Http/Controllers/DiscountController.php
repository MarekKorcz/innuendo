<?php

namespace App\Http\Controllers;

use App\Discount;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;

class DiscountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function create()
    {
        return view('discount.create');
    }
    
    public function store()
    {        
        $rules = array(
            'name'             => 'required',
            'name_en'          => 'required',
            'description'      => 'required',
            'description_en'   => 'required',
            'percent'          => 'required',
            'worker_threshold' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('admin/discount/create')
                ->withErrors($validator);
        } else {
            
            $discount = new Discount();
            $discount->name             = Input::get('name');
            $discount->name_en          = Input::get('name_en');
            $discount->slug             = str_slug(Input::get('name'));
            $discount->description      = Input::get('description');
            $discount->description_en   = Input::get('description_en');
            $discount->worker_threshold = Input::get('worker_threshold');
            $discount->percent          = Input::get('percent');
            $discount->save();

            return redirect('admin/discount/index')->with('success', 'Discount successfully created!');
        }
    }
    
//    public function show($id)
//    {        
//        $discount = Discount::where('id', $id)->first();
//        
//        if ($discount !== null)
//        {
//            return view('discount.show')->with('discount', $discount);
//        }
//        
//        return redirect()->route('welcome')->with('error', 'Such discount doesn\'t exist');
//    }
    
    public function index()
    {
        return view('discount.index')->with([
            'discounts' => Discount::where('id', '!=', null)->get()
        ]);
    }
    
    public function destroy($id)
    {
        $discount = Discount::where('id', $id)->first();
        
        if ($discount !== null)
        {
            $discount->delete();
        
            return redirect('/admin/discount/index')->with('success', 'Discount deleted!');
        }
        
        return redirect()->route('welcome')->with('error', 'Such discount doesn\'t exist');
    }
}