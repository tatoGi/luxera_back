<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageOption;
use App\Models\PageOptionsImage;
use Illuminate\Http\Request;

class PageOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        // Find the page by its ID
        $page = Page::findOrFail($request->page_id);
        
        // Retrieve options associated with the page
        $options = PageOption::where('page_id', $page->id)->get();
        // Pass the options to the view
        return view('admin.options.index', compact('options', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $sectionTypes = sectionTypes();
        return view('admin.options.create', compact('sectionTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $page = Page::where('type_id', $data['type_id'])->first();
        $data['page_id'] = $page->id;
        $option = PageOption::create($data);
       
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $imageName = $image->getClientOriginalName();
                $path = $image->storeAs('/options', $imageName);
                $optionImage = new PageOptionsImage;
                $optionImage->image_name = $imageName; // Store the file path instead of the file name
                $optionImage->page_option_id = $option->id;
                $optionImage->save();
            }
        }
        return redirect()->route('options.index', app()->getLocale());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $option = PageOption::findOrFail($id);
        
        return view('admin.options.edit',compact('option'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        return redirect()->route('products.index', app()->getLocale());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $option = PageOption::findOrFail($id);
        $option->delete();

        return redirect()->route('options.index', app()->getLocale());
    }
}
