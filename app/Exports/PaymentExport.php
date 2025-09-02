<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

use App\Models\RideRequests;
use Auth;

class PaymentExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $admin = Auth::guard('admin')->user();
        $admin_id = $admin->id;
        $admin_role = $admin->roles->first()->name ?? null;

        return RideRequests::whereHas('booking', function ($query) use ($admin_role, $admin_id){
            $query->where('booking_status', '!=', 'Cancelled');
            if ($admin_role !== 'super-admin' && $admin_role !== 'admin') {
                $query->where('driver_id', $admin_id);
            }
        })
        ->with(['booking', 'users', 'paymentStatus'])
        ->get()
        ->map(function ($rideRequest) {
            return [
                'id'            => $rideRequest->id,
                'user_name'     => $rideRequest->users->first_name . ' ' . $rideRequest->users->last_name,
                'email'         => $rideRequest->users->email,
                'booking_status'=> $rideRequest->booking->booking_status,
                'payment_status'=> $rideRequest->paymentStatus->status ?? 'N/A',
                'created_at'    => $rideRequest->created_at->toDateTimeString(),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'User Name',
            'Email',
            'Booking Status',
            'Payment Status',
            'Created At',
        ];
    }
}
