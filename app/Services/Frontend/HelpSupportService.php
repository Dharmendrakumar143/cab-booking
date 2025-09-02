<?php 

namespace App\Services\Frontend;

use Carbon\Carbon;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Models\SupportTickets;


class HelpSupportService
{
    public function storeTicket($request)
    {
        try {

            $user = Auth::user();
            $user_id = $user->id;
            
            $ticket_data = [
                'user_id'=>$user_id,
                'issue_subject'=>$request->subject,
                'issue_description'=>$request->comment,
                'name'=>$request->name,
                'email'=>$request->email,
            ];

            SupportTickets::create($ticket_data);
            
            return [
                'success' => true,
                'message' => 'Ticket send successfully.',
            ];
       
        } catch (\Exception $e) {
            // Catch any exception and return the error message
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
?>
