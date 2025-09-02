<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExtraCharges;

class SurgePriceController extends AdminBaseController
{
    public function index()
    {
        $extra_charges = ExtraCharges::all();

        return view('admin.surge_price.index',compact('extra_charges'));
    }

    public function addForm()
    {
        return view('admin.surge_price.add');
    }


    public function addPrice(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'value' => 'required',
        ]);
        
        $result = $this->surge_price_service->addSurgePrice($request);

        if ($result['success']) {
            $request->session()->flash('success', 'Surge price added successfully');
            return redirect()->route('surge-price');
        }

        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }


    public function priceDeleted(Request $request, $price_id = null)
    {
        $extra_charge = ExtraCharges::find($price_id);
    
        if (!$extra_charge) {
            // Flash an error message and redirect if the passenger is not found
            session()->flash('error', 'Price not found');
            return redirect()->back();
            //return redirect()->route('admin.users.index');  // Redirect to a relevant route (change if necessary)
        }
        
        $extra_charge->delete();

        // Flash success message
        $request->session()->flash('success', 'Price deleted successfully');

        return redirect()->route('surge-price');
        
    }


    public function editPrice(Request $request,$price_id)
    {
        $edit_price = ExtraCharges::find($price_id);
        return view('admin.surge_price.edit',compact('edit_price'));
    }


    public function updatePrice(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'value' => 'required',
        ]);
        
        $result = $this->surge_price_service->updateSurgePrice($request);

        if ($result['success']) {
            $request->session()->flash('success', 'Surge price updated successfully');
            return redirect()->route('surge-price');
        }

        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }


}
