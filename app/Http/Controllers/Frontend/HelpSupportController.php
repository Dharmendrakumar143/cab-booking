<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\SupportTickets;

class HelpSupportController extends BaseController
{
    public function index()
    {
        return view('Frontend.helpSupport.index');
    }

    public function sendTicket(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'comment' => 'required',
            'email' => 'required|email',
        ]);

        $result = $this->help_support_service->storeTicket($request);

        if ($result['success']) {
            $request->session()->flash('success', $result['message']);
            return redirect()->route('help-support');
        }

        // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();

    }

    public function viewTickets()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $support_tickets = SupportTickets::where('user_id',$user_id)->orderBy('id','desc')->get();

        return view('Frontend.helpSupport.tickets',compact('support_tickets'));
    }

    public function viewTicketDetails($ticket_id)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $ticket_details = SupportTickets::where('user_id',$user_id)->find($ticket_id);
        return view('Frontend.helpSupport.view_ticket_details',compact('ticket_details'));
    }
}
