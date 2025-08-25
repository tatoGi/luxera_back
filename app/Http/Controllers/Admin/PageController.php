<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use RalphJSmit\Laravel\SEO\Facades\SEO;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Requests\StorePageRequest;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Factory|View
    {
        $pages = Page::where('parent_id', null)->orderBy('sort', 'asc')->with('children')->get();

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sectionTypes = sectionTypes();

        return view('admin.pages.create', compact('sectionTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StorePageRequest $request)
    {
        $data = $request->all();
    
        foreach (config('app.locales') as $locale) {
            if ($data[$locale]['slug'] != '') {
                $data[$locale]['slug'] = str_replace(' ', '-', $data[$locale]['slug']);
            }
        }
    
        $page = Page::create($data);
    
        foreach (config('app.locales') as $locale) {
            if ($data[$locale]['slug'] != '') {
                $data[$locale]['slug'] = str_replace(' ', '-', $data[$locale]['slug']);
            }
            
            $seo = $page->translate($locale)->seo;
            
            // Prepare SEO data
            $seoData = [
                'title' => $data[$locale]['title'],
                'description' => $data[$locale]['desc'],
            ];     
    
            // Update SEO data
            $seo->update($seoData);
        }
    
        return redirect()->route('pages.index', [app()->getLocale()]);
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
        $page = Page::findOrFail($id);
        $sectionTypes = SectionTypes();
        return view('admin.pages.edit', compact('page','sectionTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        foreach (config('app.locales') as $locale) {
            if ($data[$locale]['slug'] != '') {
                $data[$locale]['slug'] = str_replace(' ', '-', $data[$locale]['slug']);
            }
        }
        $page = Page::find($id)->update($data);

        return redirect()->route('pages.index', [app()->getLocale()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect()->route('pages.index', app()->getLocale());
    }

    public function arrange(Request $request)
    {
       
        $array = $request->input('orderArr');
        
        Page::rearrange($array);
        

        return ['error' => false];
    }
}
