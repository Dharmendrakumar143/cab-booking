<table class="table">
    <thead class="rounded-8">
        <tr>
            <th>Sr.No</th>
            <th>Date</th>
            <th>Sender</th>
            <th>Subject</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 1;
        @endphp
        @if($tickets->count() > 0)
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $i }}</td>
                    <td>{{ date('d M Y', strtotime($ticket->created_at)) }}</td>
                    <td>{{ $ticket->name }}</td>
                    <td>{{ $ticket->issue_subject }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($ticket->issue_description, 20) }}</td>
                    <td>
                        <div class="resolved-btn">
                            <button type="button" class="btn bg-71 color-71 pe-4 fs-14 rounded-10 fw-500">{{ $ticket->status }}</button>
                        </div>
                    </td>
                    <td>
                        <div class="dots-box">
                            <div class="dropdown">
                                <a class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('assets/images/dots.png') }}" alt="Options">
                                </a>
                                <ul class="dropdown-menu">
                                    @if($ticket->status=='closed')
                                    <li class="border-bottom"><a class="dropdown-item" href="{{route('ticket-details',['ticket_id'=>$ticket->id])}}">View</a></li>
                                    @else
                                    <li class="border-bottom"><a class="dropdown-item" href="{{route('edit-ticket',['ticket_id'=>$ticket->id])}}">Edit</a></li>
                                    @endif
                                    <li class="border-bottom"><a class="dropdown-item delete-btn" data-ticket-id="{{ $ticket->id }}" href="javascript:void(0);">Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                @php
                    $i++;
                @endphp
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center">No data found</td>
            </tr>
        @endif
    </tbody>
</table>
