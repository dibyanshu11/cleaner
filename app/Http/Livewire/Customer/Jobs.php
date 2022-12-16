<?php

namespace App\Http\Livewire\Customer;

use App\Models\Order;
use App\Models\Transaction;
use Livewire\Component;
use \Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class Jobs extends Component
{

    use LivewireAlert;

    /*
     * @var: selectedTab
     * 1 means MyJobs tab
     * 2 means NewRequests tab
     */
    public $selectedTab  = 1;
    
    public $selectedDate, $orders, $selectedDateOrders, $events;

    protected $pendingOrderStatuses = ['pending', 'rejected'];
    
    protected function getPendingOrders()
    {
        return $this->orders->whereIn('status', $this->pendingOrderStatuses );
    }

    protected function getAcceptedOrders()
    {        
        return $this->orders->whereNotIn('status', $this->pendingOrderStatuses );
    }

    protected function parseOrdersForCalendarEvents($orders)
    {
        $events = $orders->map( function($order) {
            $cleaningDateTime = Carbon::parse( $order->cleaning_datetime );
            $event = [
                'id'    => $order->id,
                'title' => $cleaningDateTime->format("h:m A"),
                'start' => $cleaningDateTime->toDateString(),
            ];
            return $event;  
        })->values();

        return $events;
    }

    protected function getOrdersForSelectedTab()
    {
        if ( $this->selectedTab == 1 ) {
            $orders = $this->getAcceptedOrders();                        
        } else {
            $orders = $this->getPendingOrders();
        }

        return $orders;
    }

    protected function renderOrders()
    {
        $orders                   = $this->getOrdersForSelectedTab();
        $this->selectedDateOrders = $orders->filter(function($order) {
            return $order->cleaning_datetime->startOfDay()->equalTo( $this->selectedDate );
        });


        $this->dispatchBrowserEvent('renderOrders');
        return true;
    }

    protected function renderCalendar()
    {
        $orders = $this->getOrdersForSelectedTab();
        $events = $this->parseOrdersForCalendarEvents($orders);

        $this->events = $events;
        $this->dispatchBrowserEvent('renderCalendar', ['events' => $events]);
        return true;
    }

    /* 
     * This fetchs only needed data from database
     * that fasts the speed of page.
     * 
     */
    protected function prepareOrdersProp()
    {
        $userRelation = ['user' => function ( $query ) {
            $query->without('UserDetails')->select('id','email', 'contact_number');            
        }];

        $itemsRelation = ['items' => function ( $query ) {
            $query->select('id','order_id', 'service_item_id')->with('service_item:id,title');
        }];

        $relations = array_merge( $userRelation, $itemsRelation );

        $this->orders = Order::with($relations)->where('cleaner_id', auth()->user()->id )->get();
        $this->addAttributesInOrders();

    }

    protected function addAttributesInOrders()
    {
        $this->orders->each( function ($order) {
            $order->service_item_titles = $order->items->map(function ($item) {
                return $item->service_item->title;
            })->implode(', ');
        });       
    }

    public function hydrate()
    {
        $this->addAttributesInOrders();
    }

    public function prepare()
    {
        $this->selectedDate = $this->selectedDate ?? today()->toDateString();
        $this->refreshSelectedTab();
        
    }

    public function mount()
    {
        $this->prepare();        
    }

    protected function storeAcceptOrderTransaction($user_id, $order_id, $amount, $stripe_charge_id)
    {
        $transaction = new Transaction;
        $transaction->user_id   = $user_id;
        $transaction->amount    = $amount;
        $transaction->type      = 'debit';
        $transaction->stripe_id = $stripe_charge_id;
        $transaction->transactionable_id   = $order_id;
        $transaction->transactionable_type = Order::class;
        $transaction->save();

        return $transaction;
    }

    public function acceptOrder( $orderId )
    {
        $order = Order::find( $orderId );
        $user  = $order->user;

        /* Charge customer */
        $chargeResp = stripeChargeCustomer( 
            $user->UserDetails->stripe_customer_id,
            $order->totalInCents(),
            "CanaryCleaner charge for order #$order->id"            
        );

        /* Handle stripe charge error */
        if ( $chargeResp['status'] == false ) {
            $this->alert('error', 'Customer charge got failed');
            return false;
        }

        $transaction = $this->storeAcceptOrderTransaction(
            $user->id, 
            $order->id, 
            $order->total,
            $chargeResp['charge_id'],            
        );

        /* Update order */
        $order->status              = 'accepted';
        $order->is_paid_by_user     = 1;        
        $order->user_transaction_id = $transaction->id;
        $order->save();

        $this->alert('success', 'Booking accepted');
        $this->refreshSelectedTab();
        return true;
    }

    

    protected function refreshSelectedTab()
    {
        $this->prepareOrdersProp();        
        $this->renderCalendar();
        $this->renderOrders();
    }

    public function rejectOrder( $orderId ) 
    {
        $order = Order::find($orderId);
        $order->status = 'rejected';
        $order->save();

        $this->alert('success', 'Booking rejected');
        $this->refreshSelectedTab();
    }


    protected function storeCollectPaymentTransaction($user_id, $order_id, $amount, $stripe_transfer_id)
    {
        $transaction = new Transaction;
        $transaction->user_id   = $user_id;
        $transaction->amount    = $amount;
        $transaction->type      = 'credit';
        $transaction->stripe_id = $stripe_transfer_id;
        $transaction->transactionable_id   = $order_id;
        $transaction->transactionable_type = Order::class;
        $transaction->save();

        return $transaction;
    }

    

    public function collectPayment($orderId)
    {
        $cleaner = auth()->user();
        $order   = Order::find( $orderId );

        $transferResp = stripeTransferAmountToConnectedAccount(
            convertAmountIntoCents( $order->cleanerFee() ),
            $cleaner->bankInfo->account_id,            
            "CanaryCleaner payment for order #$order->id",        
        );

        $transaction = $this->storeCollectPaymentTransaction(
            $cleaner->id, 
            $order->id, 
            $order->cleanerFee(),
            $transferResp['transfer_id'],
            
        );

        $order->is_paid_out_to_cleaner = 1;
        $order->cleaner_transaction_id = $transaction->id;
        $order->status = 'payment_collected';
        $order->save();

        $this->alert('success', 'Amount transffered');        
        return true;
    }

    public function completeOrder( $orderId ) 
    {
        $order = Order::find($orderId);
        $order->status = 'completed';
        $order->save();

        $this->alert('success', 'Marked as completed');
        $this->refreshSelectedTab();        
    }

    public function updated($name)
    {
        if ( $name == "selectedTab" ) {
            $this->renderCalendar();
            $this->renderOrders();
        }

        if ( $name == "selectedDate") {
            $this->renderOrders();
        }        
    }

    public function render()
    {
        return view('livewire.customer.jobs');
    }
}
