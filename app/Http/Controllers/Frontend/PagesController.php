<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Page;
use App\Models\PageContent;
use App\Models\Faqs;

class PagesController extends BaseController
{
    public function pageContend(Request $request,$slug)
    {
        $page = Page::with('PageContent')->where('slug',$slug)->first();
        $faqs = Faqs::all();

        return view('Frontend.page.'.$slug,compact('page','faqs'));
    }

    
}
