@extends('layouts.adminapp')
@section('content')
<div>
 <section class="table-layout-sec jobs">
    <div class="white-bg-wrapper">
    <div class="table-header-wrapper">
    <div class="table-tabs-wrap">
    <ul class="nav nav-tabs">
    <!--   <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#all">All <span class="data-span"></span></a>
      </li> -->
     
    </ul>
    </div>
    <div class="table-right-block">
      <div class="dropdown">
        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
          All <img src="{{ asset('assets/admin/images/icons/all-filter.svg') }}">
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="#">Link 1</a></li>
          <li><a class="dropdown-item" href="#">Link 2</a></li>
          <li><a class="dropdown-item" href="#">Link 3</a></li>
        </ul>
      </div>
    </div>
    </div>
    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="all">
        <div class="table-design">
       
             
        <table id="all-customer-table" class="table dt-responsive nowrap" style="width:100%">
          <thead>
              <tr>
                  <th>Name #</th>
                  <th>Email</th>
                  <th>Insured</th>
                  <th>Phone</th>
                  <th>Status</th>
                 
              </tr>
          </thead>
          <tbody>
            @foreach($members as $member)
            @foreach($member->cleanerTeam as $mem)
            
            <tr>
               <td>{{$mem->first_name}} {{$mem->last_name}}</td>
               <td>{{$mem->email}}</td>
               <td>{{$mem->insured}}</td>
               <td>{{$mem->contact_number}}</td>
                <td class="status">
                    <span class="active">Active</span>
                  </td>
            </tr>
           @endforeach
           @endforeach
           
          </tbody>
        </table>
         
        </div>
      </div>
   
    </div>
    </div>
   </section>
 

</div>
@endsection