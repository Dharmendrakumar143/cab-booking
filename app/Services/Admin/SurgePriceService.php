<?php 

namespace App\Services\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\ExtraCharges;

class SurgePriceService
{   
    public function addSurgePrice($request)
    {
        try {
            
            $name = $request->name;
            $days = $request->days;
            $holiday_dates = $request->holiday_dates;
            $start_time = $request->start_time;
            $end_time = $request->end_time;
            $value = $request->value;

            // Validate that start_time is earlier than end_time
            if ($start_time && $end_time) {
                $startDate = new \DateTime($start_time);
                $endDate = new \DateTime($end_time);
                
                if ($startDate >= $endDate) {
                    return [
                        'success' => false,
                        'message' => 'End time must be later than start time.',
                    ];
                }
            }

            if ($days) {
                $weekends = ExtraCharges::where('name', 'weekend')->first();
                $days = implode(",", $days);
                if (!$weekends) {
                    ExtraCharges::create([
                        'name' => $name,
                        'value' => $value,
                        'is_weekend' => true,
                        'weekend_days' => $days,
                    ]);
                } else {
                    $weekends->update([
                        'value' => $value,
                        'weekend_days' => $days,
                    ]);
                }
            }

            if ($holiday_dates) {
                $holidays = ExtraCharges::where('name', 'holiday')->first();
                if (!$holidays) {
                    ExtraCharges::create([
                        'name' => $name,
                        'value' => $value,
                        'is_holiday' => true,
                        'holiday_dates' => $holiday_dates,
                    ]);
                } else {
                    $holidays->update([
                        'value' => $value,
                        'holiday_dates' => $holiday_dates,
                    ]);
                }
            }

            if($name == 'night'){
                if ($start_time) {
                    $night = ExtraCharges::where('name', 'night')->first();
                    if (!$night) {
                        ExtraCharges::create([
                            'name' => $name,
                            'value' => $value,
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                        ]);
                    } else {
                        $night->update([
                            'value' => $value,
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                        ]);
                    }
                }
            }
          

            if($name == 'night 2'){
                if ($start_time) {
                    $night2 = ExtraCharges::where('name', 'night 2')->first();
                    if (!$night2) {
                        ExtraCharges::create([
                            'name' => $name,
                            'value' => $value,
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                        ]);
                    } else {
                        $night2->update([
                            'value' => $value,
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                        ]);
                    }
                }
            }


            if($name == 'rush hour 1'){
                if ($start_time) {
                    $rush_hour1 = ExtraCharges::where('name', 'rush hour 1')->first();
                    if (!$rush_hour1) {
                        ExtraCharges::create([
                            'name' => $name,
                            'value' => $value,
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                        ]);
                    } else {
                        $night2->update([
                            'value' => $value,
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                        ]);
                    }
                }
            }


            if($name == 'rush hour 2'){
                if ($start_time) {
                    $rush_hour2 = ExtraCharges::where('name', 'rush hour 2')->first();
                    if (!$rush_hour2) {
                        ExtraCharges::create([
                            'name' => $name,
                            'value' => $value,
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                        ]);
                    } else {
                        $night2->update([
                            'value' => $value,
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                        ]);
                    }
                }
            }

            return [
                'success' => true,
                'message' => 'Surge price added successfully.',
            ];

        } catch (\Exception $e) {
            // Catch any exception and return the error message
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }


    public function updateSurgePrice($request)
    {
        try {
            
            $name = $request->name;
            $days = $request->days;
            $holiday_dates = $request->holiday_dates;
            $start_time = $request->start_time;
            $end_time = $request->end_time;
            $value = $request->value;

            // Validate that start_time is earlier than end_time
            if ($start_time && $end_time) {
                $startDate = new \DateTime($start_time);
                $endDate = new \DateTime($end_time);
                
                if ($startDate >= $endDate) {
                    return [
                        'success' => false,
                        'message' => 'End time must be later than start time.',
                    ];
                }
            }

            if ($days) {
                $weekends = ExtraCharges::where('name', 'weekend')->first();
                $days = implode(",", $days);
                if ($weekends) {
                    $weekends->update([
                        'value' => $value,
                        'weekend_days' => $days,
                    ]);
                }
            }

            if ($holiday_dates) {
                $holidays = ExtraCharges::where('name', 'holiday')->first();
                if ($holidays) {
                    $holidays->update([
                        'value' => $value,
                        'holiday_dates' => $holiday_dates,
                    ]);
                }
            }

            if($name == 'night'){
                if ($start_time) {
                    $night = ExtraCharges::where('name', 'night')->first();
                    if ($night) {
                        $night->update([
                            'value' => $value,
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                        ]);
                    }
                }
            }

           if($name == 'night 2'){
                if ($start_time) {
                    $night2 = ExtraCharges::where('name', 'night 2')->first();
                    if ($night2) {
                        $night2->update([
                            'value' => $value,
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                        ]);
                    }
                }
            }


            if($name == 'rush hour 1'){
                if ($start_time) {
                    $rush_hour1 = ExtraCharges::where('name', 'rush hour 1')->first();
                    if ($rush_hour1) {
                        $rush_hour1->update([
                            'value' => $value,
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                        ]);
                    }
                }
            }

            if($name == 'rush hour 2'){
                if ($start_time) {
                    $rush_hour2 = ExtraCharges::where('name', 'rush hour 2')->first();
                    if ($rush_hour2) {
                        $rush_hour2->update([
                            'value' => $value,
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                        ]);
                    }
                }
            }

            return [
                'success' => true,
                'message' => 'Surge price updated successfully.',
            ];

        } catch (\Exception $e) {
            // Catch any exception and return the error message
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }

    }

}
