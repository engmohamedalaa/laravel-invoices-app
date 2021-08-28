<?php

namespace App\Http\Controllers;

use App\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSections;
class SectionsController extends Controller
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
        return view('sections.sections', compact('sections'));
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
    public function store(StoreSections $request) //Request
    {
        //store data
        //dd($_POST);
        $input = $request->all();
        //inputes validation
        /*$validatedData = $request->validate([
            'section_name' => 'required|unique:sections|max:255',
            //'description'  => 'required',
        ],
        [
          'section_name.required' => 'يرجي ادخال اسم القسم!',
          'section_name.unique'   => 'عفوا هذا القسم موجود مسبقا!',
          //'description.required'  => 'يرجي ادخال الملاحظات!',
        ]);*/
        // Retrieve the validated input data...
        $validated = $request->validated();
        //check if section exist before
        //$s_exists = sections::where('section_name', '=', $input['section_name'])->exists();
        //session()->flash('Error', 'عفوا هذا القسم موجود مسبقا!');
        sections::create([
          'section_name' => $request->section_name,
          'description'  => $request->description,
          'created_by'   => (Auth::user()->name),
        ]);
        return redirect('sections');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\sections  $sections
     * @return \Illuminate\Http\Response
     */
    public function show(sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\sections  $sections
     * @return \Illuminate\Http\Response
     */
    public function edit(sections $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\sections  $sections
     * @return \Illuminate\Http\Response
     */
    public function update(StoreSections $request, sections $sections)
    {
        //dd($_POST);
        $id = $request->id;
        // Retrieve the validated input data...
        $validated = $request->validated();
        $sections  = sections::find($id);
        $sections->update([
          'section_name' => $request->section_name,
          'description'  => $request->description
        ]);
        session()->flash('Edit', 'تم تحديث بيانات القسم بنجاح.');
        return redirect('sections');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\sections  $sections
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $id = $request->id;
        sections::find($id)->delete();
        session()->flash('Delete', 'تم حذف القسم بنجاح.');
        return redirect('sections');
    }
}
