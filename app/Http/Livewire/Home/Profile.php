<?php

namespace App\Http\Livewire\Home;

use Livewire\Component;
use App\Models\User;
use App\Models\Services;
use App\Models\ServicesItems;
use App\Models\CleanerHours;
use App\Models\CleanerServices;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Crypt;
use App\Models\Order;
use App\Models\CleanerTeam;
use App\Models\Favourite;

class Profile extends Component
{
    public $cleanerId;
    public $cleaner;

    public $services;
    public $itemAddOns;


    //...fields
    public $selected_date;

    public $workingDates = [];
    public $workingDatesTimeSlot = [];


    public $todayDate, $dateFormat, $month_date;



    public $servicesUnmodified;
    public $servicesItems;
    public $cleanerServices;

    public $estimatedPrice;
    public $estimatedTime;

    public $homeSize;
    public $time;
    public $title;
    public $serviceItemId;
    public $addOnIds = [];

    public $selectedDetails = [
        'service_item_id' => null,
        'home_size' => null,
        'add_on_id' => null,
        'time' => null,
    ];

    public $socialMediaSharingInfo, $cleanerAdditionalInfo;


    protected function prepareProps()
    {
        /* Get data */
        $this->cleanerServices    = CleanerServices::where("users_id", $this->cleanerId )->where('status', '1' )->get();
        $this->servicesItems      = ServicesItems::find( $this->cleanerServices->pluck('services_items_id') );
        $this->servicesUnmodified = Services::find( $this->servicesItems->pluck('services_id') );

        /* Prepare services object for blade */
        $this->services = collect();
        foreach ( $this->servicesUnmodified as $service ) {
            $service->serviceItems = $this->servicesItems->whereIn('services_id', $service->id );
            $this->services->push( $service );
        }

        // fetching first because there will always be only one service for add ons if cleaner opts in for that
        $addOnService     = $this->services->where('types_id', 2 )->first();
        $this->itemAddOns = is_null( $addOnService ) ? null : $addOnService->serviceItems;

        $this->fillSocialMediaShareInfo();
        $this->fillCleanerAdditionalInfo();

        return true;
    }

    protected function fillCleanerAdditionalInfo()
    {
        $completedOrders = Order::where('cleaner_id', $this->cleanerId )->whereIn('status', ['payment_collected', 'completed'])->count();
        $totalMembersOfCleanerTeam = CleanerTeam::where('user_id', $this->cleanerId )->count();
        $this->cleanerAdditionalInfo = [
            'rating' => 0,
            'completed_orders' => $completedOrders,
            'total_team'       => $totalMembersOfCleanerTeam,
            'is_insured'       => $this->cleaner->UserDetails->is_insured == 1,
            'is_organic'       => $this->cleaner->UserDetails->provide_organic_service == 1,
        ];


        return true;
    }

    public function fillSocialMediaShareInfo()
    {
        $this->socialMediaSharingInfo = [
            'title' => urlencode("CanaryCleaner ".$this->cleaner->name),
            //'url'   => urlencode(request()->url()), // local links doesn't get share on social platforms
            'url'     => urlencode("https://www.atxwebdesigns.com/how-to-build-a-recession-proof-small-business/"),
        ];
    }

    public function mount()
    {

        $this->cleaner = User::with('cleanerReviews')->findOrFail($this->cleanerId);

        $this->prepareProps();

        $this->todayDate = Carbon::now();

        $this->getWorkingDays($this->todayDate);

        //dd( $this->cleanerServices->pluck('services_items_id')->toArray() );
    }


    public function getWorkingDays($today)
    {
        $fromDate = $today->copy()->firstOfMonth()->startOfDay();
        $toDate = $today->addMonths('2')->copy()->endOfMonth()->startOfDay();

        $Month = date('m', strtotime($fromDate));
        $Year = date('Y', strtotime($fromDate));

        $dates = [];
        $period = CarbonPeriod::create($fromDate, $toDate);



        $lawyerHoursDayAll = CleanerHours::whereUsersId($this->cleaner->id)->get()->pluck('day')->toArray();

        $lawyerHoursDayArray = [];
        foreach($lawyerHoursDayAll as $day){
            array_push($lawyerHoursDayArray, $day);
        }

        $hoursDay = array_values(array_unique($lawyerHoursDayArray));


        foreach ($period as $date) {
            $day = $date->format('l');
            $ndate = $date->format('Y-m-d');

            // dd($ndate );

            ///,.....available current date
            if ($ndate >= date('Y-m-d') && in_array($day, $hoursDay)) {
                array_push($dates, $ndate);
            }
        }

        $this->workingDates = $dates;


        $this->emit('fireCalender', $this->workingDates, $fromDate);
    }



    public function slotAvailability()
    {
        $selectDate = Carbon::parse($this->selected_date);

        $date = $selectDate->format('Y-m-d');
        $dateDay = $selectDate->format("l");

        $hoursDayAll = CleanerHours::whereUsersId($this->cleaner->id)->where('day', $dateDay)->get();

        $getLeaves = [];

        ///,.....available current date
        if (@$hoursDayAll && $date >= date('Y-m-d')) {


            $duration = '30';

            //...
            $time_slots = array();
            $add_mins  = $duration * 60;

            foreach($hoursDayAll as $hour){
                $startTime = strtotime($hour->from_time);
                $endTime = strtotime($hour->to_time);
                while ($startTime <= $endTime) {
                    $timeSlot = [];
                    if(in_array(date("H:i:s", $startTime), $getLeaves)) {
                        $timeSlot['is_free'] = 'no';
                    }
                    else {
                        $timeSlot['is_free'] = 'yes';
                    }


                    $timeSlot['time'] = date("H:i", $startTime);
                    $time_slots[] = $timeSlot;

                    $startTime += $add_mins;

                }
            }
            $this->workingDatesTimeSlot = $time_slots;
        }
    }

    protected function updateEstimateTimeAndPrice()
    {
        $this->estimatedPrice = 0;
        $this->estimatedTime  = 0;
        if ( empty( $this->homeSize) ){
            return null;
        }

        /* Filter out selected services */
        $selectedServices = $this->cleanerServices->where('services_items_id', $this->serviceItemId );
        if ( ! empty( $this->addOnIds) ) {
            $addOnServices    = $this->cleanerServices->whereIn('services_items_id', $this->addOnIds );
            $selectedServices = $selectedServices->concat( $addOnServices );
        }


        /* Sum of each service price against input square feet */
        foreach ( $selectedServices as $serviceItem ) {
            $this->estimatedPrice += $serviceItem->priceForSqFt( $this->homeSize );
        }

        $this->estimatedTime = $selectedServices->sum('duration');
    }

    public function updatingHomeSize($value)
    {
        $this->validate([
            'serviceItemId' => 'required'
        ]);
    }

    public function updated( $key, $value )
    {
        if ( in_array( $key, [ 'homeSize', 'serviceItemId', 'addOnIds' ]) ) {
            $this->updateEstimateTimeAndPrice();
        }

    }


    public function updatedMonthDate()
    {
        $month = $this->month_date;

        $nDate = Carbon::parse($month);

        $this->getWorkingDays($nDate);
    }


    public function updatedSelectedDate(){
        $this->slotAvailability();

    }

    protected function checkoutRules()
    {
        $rules = [
            'serviceItemId' => 'required',
            'addOnIds'      => 'present|array',
            'homeSize'      => 'required|numeric',
            'selected_date' => 'required|date|date_format:Y-m-d',
            'time'          => 'required',
        ];

        $messages = [
            'serviceItemId.required' => "Please select any cleaning type"
        ];

        return [ $rules, $messages ];
    }

    public function redirectToCheckout()
    {
        $validatedData = $this->validate(...$this->checkoutRules());
        $validatedData['cleanerId'] = $this->cleanerId;

        $encryptedDetails = Crypt::encryptString( json_encode( $validatedData ) );

        return redirect()->route('checkout', ['details' => $encryptedDetails ]);

    }

    public function render()
    {
        $this->emit('componentRendered');


        return view('livewire.home.profile');
    }
}
