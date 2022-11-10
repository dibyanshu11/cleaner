@extends('layouts.app')

@section('content')
<section class="authentication-sec light-banner signup-page" style="background-image: url('assets/images/white-pattern.png')">
  <div class="container customer_signup_container signup_all_container">
    <div class="signup-selection-wrapper">
      <div class="selection-wrap d-flex justify-content-spacebw">
        <div class="select-inner-box position-relative customer active">
          <div class="select-signup-img">
            <img src="assets/images/customer.png">
          </div>
          <div class="select-signup-cntnt">
            <p>I’m a Customer</p>
          </div>
          <a href="{{route('signup-customers')}}" class="link-overlay"></a>
        </div>
        <div class="select-inner-box position-relative cleaner">
          <div class="select-signup-img">
            <img src="assets/images/cleaner.png">
          </div>
          <div class="select-signup-cntnt">
            <p>I’m a Cleaner</p>
          </div>
          <a href="{{route('signup-cleaner')}}" class="link-overlay"></a>
        </div>
      </div>
    </div>
    <div class="authentication-form-wrapper">
      <form class="form-design" method="post" action="{{route('register')}}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="user_type" value="customer">
        <div class="row no-mrg">
          <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 no-padd">
            <div class="blue-bg-wrapper">
              <div class="avatar-upload">
                <div class="avatar-edit">
                  <input type='file' id="upload" accept=".png, .jpg, .jpeg" />
                  {!! Form::label('upload','Upload a profile pic', ['class' => 'form-label']) !!}
                  {!! $errors->first('image', '<span class="help-block">:message</span>') !!}
                </div>

                <div class="lawyer_profile-img mb-3">

                <div class="circle avatar-preview" id="uploaded">
                    <div id="imagePreview" class="profile-pic" style="background-image: url(assets/images/thumbnail.png);">
                        <img class="profile-pic" id="imagePreview"  src="">
                    </div>
                    </div>
                    <div class="p-image">
                        <!-- <span class="pencil_icon"><i class="fa-solid fa-pencil upload-button"></i></span> -->
                        <!-- <input class="file-upload" id="upload" type="file" accept="image/*" /> -->
                        <input type="hidden" name="image" id="upload-img" />
                    </div>
                </div>

                <!-- <div class="avatar-preview">
                  <div id="imagePreview" style="background-image: url(assets/images/thumbnail.png);">
                    <button class="delete-btn"><img src="assets/images/icons/delete.svg"></button>
                  </div>
                </div> -->
              </div>
              <div class="form-grouph textarea-single-design">
              {!! Form::label('about','About yourself (Optional)', ['class' => 'form-label']) !!}
              <textarea name="about"> Effective</textarea>
              {!! $errors->first('about', '<span class="help-block">:message</span>') !!}
              </div>
              <div class="folow-us">
                <ul class="list-unstyled d-flex justify-content-center">
                  <li><span>Follow Us</span></li>
                  <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                  <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                  <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                  <li><a href="#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                </ul>
              </div>
              <div class="blue-logo-block text-center">
                <a href="#"><img src="{{asset('assets/images/logo/logo.svg')}}"></a>
              </div>
            </div>
          </div>
          <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 no-padd">
            <div class="singup_auth-design">
              <div class="account-header-heading text-center">
                <h4><img src="assets/images/icons/white-circle-user.svg"> Account Info</h4>
              </div>
              <div class="form-heading-h4 text-center">
                <h4>Please enter the following information to create your account</h4>
              </div>
              <div class="signup-form-grouph">
                <div class="form-flex two-column">
                  <div class="form-left-block">
                    <div class="form-grouph input-design mb-30">
                    {!! Form::text('first_name', request()->first_name ?? null, ['placeholder' => 'First name','class' => 'form-control'.($errors->has('first_name') ? ' is-invalid' : '')]) !!}
                    {!! $errors->first('first_name', '<span class="alert">:message</span>') !!}
                    </div>

                    <div class="form-grouph input-design mb-30">
                    {!! Form::text('last_name', request()->last_name ?? null, ['placeholder' => 'Last name','class' => 'form-control'.($errors->has('last_name') ? ' is-invalid' : '')]) !!}
                    {!! $errors->first('last_name', '<span class="alert">:message</span>') !!}
                    </div>
                    <div class="form-grouph input-design mb-30">
                    {!! Form::email('email', request()->email ?? null, ['placeholder' => 'Email (this will be your login)','class' => 'form-control'.($errors->has('email') ? ' is-invalid' : '')]) !!}
                    {!! $errors->first('email', '<span class="alert">:message</span>') !!}
                    </div>
                    <div class="form-grouph input-design mb-30">
                    {!! Form::text('address', request()->address ?? null, ['placeholder' => 'Address','class' => 'form-control'.($errors->has('address') ? ' is-invalid' : '')]) !!}
                    {!! $errors->first('address', '<span class="alert">:message</span>') !!}
                    </div>
                    <div class="form-grouph mb-30 input-select-abs">
                      <div class="inputs-box">
                      {!! Form::text('city', request()->city ?? null, ['placeholder' => 'City','class' => 'form-control'.($errors->has('city') ? ' is-invalid' : '')]) !!}
                      {!! $errors->first('city', '<span class="alert">:message</span>') !!}
                      </div>
                      <div class="selecti-box">
                        <select class="select-custom-design" name="state" value="{{old('state')}}">
                          @foreach ($states as $state )
                          <option value='{{ $state->id }}'>{{$state->name}}</option>
                          @endforeach
                        </select>
                        {!! $errors->first('state', '<span class="alert">:message</span>') !!}
                      </div>
                    </div>
                  </div>
                  <div class="form-right-block">
                    <div class="form-grouph input-design mb-30">
                    <input type="password" id="password" name="password" class="form-control{!! ($errors->has('password') ? ' is-invalid' : '') !!}" />
                    {!! $errors->first('password', '<span class="alert">:message</span>') !!}
                    </div>
                    <div class="form-grouph input-design mb-30">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control{!! ($errors->has('password_confirmation') ? ' is-invalid' : '') !!}" />
                    {!! $errors->first('password_confirmation', '<span class="alert">:message</span>') !!}
                    </div>
                    <div class="form-grouph input-design mb-30">
                    {!! Form::number('contact_number', request()->contact_number ?? null, ['placeholder' => 'Phone Number','class' => 'form-control'.($errors->has('contact_number') ? ' is-invalid' : '')]) !!}
                    {!! $errors->first('contact_number', '<span class="alert">:message</span>') !!}
                    </div>
                    <div class="form-grouph input-design mb-30">
                    {!! Form::number('apt_or_unit', request()->apt_or_unit ?? null, ['placeholder' => 'Apt # or Unit #','class' => 'form-control'.($errors->has('apt_or_unit') ? ' is-invalid' : '')]) !!}
                    {!! $errors->first('apt_or_unit', '<span class="alert">:message</span>') !!}
                    </div>
                    <div class="form-grouph input-design mb-30">
                    {!! Form::number('zip_code', request()->zip_code ?? null, ['placeholder' => 'Zip','class' => 'form-control'.($errors->has('zip_code') ? ' is-invalid' : '')]) !!}
                    {!! $errors->first('zip_code', '<span class="alert">:message</span>') !!}
                    </div>
                    <div class="form-grouph select-design mb-30">
                      <select class="select-custom-design" name="payment_method" value="{{old('payment_method')}}">
                        <option disabled>Payment Method</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Direct Deposit">Direct Deposit</option>
                      </select>
                      {!! $errors->first('payment_method', '<span class="alert">:message</span>') !!}
                    </div>
                  </div>
                </div>
                <input type="checkbox" name="term">
                {!! $errors->first('term', '<span class="alert">:message</span>') !!}
                <div class="form-flex two-column">
                  <div class="form-left-block">
                    <div class="terms-text">
                      <p>By clicking “Create My Account”, you agree with all Canary Clean’s <a href="#" class="link-design-2">terms and conditions</a> and <a href="#" class="link-design-2">privacy policy</a></p>
                    </div>
                  </div>
                  <div class="form-right-block">
                    <div class="form-grouph submit-design mb-30">
                      <input type="submit" placeholder="Create My Account" value="Create My Account" class="subit-btn-2">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@section('script')
@include('layouts.common.cropper')
@endsection