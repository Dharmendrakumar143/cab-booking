<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class MainPageController extends BaseController
{
    public function index(Request $request)
    {
        $reviews = Review::select('user_id', 'feedback', 'rating', 'created_at')
        ->whereIn('id', function ($query) {
            $query->selectRaw('MAX(id)')
                ->from('reviews')
                ->groupBy('user_id');
        })
        ->with(['users'])
        ->distinct('user_id') 
        ->orderBy('created_at', 'desc')
        ->get();

        // echo "<pre>reviews==";
        // print_r($reviews);
        // die;

        // Delete session data at the top
        $request->session()->forget('user_ride_request_data');
        return view('Frontend.home.index',compact('reviews'));
    }

}
