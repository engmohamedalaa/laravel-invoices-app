<?php

namespace App\Http\Controllers;

use App\products;
use App\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sections = sections::all();
        $products = products::all();
        return view('products.products', compact('sections', 'products'));
        //return view('products.products')->with('products', $products)->with('sections', $sections);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        //dd($_POST);
        products::create([
          'product_name' => $request->product_name,
          'section_id'   => $request->section_id,
          'description'  => $request->description
        ]);
        session()->flash('Add', 'تم اضافة المنتج بنجاح.');
        return redirect('products');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, products $products)
    {
        $id        = $request->id;
        $products  = products::findOrFail($id);
        $products->update([
          'product_name' => $request->product_name,
          'section_id'   => $request->section_id,
          'description'  => $request->description
        ]);
        session()->flash('Edit', 'تم تعديل المنتج بنجاح.');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $products = products::findOrFail($request->id);
      $products->delete();
      session()->flash('Delete', 'تم حذف المنتج بنجاح.');
      return back();
    }
}
