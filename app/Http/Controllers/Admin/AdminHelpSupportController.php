<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTickets;


class AdminHelpSupportController extends AdminBaseController
{
    /**
     * Display a paginated list of support tickets.
     *
     * @return \Illuminate\View\View The view displaying the tickets.
     */
    public function index()
    {
        $tickets = SupportTickets::paginate(10);
        return view('admin.ticket.index', compact('tickets'));
    }

    /**
     * Filter tickets based on the provided search criteria.
     *
     * @param \Illuminate\Http\Request $request The request containing the search term.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View The filtered tickets in JSON or as a view.
     */
    public function ticketFilter(Request $request)
    {
        $search_by = $request->search_by;

        // Perform the search if a search term is provided
        if (!empty($search_by)) {
            $tickets = SupportTickets::where('name', 'LIKE', "%$search_by%")
                ->orWhere('issue_subject', 'LIKE', "%$search_by%")
                ->orWhere('email', 'LIKE', "%$search_by%")
                ->orWhere('status', '=', $search_by)
                ->orWhereDate('created_at', '=', $search_by)
                ->take(10)
                ->get();
        } else {
            // Default to fetching a limited number of tickets if no search term is provided
            $tickets = SupportTickets::take(10)->get();
        }

        // Return filtered results as a view or JSON
        return view('admin.ticket.filter_result', compact('tickets'));

        if ($tickets->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'result' => $tickets,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Ticket not found",
        ]);
    }

    /**
     * Display the details of a specific ticket.
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @param int $ticket_id The ID of the ticket.
     * @return \Illuminate\View\View The view displaying the ticket details.
     */
    public function ticketDetails(Request $request, $ticket_id)
    {
        $ticket_detail = SupportTickets::find($ticket_id);
        return view('admin.ticket.view', compact('ticket_detail'));
    }

    /**
     * Display the edit form for a specific ticket.
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @param int $ticket_id The ID of the ticket.
     * @return \Illuminate\View\View The view displaying the edit form for the ticket.
     */
    public function editTicket(Request $request, $ticket_id)
    {
        $ticket_detail = SupportTickets::find($ticket_id);
        return view('admin.ticket.edit', compact('ticket_detail'));
    }

    /**
     * Mark a ticket as resolved by updating its solution and status.
     *
     * @param \Illuminate\Http\Request $request The request containing ticket resolution details.
     * @return \Illuminate\Http\RedirectResponse A redirect response after resolving the ticket.
     */
    public function resolvedTicket(Request $request)
    {
        $request->validate([
            'solution' => 'required',
        ]);

        // Call the service to resolve the ticket
        $result = $this->support_ticket_service->resolvedUserTicket($request);

        if ($result['success']) {
            $request->session()->flash('success', $result['message']);
            return redirect()->route('tickets');
        }

        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }

    /**
     * Delete a specific ticket.
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @param int $ticket_id The ID of the ticket to delete.
     * @return \Illuminate\Http\RedirectResponse A redirect response after deleting the ticket.
     */
    public function deleteTicket(Request $request, $ticket_id)
    {
        $ticket = SupportTickets::find($ticket_id);

        if (!$ticket) {
            $request->session()->flash('error', 'Ticket not found');
            return redirect()->back();
        }

        $ticket->delete();

        $request->session()->flash('success', 'Ticket deleted successfully');
        return redirect()->route('tickets');
    }
}
