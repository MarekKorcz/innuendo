<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('item/create/' . request()->input('category_id'))
                ->withErrors($validator);
        } else {

            $item = new Item();
            $item->name = request()->input('name');
            $item->slug = Str::slug(request()->input('name'));
            $item->description = request()->input('description');
            $item->minutes = request()->input('minutes');
            $item->price = request()->input('price');
            $item->category_id = request()->input('category_id');
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
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('item/' . request()->input('item_id') . '/edit')
                ->withErrors($validator);
        } else {

            $item = Item::where('id', request()->input('item_id'))->first();

            if ($item !== null)
            {
                $item->name = request()->input('name');
                $item->slug = request()->input('slug');
                $item->description = request()->input('description');
                $item->minutes = request()->input('minutes');
                $item->price = request()->input('price');
                $item->save();

                return redirect('item/show/' . $item->id)->with('success', 'Masaż został zaktualizowany');
            }

            return redirect()->route('welcome')->with('error', 'Masaż nie istnieje');
        }
    }
}
