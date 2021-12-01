<?php

namespace App\Http\Controllers;
use App\User;
use App\invoices;
use App\sections;
use App\invoices_details;
use App\invoices_attachments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use App\Notifications\AddInvoice;
use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;
class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //echo 'Hi Invoice';
        $invoices = invoices::all();
        return view('invoices.invoices', compact('invoices'));
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

        //$user = User::first();
        //$user->notify(new AddInvoice($invoice_id));
        //Notification::send($user, new AddInvoice($invoice_id));
        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $invoices = invoices::where('id', $id)->first();
      return view('invoices.status_update', compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $invoices = invoices::where('id', $id)->first();
      $sections = sections::all();
      return view('invoices.edit_invoice', compact('sections', 'invoices'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $invoices = invoices::findOrFail($request->invoice_id);
      $invoices->update([
          'invoice_number'    => $request->invoice_number,
          'invoice_date'      => $request->invoice_date,
          'due_date'          => $request->due_date,
          'product'           => $request->product,
          'section_id'        => $request->Section,
          'amount_collection' => $request->amount_collection,
          'amount_commission' => $request->amount_commission,
          'discount'          => $request->discount,
          'value_vat'         => $request->value_vat,
          'rate_vat'          => $request->rate_vat,
          'total'             => $request->total,
          'note'              => $request->note,
      ]);
      session()->flash('Edit', 'تم تعديل الفاتورة بنجاح');
      return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      $id       = $request->invoice_id;
      $invoices = invoices::where('id', $id)->first();
      $Details  = invoices_attachments::where('invoice_id', $id)->first();
      $id_page  = $request->id_page;
      if(!$id_page == 2){
        if(!empty($Details->invoice_number)) {
           Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
        }
        Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
        $invoices->Delete(); //forceDelete -> delete from DB
        session()->flash('Delete', 'تم حذف الفاتورة بنجاح!');
        return redirect('invoices');
      }else{
        $invoices->delete();
        session()->flash('invoice_archive');
        return redirect('archive');
      }
    }

    public function getproducts($id)
    {
      $products = DB::table("products")->where("section_id", $id)->pluck("product_name", "id");
      return json_encode($products);
    }

    public function update_status($id, Request $request)
    {
      // var_dump($request->section);
      // exit;
      $invoices = invoices::findOrFail($id);
      if($request->status === 'مدفوعة'){
        $invoices->update([
          'value_status' => 1,
          'status'       => $request->status,
          'payment_date' => $request->payment_date,
        ]);
        invoices_details::create([
          'invoice_id'     => $request->invoice_id,
          'invoice_number' => $request->invoice_number,
          'product'        => $request->product,
          'section'        => $request->section,
          'status'         => $request->status,
          'value_status'   => 1,
          'note'           => $request->note,
          'payment_date'   => $request->payment_date,
          'user'           => (Auth::user()->name),
        ]);
      }else{
        $invoices->update([
          'value_status' => 3,
          'status'       => $request->status,
          'payment_date' => $request->payment_date,
        ]);
        invoices_details::create([
          'invoice_id'     => $request->invoice_id,
          'invoice_number' => $request->invoice_number,
          'product'        => $request->product,
          'section'        => $request->section,
          'status'         => $request->status,
          'value_status'   => 3,
          'note'           => $request->note,
          'payment_date'   => $request->payment_date,
          'user'           => (Auth::user()->name),
        ]);
      }
      session()->flash('status_update');
      return redirect('/invoices');
    }

    public function invoices_paid()
    {
      $invoices = invoices::where('value_status', 1)->get();
      return view('invoices.invoices_paid', compact('invoices'));
    }

    public function invoices_unpaid()
    {
      $invoices = invoices::where('value_status', 2)->get();
      return view('invoices.invoices_unpaid', compact('invoices'));
    }

    public function invoices_partial()
    {
      $invoices = invoices::where('value_status', 3)->get();
      return view('invoices.invoices_partial', compact('invoices'));
    }

    public function invoice_print($id)
    {
      $invoice = invoices::where('id', $id)->first();
      return view('invoices.invoice_print', compact('invoice'));
    }

    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }
}
