<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Barryvdh\DomPDF\Facade\Pdf;

class EmployeeController extends AdminBaseController
{
    public function index()
    {
        $employees = Admin::with('bookingMore')
        ->whereHas('roles', function($query){
            $query->whereIn('name',['admin', 'employees']);
        }) 
        ->orderBy('id','desc')
        ->paginate(10);

        // Iterate through each driver and calculate total rides and completed rides
        $employees->getCollection()->transform(function($employee) {
            // Calculate the total rides
            $totalRides = $employee->bookingMore->count();

            // Calculate the total completed rides (assuming "completed" rides are marked by a specific status)
            $completedRides = $employee->bookingMore->where('booking_status', 'Completed')->count();

            // Add the counts to the driver object
            $employee->total_rides = $totalRides;
            $employee->completed_rides = $completedRides;

            return $employee;
        });

        return view('admin.employee.index',compact('employees'));
    }


    public function employeeData(Request $request)
    {
        $query = Admin::with('bookingMore')
        ->whereHas('roles', function($query){
            $query->whereIn('name',['employees']);
        });

        // Apply custom search if provided
        if ($request->has('custom_search') && $request->custom_search) {
            $searchTerm = $request->custom_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('first_name', 'like', "%{$searchTerm}%")
                    ->orWhere('last_name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Sorting logic based on the column and direction from the request
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir');
        $columns = $request->input('columns');
        $orderColumn = $columns[$orderColumnIndex]['name'];

        if($orderColumn=='user'){
            $query->orderBy('first_name', $orderDirection);
        }else{
            $query->orderBy($orderColumn, $orderDirection);
        }

        // Pagination logic
        $totalRecords = $query->count();
        $employees = $query->skip($request->start)
            ->take($request->length)
            ->orderBy('id','desc')
            ->get();

        foreach($employees as $employee){
            // Calculate the total rides
            $totalRides = $employee->bookingMore->count();

            // Calculate the total completed rides (assuming "completed" rides are marked by a specific status)
            $completedRides = $employee->bookingMore->where('booking_status', 'Completed')->count();

            $employee->total_rides = $totalRides;
            $employee->completed_rides = $completedRides;
        }
        
        // Return data formatted for DataTables
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $employees,
        ]);
    }


    public function addEmployeeForm()
    {
        return view('admin.employee.add'); 
    }

    public function addNewEmployee(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:8',
        ]);

        $result = $this->employee_service->addEmployee($request);

        if ($result['success']) {
            $request->session()->flash('success', 'New Employee added successfully.');
            return redirect()->route('employee-list');
        }
        
        // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }


    public function employeeDeleted(Request $request, $employee_id)
    {
        $employee = Admin::with('bookingMore')->find($employee_id);
    
        if (!$employee) {
            session()->flash('error', 'Employee not found');
            return redirect()->back();
        }
    
        if ($employee->bookingMore->count() > 0) {
            session()->flash('error', 'This Employee cannot be deleted because they have assigned bookings.');
            return redirect()->back();
        }

        $employee->delete();
    
        session()->flash('success', 'Employee deleted successfully.');
        return redirect()->back();
    }


    public function employeeEdit($user_id)
    {
        $employee = Admin::find($user_id);
        return view('admin.employee.edit',compact('employee'));
    }

    
    public function updateEmployee(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required',
        ]);

        $result = $this->employee_service->updateEmployeeData($request);

        if ($result['success']) {
            $request->session()->flash('success', 'Employee details updated successfully.');
            return redirect()->route('employee-list');
        }
        
        // Add an error flash message
        $request->session()->flash('error', $result['message']);
        return redirect()->back();
    }


    public function exportEmployeePDF()
    {
        $employees = Admin::whereHas('roles', function ($query) {
                        $query->where('name', 'employees');
                    })
                    ->get(['id', 'first_name', 'last_name', 'email', 'phone_number', 'created_at']);

        // Pass data to the PDF view
        $pdf = Pdf::loadView('admin.employee.exports.users_pdf', compact('employees'));

        // Stream the PDF as a response
        return $pdf->download('employees.pdf');
    }


}
