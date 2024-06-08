<?php

namespace Webkul\Xbooking\Http\Controllers\Shop;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

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
        $dates = [
            '2024-06-01', '2024-06-02', '2024-06-03', '2024-06-04', '2024-06-05'
        ];

        return response()->json($dates);
    }

    public function getTimes($date)
    {
        $times = [
            '2024-06-01' => ['10:00', '11:00', '12:00'],
            '2024-06-02' => ['13:00', '14:00', '15:00'],
            '2024-06-03' => ['16:00', '17:00', '18:00'],
            '2024-06-04' => ['10:00', '11:00', '12:00'],
            '2024-06-05' => ['13:00', '14:00', '15:00']
        ];

        return response()->json($times[$date] ?? []);
    }

    public function getCities()
    {
        $cities = [
            'tanta' => '1:20:20',
            'alex' => '2:40:30',
            'cairo' => '3:20:50'
        ];

        return response()->json($cities ?? []);
    }

    private function getAvailableDays(Request $request)
    {
        $exceptionDays = Slot::select('day')->orderBy('day')->get()->toArray();

        $exceptionDaysArr = [];
        foreach($exceptionDays as $exceptionDay){
            $exceptionDaysArr[] =  $exceptionDay['day'];
        }

        $days = $this->generateDays(30, $exceptionDaysArr);

        // dd($days);

        $city_id = $request->city_id;
        $product_id = $request->product_id;

        $daysAvailable = [];

        foreach($days as $day){
            $daySlots = $this->getAvailableTimeSlotsArray($day);



            $slotsForCustomers = $this->trimSlots($daySlots, $product_id, $city_id);

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

        return $this->responseJson(false, 200, 'Slots retrieved successfully', $daysAvailable);  
    }

    private function getAvailableTimeSlotsArray($day)
    {
        $slots = [];

        //order bookings by from timestamp 
        $bookings = Booking::where('day', $day['day'])->whereNotNull('order_id')->orderBy('from')->get();

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

    private function trimSlots($slots, $product_id, $city_id)
    {
        
        $product = Product::where('id', $product_id)->where('status', 1)->first();

        $city = City::where('id', $city_id)->first();

        $totalDuration = (int) $product->duration + (int) $city->duration;
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
        $weekDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']; 

        $workingDays = Wday::all();

        $workingDaysArr = [];

        foreach($workingDays as $workingDay){
            $workingDaysArr[] = $workingDay->day_name;
        }

        $vacations = array_diff($weekDays, $workingDaysArr);

        $workingDaysModified = [];

        foreach($workingDays as $workingDay){
            $workingDaysModified[$workingDay->day_name] = 
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
                    $exceptionDay = Slot::where('day', $date)->first();
                    $days[] = [
                        'day' => $exceptionDay->day,
                        'from' => $exceptionDay->from,
                        'to' => $exceptionDay->to
                    ];
                } else {
                    if(isset($workingDaysModified[strtolower( $dayName )])){
                        $days[] = [
                            'day' => $date ,
                            'from' => $workingDaysModified[strtolower( $dayName )]['from'],
                            'to' => $workingDaysModified[strtolower( $dayName )]['to']
                        ];
                    }
                }
            }
        }

        return $days;
    }
}
