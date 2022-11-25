@extends('layouts.app')
@section('content')
<section class="light-banner customer-account-page" style="background-image: url('assets/images/white-pattern.png')">
   <div class="container">
      <div class="customer-white-wrapper search_result_section">
         <div class="result_text">
            <h3>Results that keep you wanting more.</h3>
            <p>See the cleaners available for your selected service and address below. Feel free to filter further using the “Filter by” feature. We hope you enjoy every experience in your journey to find the perfect cleaner. </p>
            <p>Prices listed are for typical cleanings. <strong>If you feel your cleaning needs are unique, send a chat to the cleaner from their profile for a custom quote!</strong></p>
         </div>
         <div class="row routine_service_div">
            <div class="col-xl-4 col-lg-4 col-md-12 p-0 yellow-bg">
               <p><img src="assets/images/icons/2_weaks.svg" class="me-3">Routine Service - Every 2 Weeks</p>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-12 p-0 yellow-bg t-width-auto border-left-sf">
               <p><img src="assets/images/icons/s_feet.svg" class="me-3">2,700 square feet</p>
            </div>
            <div class="col-xl-6 col-lg-4 col-md-16 p-0 white-bordered">
               <p><img src="assets/images/icons/location.svg" class="me-3">12345 East Grandview Avenue #9118, Austin, TX 78787</p>
            </div>
         </div>
         <div class="window_garage py-3">
            <a href="javascript:void(0)" class="link-design-2">(X) Window cleaning</a>
            <span class=" link-design-2 px-2">|</span>
            <a href="javascript:void(0)" class="link-design-2">(X) Garage Sweeping</a>
         </div>
         
         
         @livewire('home.search-result')
         
         
      </div>
   </div>
</section>
@endsection