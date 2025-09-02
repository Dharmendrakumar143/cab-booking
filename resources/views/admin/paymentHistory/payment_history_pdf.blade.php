<!DOCTYPE html>
<html>
<head>
    <title>Payment History Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Payment History Report</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>Email</th>
                <th>Booking Status</th>
                <th>Payment Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rideRequests as $request)
            <tr>
                <td>{{ $request['id'] }}</td>
                <td>{{ $request['user_name'] }}</td>
                <td>{{ $request['email'] }}</td>
                <td>{{ $request['booking_status'] }}</td>
                <td>{{ $request['payment_status'] }}</td>
                <td>{{ $request['created_at'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
