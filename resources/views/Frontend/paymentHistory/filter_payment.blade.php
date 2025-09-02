<table class="table">
    <thead class=" rounded-8">
        <tr>
            <th>Sr.No</th>
            <th>Passanger Name</th>
            <th>Pickup  Address</th>
            <th>Drop  Address</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @php
        $i=1;
    @endphp
    @if($payment_statuses->count() > 0)
        @foreach($payment_statuses as $payment_status)
            <tr>
                <td>{{$i}}</td>
                <td>{{$payment_status->users->first_name}} {{$payment_status->users->last_name}}</td>
                <td>{{$payment_status->pick_up_address}}</td>
                <td>{{$payment_status->drop_off_address}}</td>
                <td>{{ date('d M Y', strtotime($payment_status->pick_up_date)) }} </td>
                <td>${{$payment_status->booking->ride_booking_amount}}</td>
                <td>
                    <div class="success-btn">
                        <button type="button" class="btn bg-28 color-28 px-4 fs-12 rounded-10 fw-600">{{$payment_status->paymentStatus->status}}</button>
                    </div>
                </td>
                <td>
                    <div class="td-delete-icon d-flex gap-3">
                        <a href="#">
                            <img src="{{asset('assets/images/td-eye.png')}}">
                        </a>
                    
                    </div>
                </td>
            </tr>
            @php
                $i++;
            @endphp
        @endforeach
    @else
        <tr>
            <td colspan="8" class="text-center">No data found</td>
        </tr>
    @endif
    </tbody>
</table>