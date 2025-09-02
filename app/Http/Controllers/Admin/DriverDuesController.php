<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\DriverDues;

class DriverDuesController extends AdminBaseController
{
    /**
     * Display a list of driver dues for the authenticated admin.
     *
     * @param Request $request The incoming HTTP request.
     * @return \Illuminate\View\View The view with driver dues data.
     */
    public function driverDues(Request $request)
    {
        // Fetch the driver role and pagination number from the config
        $driver_role = config('global-constant.driver_role');
        $paginate_number = config('global-constant.paginate_number');

        // Get the authenticated admin user
        $user = Auth::guard('admin')->user();

        // Retrieve the role of the authenticated user
        $user_role = $user->roles->first()->name;

        // If the authenticated user is a driver, show only their dues
        if ($user_role === $driver_role) {
            $driver_dues = DriverDues::with(['admin'])
                ->where('driver_id', $user->id)
                ->paginate($paginate_number);
        } else {
            // If the user is an admin, show all driver dues
            $driver_dues = DriverDues::with(['admin'])
                ->paginate($paginate_number);
        }

        // Return the view with the driver dues data
        return view('admin.adminDues.index', compact('driver_dues'));
    }

    /**
     * Filter and display driver dues based on status.
     *
     * @param Request $request The incoming HTTP request containing the search filters.
     * @return \Illuminate\View\View The filtered driver dues view.
     */
    public function driverDuesFilters(Request $request)
    {
        // Fetch the driver role and pagination number from the config
        $driver_role = config('global-constant.driver_role');
        $paginate_number = config('global-constant.paginate_number');

        // Get the authenticated admin user
        $user = Auth::guard('admin')->user();

        // Retrieve the role of the authenticated user
        $user_role = $user->roles->first()->name;

        // Get the search filter value, if any
        $search_by = $request->search_by;

        // Initialize the query to fetch driver dues
        $query = DriverDues::query();

        // If a search filter is applied, filter the results by status
        if ($search_by) {
            $query->where('status', $search_by);
        }

        // If the authenticated user is a driver, show only their dues
        if ($user_role === $driver_role) {
            $driver_dues = $query->with(['admin'])
                ->where('driver_id', $user->id)
                ->paginate($paginate_number);
        } else {
            // If the user is an admin, show all driver dues
            $driver_dues = $query->with(['admin'])
                ->paginate($paginate_number);
        }

        // Return the filtered view with the driver dues data
        return view('admin.adminDues.filter', compact('driver_dues'));
    }
}
