@extends('layouts.adminapp')
@section('content')
  <section class="table-layout-sec jobs">
    <div class="white-bg-wrapper">
    <div class="table-header-wrapper">
    <div class="table-tabs-wrap">
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#all">All <span class="data-span">(5,249)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#active">Active <span class="data-span">(199)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#Inactive">Inactive <span class="data-span">(3,949)</span></a>
      </li>
    </ul>
    </div>
    <div class="table-right-block">
      <div class="dropdown">
        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
          All <img src="../assets/admin/images/icons/all-filter.svg">
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
   @livewire('admin.customer.customer')

     </div>
      </div>
     <!--  <div class="tab-pane fade" id="active">
        <div class="table-design">
          <table id="active-customers-table" class="table dt-responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Name #</th>
                    <th>Email</th>
                    <th>Jobs</th>
                    <th>Last Job</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Join date</th>
                    <th>Total Spend</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="name"><a href="customer-account-info.html">Jenny Wilson</a></td>
                    <td>alexdanss14@gmail.com</td>
                    <td>12</td>
                    <td>2/14/22</td>
                    <td>New Orleans</td>
                    <td>LA</td>
                    <td>USA</td>
                    <td>2/14/22</td>
                    <td>$2,555</td>
                    <td class="status">
                      <span class="active">Active</span>
                    </td>
                </tr>
           </tbody>
          </table>
          </div>
      </div> -->
     <!--  <div class="tab-pane fade" id="Inactive">
        <div class="table-design">
          <table id="inactive-customers-table" class="table dt-responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Name #</th>
                    <th>Email</th>
                    <th>Jobs</th>
                    <th>Last Job</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Join date</th>
                    <th>Total Spend</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="name"><a href="customer-account-info.html">Jenny Wilson</a></td>
                    <td>alexdanss14@gmail.com</td>
                    <td>12</td>
                    <td>2/14/22</td>
                    <td>New Orleans</td>
                    <td>LA</td>
                    <td>USA</td>
                    <td>2/14/22</td>
                    <td>$2,555</td>
                    <td class="status">
                       <span class="inactive">Inactive</span>
                    </td>
                </tr>
               
            </tbody>
          </table>
          </div>
      </div> -->
    </div>
    </div>
   </section>
 
  
 @endsection