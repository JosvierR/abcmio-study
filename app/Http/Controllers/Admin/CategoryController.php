<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\StoreChildCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use \Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('parent_id',0)->orderBy('name','asc')->get();
        return view('admin.categories.index',compact('categories'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $storeCategoryRequest)
    {

        $category = new Category();
        $category->fill($storeCategoryRequest->all());
        $category->save();

        if($category->parent_id>0)
            return redirect('/admin/category/'.$storeCategoryRequest->parent_id)->with('success','Su categoría fue creada correctamente');
        return redirect('/admin/categories')->with('success','Su categoría fue creada correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $parent = $category;
        $category = new Category();
        return view('admin.categories.create',compact('category','parent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $updateCategoryRequest, Category $category)
    {
        $category->update($updateCategoryRequest->all());

//        $slug = Str::slug($updateCategoryRequest->name);
//        $i = 1;
//
//        while(Category::where('slug',$slug)->first())
//        {
//            $slug = Str::slug($updateCategoryRequest->name)."-".$i++;
//        }
//
//        $category->slug = $slug;
        $category->save();

        return redirect('/admin/categories')->with('success','Su categoría fue actualizada correctamente');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if(\Auth::user()->type ==='admin' ||\Auth::user()->type ==='super')
        {
            if(!$category->children->count())
            {
                $category->delete();
                return redirect('admin/categories')->with('success','Categoria eliminada');
            }
                return redirect('admin/categories')->with('error','Esta categoría no puede ser eliminada por que tiene hijos.');
        }
            return redirect('admin/categories')->with('success','Categoria eliminada ;) ');
    }

    public function child(Category $category)
    {
        $categories = Category::where('parent_id',$category->id)->orderBy('name','asc')->get();

        return view('admin.categories.index',compact('categories','category'));
    }
    public function edit_child()
    {

    }
    public function create_child()
    {

    }

    public function store_child(StoreChildCategoryRequest $storeChildCategoryRequest, Category $category)
    {

    }
//    public function update_child(UpdateCategoryRequest $updateCategoryRequest,Category $category, Category $subcategory)
//    {
//
//    }
}
