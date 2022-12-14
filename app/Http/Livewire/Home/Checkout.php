<?php

namespace App\Http\Livewire\Home;

use App\Models\CleanerServices;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ServicesItems;
use App\Models\State;
use Livewire\Component;

use App\Models\User;
use App\Models\UserDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

use Jantinnerezo\LivewireAlert\LivewireAlert;


class Checkout extends Component
{
    use LivewireAlert;

    public $currentlyActiveStep, $currentPageUrl;
    public $details;

    /* First step props */
    public $cleaner, $datetime, $estimatedDuration, $frequency;
    public $cleanerService, $addOns, $homeSize;

    /* Second step props */
    public $subtotal, $tax, $transactionFees, $total, $states, $user;

    /* Second step: User details form props */
    public $firstname, $lastname, $email, $password;
    public $confirmPassword, $address, $aptOrUnit;
    public $city, $stateId, $zip, $paymentMethod;
    public $contact;

    /* Second step: Card details props */
    public $number, $expMonthYear, $cvc;

    /* Second step: Stripe card saving props */
    public $stripe_customer_id, $expMonth, $expYear;

    /* Third Step */
    public $order, $notes;



    protected function prepareServiceAddOnsAndEstimatedDurationProps()
    {
        /* Fetch selected services from DB */
        $cleanerServicesIds = array_merge( [ $this->details['serviceItemId'] ], $this->details['addOnIds'] );
        $cleanerServices    = CleanerServices::with('servicesItems.service')->where('users_id', $this->cleaner->id )->whereIn('services_items_id', $cleanerServicesIds )->get();

        /* Add attributes for blade */
        $cleanerServices->each( function($item, $key) {
            $item->rate               = $item->priceForSqFt( $this->details['homeSize'] );
            $item->service_item_title = $item->servicesItems->title;
            $item->service_title      = $item->servicesItems->service->title;
        });

        /* Set Props */
        $this->cleanerService    = $cleanerServices->where('services_items_id', $this->details['serviceItemId'] );
        $this->addOns            = $cleanerServices->whereIn('services_items_id', $this->details['addOnIds']);
        $this->estimatedDuration = $cleanerServices->sum('duration');
    }

    protected function preparePricingProps()
    {
        $this->subtotal = $this->cleanerService->first()->rate + $this->addOns->sum('rate');
        $this->tax      = 0; // TODO: make this dynamic
        $this->transactionFees = ( $this->subtotal + $this->tax ) / 100 * 2; // TODO: transaction fees calculation formula needed. 2% for make it work
        $this->total    = $this->subtotal + $this->tax + $this->transactionFees;
    }

    protected function handleLoggedInUser()
    {
        if ( ! Auth::check() ){
            /* This prop will help for redirecting to same page if user logs in while doing checkout */
            $this->currentPageUrl = request()->url();
            return true;     
        }

        /* Assigning user object props to this component in order to fill the form values */
        $this->user = Auth::user();
        $this->firstname = $this->user->first_name;
        $this->lastname  = $this->user->last_name;
        $this->email     = $this->user->email;
        $this->contact   = $this->user->contact_number;
        $this->address   = $this->user->UserDetails->address;
        $this->aptOrUnit = $this->user->UserDetails->apt_or_unit;
        $this->city      = $this->user->UserDetails->city;
        $this->zip       = $this->user->UserDetails->zip_code;
        $this->stateId   = $this->user->UserDetails->states_id;

        return true;
    }
    /*
     * Prepare properties to make the component work 
     * 
     */
    protected function prepare()
    {
        /* Add data to the props that are essential to make the component function */
        $this->cleaner      = User::find( $this->details['cleanerId'] );
        $this->datetime     = Carbon::createFromFormat('Y-m-d H:i:s', $this->details['selected_date']." ".$this->details['time'] )->toDayDateTimeString();
        $this->homeSize     = $this->details['homeSize'];
        $this->states       = State::all();

        /* Prepare other props */
        $this->prepareServiceAddOnsAndEstimatedDurationProps();
        $this->preparePricingProps();
    }

    public function hydrate()
    {
        $this->resetErrorBag();
    }

    public function next()
    {
        $this->currentlyActiveStep += 1;        
    }

    public function previous()
    {
        $this->currentlyActiveStep -= 1;
        $this->prepare(); // preparing again because livewire removes the attributes from database collections
    }

    /*
     * Used for authenticating customers that are
     * already have an account with us.
     * 
     */
    public function authenticateUser()
    {
        $this->validate([
            'email' => 'required|exists:users,email', 
            'password' => 'required'
        ]);


        $user = User::where('email', $this->email )->first();

        if ( ! Hash::check( $this->password, $user->password ) ){
            $this->addError('password', 'Input credentials are not matched in our records.');
            return true; 
        }

        auth()->loginUsingId($user->id);
        
        // redirecting to same page to change the design of header
        return redirect( $this->currentPageUrl."?step=2" );
    }

    protected function passwordValidationRules()
    {
        return [ 
            'password'  => 'required',
            'confirmPassword' => 'required|same:password'
        ];
    }

    protected function cardValidationRules()
    {
        return [
            'number'       => 'required|numeric',
            'expMonthYear' => 'required',
            'cvc'          => 'required|numeric',
            'expMonth'     => 'required|numeric',
            'expYear'      => 'required|numeric'
        ];
    }

    protected function checkoutRules()
    {
        $rules = [
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required',
            'aptOrUnit' => 'required',
            'address'   => 'required',
            'city'      => 'required',
            'stateId'     => 'required|exists:states,id',            
            'zip'       => 'required', 
            'paymentMethod' => 'required',           
        ];

        if ( ! $this->user ) {
            $rules          = array_merge( $rules, $this->passwordValidationRules() );
            $rules['email'] = $rules['email']."|unique:users";
        }

        if ( $this->paymentMethod == 'credit_card'){
            $rules = array_merge( $rules, $this->cardValidationRules() );
        }

        /* Customize validation messages */
        $messages = [
            'firstname.required' => 'First name is required'
        ];

        return [ $rules, $messages ];
    }

    protected function storeUserDetails($user_id)
    {
        $userDetails = UserDetails::create([
            'user_id'     => $user_id,
            'states_id'   => $this->stateId,
            'address'     => $this->address,
            'apt_or_unit' => $this->aptOrUnit,
            'city'        => $this->city,
            'zip_code'    => $this->zip,
            'payment_method'     => $this->paymentMethod,
            'stripe_customer_id' => $this->stripe_customer_id,
        ]);


        // TODO: store lat/lng 

        return $userDetails;
    }

    /*
     * 
     * @return: object ( instance of App\Models\User )
     * 
     */
    protected function storeUserAsCustomer()
    {
        $user = User::create([
            'role' => 'customer',
            'first_name' => $this->firstname,
            'last_name'  => $this->lastname,
            'email'      => $this->email,
            'password'   => Hash::make( $this->password ),
            'status'     => '1',
            'contact_number' => $this->contact,
        ]);
        
        $this->storeUserDetails($user->id);

        return $user;
    }

    /*
     * @param: int $user_id ( primary key of App\Models\User )
     * 
     * @return: object ( instance of App\Models\Order )
     * 
     */
    protected function storeOrder($user_id)
    {
        $order = Order::create([
            'user_id'     => $user_id,
            'is_paid'     => 0,
            'state_id'    => $this->stateId,
            'address'     => $this->address,
            'apt_or_unit' => $this->aptOrUnit,
            'city'        => $this->city,
            'zip'         => $this->zip,            
            'first_name'  => $this->firstname,
            'last_name'   => $this->lastname,
            'tax'         => $this->tax,
            'transaction_fees' => $this->transactionFees,
            'subtotal'         => $this->subtotal,
            'total'            => $this->total,
            'payment_method'   => $this->paymentMethod,
            'home_size_sq_ft'  => $this->homeSize,
            'cleaning_datetime'        => Carbon::createFromFormat('Y-m-d H:i:s', $this->details['selected_date']." ".$this->details['time'] ),
            'estimated_duration_hours' => $this->estimatedDuration,
            'cleaner_id'               => $this->cleaner->id
            
        ]);

        return $order;
    }

    /*
     * @param: int $order_id ( primary key of App\Models\Order )
     * 
     * @return: int (number of items inserted in DB)
     */

    protected function storeOrderItems($order_id)
    {
        $cleanerServices = $this->cleanerService->concat( $this->addOns );

        /* Make an array of order items that can be used to insert in table at once */
        $orderItems = $cleanerServices->map( function($cleanerService, $key) use( $order_id ) {
            $orderItem = [
                'order_id'           => $order_id,
                'cleaner_service_id' => $cleanerService->id, 
                'price_per_sq_ft'    => $cleanerService->priceForSqFt(1),
                'created_at'         => now(),
                'updated_at'         => now(),
            ];

            return $orderItem;
        });

        /* Insert order items in table */
        $result = OrderItem::insert( $orderItems->toArray() );

        return $result;
    }

    protected function validateCardWithStripe()
    {
        $card = [
            'number'    => $this->number,
            'exp_month' => $this->expMonth,
            'exp_year'  => $this->expYear,
            'cvc'       => $this->cvc,
        ];

        $tokenResp = stripeGenerateCardToken($card);
        return $tokenResp;
    }

    /*
     * 1. Validate credit card
     * 2. Store new customer in stripe along attaching the card with it
     * 
     * @return: array
     */
    protected function handleCreditCardPaymentMethodSelection()
    {
        $tokenResp = $this->validateCardWithStripe();

        if ( $tokenResp['status']  == false ){
            $this->addError('stripe_card_verification', $tokenResp['error_string']);
            return [ 'status' => false, 'error' => $tokenResp['error_string'] ];
        }

        $name = "$this->firstname $this->lastname";

        $customer = stripeCreateCustomerWithSource($name, $this->email, $tokenResp['token_string']);

        $this->stripe_customer_id = $customer->id;
        return ['status' => true, 'response' => $customer ];
    }

    /*
     * Store order and data related to it in DB
     * 
     * @return: App\Models\Order 
     * 
     */
    protected function placeOrder()
    {
        /* Handle credit card verification */
        if ( $this->paymentMethod == 'credit_card') {
            $result = $this->handleCreditCardPaymentMethodSelection();
            if ( $result['status'] == false ) {
                return false;    
            }
        }

        /* Handle guest user */
        if ( is_null( $this->user ) ) {
            $this->user = $this->storeUserAsCustomer();
        }

        /* Store order */
        $order      = $this->storeOrder($this->user->id);
        $orderItems = $this->storeOrderItems($order->id);

        $this->order = $order;
        return true;
    }   

    public function schedule()
    {
        $validatedData = $this->validate( ...$this->checkoutRules() );
        $status        = $this->placeOrder();

        if ( $status ) {
            $this->currentlyActiveStep++;
        }        
    }

    public function saveOrderNotes()
    {
        $this->order->notes = $this->notes;
        $this->order->save();
        $this->alert('success', 'Notes Sent!');     
    }

    public function updatingExpMonthYear($value)
    {
    }

    public function mount()
    {
        $this->prepare();
        $this->handleLoggedInUser();
    }
    
    public function render()
    {
        $this->emit('componentRendered', $this->currentlyActiveStep );
        return view('livewire.home.checkout');
    }
}