<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('category/create')
                ->withErrors($validator);
        } else {

            $category = new Category();
            $category->name = request()->input('name');
            $category->slug = Str::slug(request()->input('name'));
            $category->description = request()->input('description');
            $category->save();

            return redirect('category/show/' . $category->id)->with('success', 'Kategoria została utworzona');
        }
    }

    public function show($id)
    {
        $category = Category::where('id', $id)->with(array('items' => function($query) {
            $query->orderBy('minutes', 'ASC');
        }))->first();

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
        $validator = Validator::make(request()->all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('category/' . request()->input('category_id') . '/edit')
                ->withErrors($validator);
        } else {

            $category = Category::where('id', request()->input('category_id'))->first();

            if ($category !== null)
            {
                $category->name = request()->input('name');
                $category->slug = request()->input('slug');
                $category->description = request()->input('description');
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
