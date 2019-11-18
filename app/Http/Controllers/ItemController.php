<?php

namespace App\Http\Controllers;

use App\Item;
use App\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    
    public function create($id)
    {
        $category = Category::where('id', $id)->first();
        
        if ($category !== null)
        {
            return view('item.create')->with('category', $category);
        }
    }

    public function store()
    {        
        $rules = array(
            'name'           => 'required',
            'description'    => 'required',
            'minutes'        => 'required',
            'price'          => 'required',
            'category_id'    => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('item/create/' . Input::get('category_id'))
                ->withErrors($validator);
        } else {
            
            $item = new Item();
            $item->name = Input::get('name');
            $item->slug = str_slug(Input::get('name'));
            $item->description = Input::get('description');
            $item->minutes = Input::get('minutes');
            $item->price = Input::get('price');
            $item->category_id = Input::get('category_id');
            $item->save();

            return redirect('item/show/' . $item->id)->with('success', 'Masaż został utworzony');
        }
    }
    
    public function show($id)
    {        
        $item = Item::where('id', $id)->with('category')->first();
        
        if ($item !== null)
        {
            return view('item.show')->with('item', $item);
        }
        
        return redirect()->route('welcome')->with('error', 'Masaż nie istnieje');
    }
    
    public function edit($id)
    {
        $item = Item::where('id', $id)->first();
        
        if ($item !== null)
        {
            return view('item.edit')->with('item', $item);
        }
        
        return redirect()->route('welcome')->with('error', 'Masaż nie istnieje');
    }
    
    public function update()
    {
        $rules = array(
            'name'           => 'required',
            'slug'           => 'required',
            'description'    => 'required',
            'minutes'        => 'required',
            'price'          => 'required',
            'item_id'        => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('item/' . Input::get('item_id') . '/edit')
                ->withErrors($validator);
        } else {
            
            $item = Item::where('id', Input::get('item_id'))->first();
            
            if ($item !== null)
            {
                $item->name = Input::get('name');
                $item->slug = Input::get('slug');
                $item->description = Input::get('description');
                $item->minutes = Input::get('minutes');
                $item->price = Input::get('price');
                $item->save();
                
                return redirect('item/show/' . $item->id)->with('success', 'Masaż został zaktualizowany');
            }

            return redirect()->route('welcome')->with('error', 'Masaż nie istnieje');
        }
    }
}