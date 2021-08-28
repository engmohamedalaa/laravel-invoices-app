<?php

namespace App\Http\Controllers;

use App\invoices;
use App\sections;
use App\invoices_details;
use App\invoices_attachments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //echo 'Hi Invoice';
        return view('invoices.invoices');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $sections = sections::all();
        return view('invoices.add_invoice', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        invoices::create([
          'invoice_number'    => $request->invoice_number,
          'invoice_date'      => $request->invoice_date,
          'due_date'          => $request->due_date,
          'product'           => $request->product,
          //'section'           => section->section_name,
          'section_id'        => $request->section_id,
          'amount_collection' => $request->amount_collection,
          'amount_commission' => $request->amount_commission,
          'discount'          => $request->discount,
          'value_vat'         => $request->value_vat,
          'rate_vat'          => $request->rate_vat,
          'total'             => $request->total,
          'status'            => 'غير مدفوعة',
          'value_status'      => 2,
          'note'              => $request->note,
          'user'              => (Auth::user()->name)
        ]);
        $invoice_id = invoices::latest()->first()->id;
        invoices_details::create([
            'invoice_id'     => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product'        => $request->product,
            'section'        => $request->section_id,
            'status'         => 'غير مدفوعة',
            'value_status'   => 2,
            'note'           => $request->note,
            'user'           => (Auth::user()->name),
        ]);
        if($request->hasFile('pic')){
          $image                       = $request->file('pic');
          $file_name                   = $image->getClientOriginalName();
          $invoice_number              = $request->invoice_number;
          $attachments                 = new invoices_attachments();
          $attachments->file_name      = $file_name;
          $attachments->invoice_number = $invoice_number;
          $attachments->created_by     = Auth::user()->name;
          $attachments->invoice_id     = $invoice_id;
          $attachments->save();
          // move pic
          $imageName = $request->pic->getClientOriginalName();
          $request->pic->move(public_path('uploads/attachments/' . $invoice_number), $imageName);
        }
        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show(invoices $invoices)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit(invoices $invoices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, invoices $invoices)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy(invoices $invoices)
    {
        //
    }

    public function getproducts($id)
    {
      $products = DB::table("products")->where("section_id", $id)->pluck("product_name", "id");
      return json_encode($products);
    }
}
