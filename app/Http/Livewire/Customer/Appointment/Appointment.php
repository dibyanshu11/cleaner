<?php

namespace App\Http\Livewire\Customer\Appointment;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use \Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Review;


class Appointment extends Component
{

    use LivewireAlert;

    public $selectedTab  = 1;
    public $selectedDate, $events, $selectedDateOrders;
    public $orders;
    protected $listeners = ['orderCancelledByCustomer'];
    protected $pendingOrderStatuses = ['pending', 'rejected', 'cancelled_by_customer'];

    /* review order props */
    public $rating, $review, $reviewOrderId;

    /* reschedule order props */
    public $rescheduleDate, $rescheduleTime, $rescheduleOrderId, $rescheduledAvailableTimeSlots = [];

    public function mount()
    {
        $this->prepare();
    }

    public function hydrate()
    {
        $this->resetErrorBag();
    }


    public function prepareOrdersPro()
    {
        $orders = Order::with('items.service_item')->where('user_id', auth()->user()->id)->latest()->get();

        $this->orders = $orders;

        $this->dispatchBrowserEvent('prepareOrdersPro', ['orders' => $orders]);

        return  true;
    }


    protected function parseOrdersForCalendarEvents($orders)
    {

        $events = $orders->map(function ($order) {
            $cleaningDateTime = Carbon::parse($order->cleaning_datetime);

            $event = [
                'id'    => $order->id,
                'title' => $cleaningDateTime->format("h:i A"),
                'start' => $cleaningDateTime->toDateString(),
            ];
            return $event;
        })->values();

        return $events;
    }

    public function prepare()
    {
        $this->selectedDate = $this->selectedDate ?? today()->toDateString();

        $this->refreshSelectedTab();
    }


    public function renderCalendar()
    {
        $events = $this->parseOrdersForCalendarEvents($this->orders);
        $this->events = $events;

        $this->dispatchBrowserEvent('renderCalendar', ['events' => $events]);
        return true;
    }

    protected function refreshSelectedTab()
    {
        $this->prepareOrdersPro();
        $this->renderCalendar();
        $this->renderOrders();
    }

    public function updated($propertyname, $value)
    {

        if ($propertyname == "selectedDate") {
            $this->renderOrders();
        }

        if ( $propertyname == "rescheduleDate") {
            $this->preapareRescheduledAvailableTimeSlotsProp();
        }

        if ( $propertyname == "rescheduleTime") {
            $this->rescheduleTime = Carbon::parse( $value )->format("H:i:s");
            $this->dispatchBrowserEvent('enableTimePickerInRescheduleTimeSelect');
        }
    }



    public function renderOrders()
    {
        $this->selectedDateOrders = $this->orders->filter(function($order) {
            return $order->cleaning_datetime->startOfDay()->equalTo( $this->selectedDate );
        });

        $this->dispatchBrowserEvent('renderOrders');
        return true;
    }

    /* Order cancelled by customer */

    public function cancelOrder($orderId)
    {

        $this->alert('warning', 'Are you sure do want to delete?', [
            'toast' => false,
            'position' => 'center',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'showConfirmButton' => true,
            'confirmButtonText' => 'Delete it',
            'onConfirmed' => 'orderCancelledByCustomer',
            'timer' => null,
            'input' => 'text',
            'inputValue' => $orderId,
            'inputAttributes' => [
                'hidden' => true,
            ]
        ]);
    }

    public function orderCancelledByCustomer($data)
    {
        $orderId = $data['value'];
        $order   = Order::where('id', $orderId )->update(['status' => 'cancelled_by_customer']);
        $this->alert('success', 'Order cancelled');
        $this->refreshSelectedTab();
    }

    public function storeReview($orderId)
    {
        /* validate review data */
        $this->reviewOrderId = $orderId;
        $validatedData = $this->validate([
            'reviewOrderId' => 'required|exists:orders,id',
            'rating'        => 'required|numeric|digits_between:1,5',
            'review'        => 'required'
        ]);

        $order  = Order::find( $orderId );

        /* create review */
        $review = new Review;
        $review->rating   = $this->rating;
        $review->review   = $this->review;
        $review->order_id = $order->id;
        $review->user_id  = auth()->user()->id;
        $review->cleaner_id = $order->cleaner_id;
        $review->save();

        /* Update order */
        $order->status = 'reviewed';
        $order->save();

        $this->alert('success', 'Review submitted');
        $this->refreshSelectedTab();
        return true;
    }

    public function preapareRescheduledAvailableTimeSlotsProp()
    {
        $order           = $this->orders->find( $this->rescheduleOrderId );
        $selectedWeekDay = Carbon::parse( $this->rescheduleDate )->englishDayOfWeek;

        /* Get from and to time from cleaner hours table of selected day */
        $cleanerTimeSlots = $order->cleaner->cleanerHours->where('day', $selectedWeekDay )->pluck('to_time', 'from_time');

        /* Parse those time to display in frontend */
        $timeSlotsForCustomer = collect();
        foreach ( $cleanerTimeSlots as $from => $to ) {

            $timeSlots = collect(\Carbon\CarbonInterval::minutes(60)->toPeriod( $from, $to ))->map->format('h:i A');
            $timeSlotsForCustomer->push( $timeSlots );
        }

        $this->rescheduledAvailableTimeSlots = $timeSlotsForCustomer->collapse()->unique()->toArray();
        $this->dispatchBrowserEvent('enableTimePickerInRescheduleTimeSelect');
        return $timeSlotsForCustomer;
    }

    public function rescheduleSelectedOrder()
    {
        $rescheduleDatetime = Carbon::createFromFormat("Y-m-d H:i:s", "$this->rescheduleDate $this->rescheduleTime" );
        $order = $this->orders->find( $this->rescheduleOrderId );
        $order->cleaning_datetime = $rescheduleDatetime;
        $order->save();

        $this->alert('success', 'Order Rescheduled');
        $this->hideRescheduleModal();
        $this->refreshSelectedTab();

        //public $rescheduleDate, $rescheduleDate, $rescheduleOrderId, $rescheduledAvailableTimeSlots = [];
        $this->reset([
            'rescheduleDate',
            'rescheduleDate',
            'rescheduleOrderId',
            'rescheduledAvailableTimeSlots'
        ]);

        return true;
    }


    public function generateAllowedRescheduleWeekDaysOfOrderForDatePicker($orderId)
    {
        /* get cleaner week days from DB */
        $order = $this->orders->find( $orderId )->loadMissing('cleaner.cleanerHours');
        $cleanerAvailablitlyWeekDays = $order->cleaner->cleanerHours->pluck('day')->unique()->map('strtolower')->toArray();

        $weekdaysForDatePicker = parseWeekdaysNameIntoWeekDaysNumber( $cleanerAvailablitlyWeekDays );
        return $weekdaysForDatePicker;
    }

    public function showRescheduleModal($orderId)
    {
        $weekdaysForDatePicker   = $this->generateAllowedRescheduleWeekDaysOfOrderForDatePicker($orderId);
        $this->rescheduleOrderId = $orderId;

        $this->dispatchBrowserEvent('showRescheduleModal', [
            'allowedWeekDays' => $weekdaysForDatePicker,
            'orderId' => $orderId,
        ]);
    }

    public function hideRescheduleModal()
    {
        $this->dispatchBrowserEvent('hideRescheduleModal');
    }

    public function render()
    {
        return view('livewire.customer.appointment.appointment');
    }
}
