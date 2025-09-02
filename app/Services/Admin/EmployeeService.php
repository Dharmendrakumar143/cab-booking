<?php 

namespace App\Services\Admin;

use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Mail\EmployeeCredentialMail;

//Models
use App\Models\Admin;

class EmployeeService
{
    
    public function addEmployee($request)
    {
        try {
            $employeeRole = Role::where('name', 'employees')->first();
            
            if (!$employeeRole) {
                return [
                    'success' => false,
                    'message' => 'Employee role does not exist.',
                ];
            }
    
            $employee = Admin::create([
                'name' =>$request->input('first_name').' '.$request->input('last_name'), 
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'phone_number' => $request->input('phone_number'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'is_email_verified'=>1,
                'verification_status'=>'verified'
            ]);
    
            if (!$employee) {
                return [
                    'success' => false,
                    'message' => 'Employee registration failed. Please try again.',
                ];
            }
    
            // Assign the 'independent-contractors' role to the driver
            if (!$employee->hasRole('employees')) {
                $employee->assignRole($employeeRole);
            }

            $tempPassword = $request->input('password');
    
            // Send OTP to the user's email
            Mail::to($employee->email)->send(new EmployeeCredentialMail($employee, $tempPassword));
            return [
                'success' => true,
                'message' => 'Employee added successfully.',
            ];

        } catch (\Exception $e) {
            // Catch any exception and return the error message
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }


    public function updateEmployeeData($request)
    {
        try {
            $employee_id = $request->employee_id;

            $employee = Admin::find($employee_id);

            if(!$employee){
                return [
                    'success' => false,
                    'message' => 'Employee doens not exist.',
                ];
            }
    
            $employee = $employee->update([
                'name' =>$request->input('first_name').' '.$request->input('last_name'), 
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'phone_number' => $request->input('phone_number'),
            ]);
    
    
            return [
                'success' => true,
                'message' => 'Employee updated successfully.',
            ];

        } catch (\Exception $e) {
            // Catch any exception and return the error message
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }
    }

}
