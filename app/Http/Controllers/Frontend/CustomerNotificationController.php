<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Notification;
use DB;

class CustomerNotificationController extends BaseController
{
     /**
     * Display a list of unread customer notifications.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = DB::table('notifications')
        ->where('read_status', false)
        ->where('type', 'admin')
        ->where('user_id', $user->id)
        ->orderBy('id', 'desc')
        ->get();
        return view('Frontend.notification.index',compact('notifications'));
    }

     /**
     * Show details of a specific notification.
     *
     * @param  int  $notification_id  The ID of the notification to retrieve
     * @return \Illuminate\View\View
     */
    public function notificationDetails($notification_id)
    {
        $notification = Notification::find($notification_id);
        return view('Frontend.notification.details',compact('notification'));
    }

    /**
     * Mark a notification as read and redirect to the notification list.
     *
     * @param  Request  $request  The incoming request containing the notification ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markRead(Request $request)
    {
        $notificationId = $request->notification_id;
        if ($notificationId) {
            // Update the notification's read_status in the database
            DB::table('notifications')
                ->where('id', $notificationId)
                ->update(['read_status' => true,'read_at'=>now()]);
            
            return redirect()->route('customer-notification-list');
        }
    }

    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('id');

        if ($notificationId) {
            // Update the notification's read_status in the database
            DB::table('notifications')
                ->where('id', $notificationId)
                ->update(['read_status' => true,'read_at'=>now()]);
            
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid notification ID']);
    }
}
