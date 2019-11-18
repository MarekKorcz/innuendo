<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    
    public function create()
    {
        return view('category.create');
    }

    public function store()
    {        
        $rules = array(
            'name'           => 'required',
            'description'    => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('category/create')
                ->withErrors($validator);
        } else {
            
            $category = new Category();
            $category->name = Input::get('name');
            $category->slug = str_slug(Input::get('name'));
            $category->description = Input::get('description');
            $category->save();

            return redirect('category/show/' . $category->id)->with('success', 'Kategoria została utworzona');
        }
    }
    
    public function show($id)
    {        
        $category = Category::where('id', $id)->with('items')->first();
        
        if ($category !== null)
        {
            return view('category.show')->with('category', $category);
        }
        
        return redirect()->route('welcome')->with('error', 'Kategoria nie istnieje');
    }
    
    public function edit($id)
    {
        $category = Category::where('id', $id)->first();
        
        if ($category !== null)
        {
            return view('category.edit')->with('category', $category);
        }
        
        return redirect()->route('welcome')->with('error', 'Kategoria nie istnieje');
    }
    
    public function update()
    {
        $rules = array(
            'name'           => 'required',
            'slug'           => 'required',
            'description'    => 'required',
            'category_id'    => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('category/' . Input::get('category_id') . '/edit')
                ->withErrors($validator);
        } else {
            
            $category = Category::where('id', Input::get('category_id'))->first();
            
            if ($category !== null)
            {
                $category->name = Input::get('name');
                $category->slug = Input::get('slug');
                $category->description = Input::get('description');
                $category->save();
                
                return redirect('category/show/' . $category->id)->with('success', 'Kategoria została zaktualizowana');
            }

            return redirect()->route('welcome')->with('error', 'Kategoria nie istnieje');
        }
    }
    
    public function index()
    {
        $categories = Category::all();
        
        if (count($categories) > 0)
        {            
            return view('category.index')->with([
                'categories' => $categories
            ]);
            
        } else if (count($categories) == 0) {
            
            return redirect()->action(
                'CategoryController@create'
            );
        }
    }
}