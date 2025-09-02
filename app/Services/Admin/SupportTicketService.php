<?php 

namespace App\Services\Admin;

use Illuminate\Support\Facades\Auth;
use App\Models\SupportTickets;

class SupportTicketService
{   
    /**
     * Resolves a user support ticket by marking it as closed and saving the provided solution.
     *
     * @param \Illuminate\Http\Request $request The request object containing ticket ID and solution.
     * @return array An array with success status and message.
     */
    public function resolvedUserTicket($request)
    {
        try {
            $ticket_id = $request->ticket_id;
            $solution = $request->solution;

            // Find the support ticket by ID
            $support_ticket = SupportTickets::find($ticket_id);

            // If the ticket does not exist, return an error response
            if (!$support_ticket) {
                return [
                    'success' => false,
                    'message' => 'Ticket not found.',
                ];
            }

            // Update the ticket with the solution and mark it as closed
            $support_ticket->update([
                'solutions' => $solution,
                'status' => 'closed'
            ]);

            return [
                'success' => true,
                'message' => 'Ticket resolved successfully.',
            ];

        } catch (\Exception $e) {
            // Handle any exceptions and return the error message
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

}
