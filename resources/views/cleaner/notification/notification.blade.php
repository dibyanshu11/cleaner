@extends('layouts.cleanerapp')

@section('content')
<section class="light-banner customer-account-page" style="background-image: url('assets/images/white-pattern.png')">
    <div class="container">
        <div class="customer-white-wrapper">
            <div class="row no-mrg">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 no-padd">
                    <div class="blue-bg-wrapper bar_left">
                        <div class="blue-bg-heading">
                            <h4>Settings</h4>
                        </div>
                        @include('layouts.common.sidebar')
                        <div class="blue-logo-block text-center max-width-100">
                            <a href="#"><img src="assets/images/logo/logo.svg"></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 no-padd">
                    <div class="row no-mrg">
                        <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 no-padd">

                            <div class="customer-account-forms notifiction_form_section">

                                <div class="form-headeing-second">
                                    <h4>Notifications </h4>
                                </div>

                                <div class="notification_toggles mt-4">
                                    @livewire('cleaner.notification.notification')

                                    <a href="#" class="b_link">Don’t worry - these changes will not affect your invoice, appointment reminder, or other service critical notifications.</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection