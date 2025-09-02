<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageContent;

class PageContentController extends AdminBaseController
{
    public function index()
    {
        $pages = Page::with('PageContent')->get();
        return view('admin.pages.index',compact('pages'));
    }

    public function editPageContent($slug)
    {
        $page = Page::with('PageContent')->where('slug',$slug)->first();
        
        return view('admin.pages.edit',compact('page'));
    }

    public function update(Request $request)
    {
        $page_id = $request->page_id;
        $request->validate([
            'content_title' => 'required|string|max:255',
            'page_slug' => 'nullable|string|unique:pages,slug,' . $page_id . '|max:255',
            'content' => 'required|string',
        ]);
 
        // Check if a page already exists with the provided slug
        $page = Page::firstOrCreate(
            ['slug' => $request->page_slug],
            ['publish' => 'published']
        );
    
        // Create or update PageContent
        $pageContent = PageContent::updateOrCreate(
            ['page_id' => $page->id],
            ['name' => $request->content_title, 'page_content' => $request->content]
        );
    
        // Flash success message
        $request->session()->flash('success', 'Page updated successfully');

        // Redirect to the rides page
        return redirect()->route('page-content');
    }
    

}
