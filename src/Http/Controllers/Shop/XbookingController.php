<?php

namespace Webkul\Xbooking\Http\Controllers\Shop;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Webkul\Xbooking\Models\ExceptionDay;
use Webkul\Xbooking\Models\WorkingDay;
use Webkul\Xbooking\Models\City;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Webkul\Xbooking\Models\Booking;

class XbookingController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('xbooking::shop.index');
    }

    public function getDates()
    {
        // $dates = [
        //     '2024-06-01', '2024-06-02', '2024-06-03', '2024-06-04', '2024-06-05'
        // ];

        $exceptionDays = ExceptionDay::select('date')->orderBy('date')->get()->toArray();

        $exceptionDaysArr = [];
        foreach($exceptionDays as $exceptionDay){
            $exceptionDaysArr[] =  $exceptionDay['date'];
        }
        Log::info(json_encode($exceptionDaysArr));
        $days = $this->generateDays(30, $exceptionDaysArr);
        Log::info(json_encode($days));
        $dates = [];

        foreach($days as $day){
            $dates[] = $day['day'];
        }
        Log::info(json_encode($dates));
        return response()->json($dates);
    }

    public function getTimes($date, Request $request)
    {
        $daysAvailable = $this->getAvailableDays($request);
        Log::info(json_encode($daysAvailable));
        
        $times = [];
        Log::info("Hii I am Times:" . json_encode($times));
        foreach($daysAvailable as $day){
            $times[$day['day']] = $this->createTimeIntervals($day['slots']); 
        }
        Log::info("Hii I am Times:" . json_encode($times));

        return response()->json($times[$date] ?? []);
    }

    public function getCities()
    {
        $cities = City::all();
        $citiesArr = [];
        foreach($cities as $city) {
            $citiesArr[$city->destination_city] = "$city->id:$city->extra_cost:$city->duration";
        }
        return response()->json($citiesArr ?? []);
    }

    private function getAvailableDays(Request $request)
    {
        $exceptionDays = ExceptionDay::select('date')->orderBy('date')->get()->toArray();

        $exceptionDaysArr = [];
        foreach($exceptionDays as $exceptionDay){
            $exceptionDaysArr[] =  $exceptionDay['date'];
        }

        $days = $this->generateDays(30, $exceptionDaysArr);

        // dd($days);
        $cityData = explode(':', $request->city);
        $cityDuraion = (isset($cityData[2]))? $cityData[2]: 0;
        $productDuration = $request->productDuration;

        $daysAvailable = [];

        foreach($days as $day){
            $daySlots = $this->getAvailableTimeSlotsArray($day);



            $slotsForCustomers = $this->trimSlots($daySlots, $productDuration, $cityDuraion);

            // dd($slotsForCustomers);

            if(!empty($slotsForCustomers)){
                $daysAvailable[] = array(
                    'from' => $day['from'],
                    'to' => $day['to'],
                    'day' => $day['day'],
                    'slots' => $slotsForCustomers
                );
            }

            $slotsForCustomers = [];
        }

        return $daysAvailable;
    }

    private function getAvailableTimeSlotsArray($day)
    {
        $slots = [];

        //order bookings by from timestamp 
        $bookings = Booking::where('day', $day['day'])->whereNotNull('product_id')->orderBy('from')->get();

        if(count($bookings) > 0){
            $startAvailableTimestamp = strtotime($day['day'] .' '. $day['from'] );
            $endAvailableTimestamp = strtotime($day['day'] .' '. $day['to'] );
            $endBookingTimestamp = $startAvailableTimestamp;
    
            foreach($bookings as $booking){
                $startBookingTimestamp = strtotime($day['day'] . ' '. $booking->from );
                $endBookingTimestamp = strtotime($day['day'] . ' '. $booking->to );
                $difference = $startBookingTimestamp - $startAvailableTimestamp ;
    
                if($difference > 0){
                    $slots[] = [
                            'start' => $startAvailableTimestamp,
                            'end' => $startAvailableTimestamp + $difference
                    ];
                }
    
                $startAvailableTimestamp = strtotime($day['day'] . ' '. $booking->to );
            }
    
            if($endAvailableTimestamp - $endBookingTimestamp > 0){
                $slots[] = [
                        'start' => $endBookingTimestamp,
                        'end' => $endAvailableTimestamp
                    ];
            }
        }else{
            $slots[] = [
                'start' => strtotime($day['day'] . ' '. $day['from'] ),
                'end' => strtotime($day['day'] . ' '. $day['to'] )
            ];
        }

        return $slots;
    }

    private function trimSlots($slots, $productDuration, $cityDuration)
    {
        $totalDuration = (int) $productDuration + (int) $cityDuration;
        $totalDuration = $totalDuration * 60;
        
        $slotsAfterTriming = [];

        if(!empty($slots)){
            foreach($slots as $slot){
                $startTimestampAfterTrim = $slot['end'] - $totalDuration;
    
                // dd(date('Y-m-d H:i:s', $slot->start));
                // dd(date('Y-m-d H:i:s', $startTimestampAfterTrim));
    
                if($startTimestampAfterTrim > $slot['start']){
                    $slotsAfterTriming[] = [
                            'start' => date('Y-m-d H:i:s', $slot['start']),
                            'end' => date('Y-m-d H:i:s', $startTimestampAfterTrim)
                    ];
                }
            }
        }

        return $slotsAfterTriming;
    }

    private function generateDays($durationByDays, $exceptionDaysArr)
    {
        $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']; 

        $workingDays = WorkingDay::all();

        $workingDaysArr = [];

        foreach($workingDays as $workingDay){
            $workingDaysArr[] = $workingDay->days;
        }

        $vacations = array_diff($weekDays, $workingDaysArr);

        $workingDaysModified = [];

        foreach($workingDays as $workingDay){
            $workingDaysModified[$workingDay->days] = 
            [
                    'from' => ($workingDay->from == '00:00:00')? '24:00:00' : $workingDay->from,
                    'to' => ($workingDay->to == '00:00:00')? '24:00:00' : $workingDay->to   
            ];
        }

        $days = [];

        for($i = 1; $i <= $durationByDays; $i++){
            $date = date('Y-m-d', strtotime("+$i days"));

            $dayName = date('l', strtotime($date));



            if(!in_array($dayName, $vacations)){
                if(in_array($date, $exceptionDaysArr)){
                    $exceptionDay = ExceptionDay::where('date', $date)->first();
                    $days[] = [
                        'day' => $exceptionDay->day,
                        'from' => $exceptionDay->from,
                        'to' => $exceptionDay->to
                    ];
                } else {
                    if(isset($workingDaysModified[ $dayName ])){
                        $days[] = [
                            'day' => $date ,
                            'from' => $workingDaysModified[ $dayName ]['from'],
                            'to' => $workingDaysModified[ $dayName ]['to']
                        ];
                    }
                }
            }
        }

        return $days;
    }

    private function createTimeIntervals($slotsAfterTriming) {
        $intervals = [];

        foreach ($slotsAfterTriming as $slot) {
            $startTimestamp = strtotime($slot['start']);
            $endTimestamp = strtotime($slot['end']);


            Log::info("Hii I am TimeStamp:" . json_encode(date('H:i:s', $startTimestamp)));

            while ($startTimestamp < $endTimestamp) {
                $intervals[] = date('H:i:s', $startTimestamp);
                $startTimestamp += 1800; // 1800 seconds = 30 minutes
            }
        }

        return $intervals;
    }
}
