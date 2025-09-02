
<table class="table">
    <thead class=" rounded-8">
        <tr>
            <th>Sr.No</th>
            <th>Driver Name</th>
            <th>Due Amount</th>
            <th>status</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $i = 1;
        @endphp
        @if($driver_dues->count() > 0)
            @foreach($driver_dues as $due)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{$due->admin->first_name}} {{$due->admin->last_name}}</td>
                    <td>${{number_format($due->total_due,2)}}</td>
                    
                    <td>
                        <div class="success-btn" bis_skin_checked="1">
                            <button type="button" class="btn bg-28 color-28 px-4 fs-12 rounded-10 fw-600">{{$due->status}}</button>
                        </div>
                    </td>
                </tr>
                @php 
                    ++$i;
                @endphp

            @endforeach
        @else
            <tr>
               <td colspan="7" class="text-center">No data found</td>
            </tr>
        @endif
    </tbody>
</table>