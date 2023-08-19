@extends('layouts/layoutMaster')

@section('title', 'User Management - Crud App')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/pickr/pickr.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('js/laravel-user-management-existing.js')}}"></script>
@endsection

@section('content')

<h2 style="color:#00264F; font-style: italic;">We save lives</h2>
<h4 style="color:#CBDA3B; font-style: italic; margin-top:-15px;">One bag at a time</h4>
<h6>Current Campaign: <span style="color:white; margin-top:-25px; ">{{ $site_settings->campaign_name ?? 'not set' }}</span></h6>

<div class="row g-4 mb-4">
  

</div>
<!-- Users List Table -->
<div class="card"  style="background-color:#00B3DC;">
  <div class="card-header  bc_tc">
    <h5 class="card-title mb-0 bc_tc" >Search for existing user and click donate</h5>
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatables-users table border-top">
      <thead>
        <tr>
          <th></th>
          <th>Id</th>
          <th>Name</th>
          <th>Surname</th>
          <th>Company</th>
          <th>Mobile</th>          
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- Offcanvas to add new user -->
  <div class="offcanvas offcanvas-end" style="background-color:#00B3DC;"  tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Capture Donation</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
      <form class="add-new-user pt-0" id="addDonationForm">
        <input type="hidden" name="id" id="user_id">

        <!-- donation -->
        
        <div class="mb-3">
          <label class="form-label bc_tc" for="eventdate">Event date</label>          
          <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="eventdate" name="eventdate" />
          </select>
        </div>

        <div class="mb-3">
          <label class="switch  bc_tc">                  
            <input type="checkbox" class="switch-input" name="donate" >
            <span class="switch-toggle-slider">
              <span class="switch-on">
                <i class="bx bx-check"></i>
              </span>
              <span class="switch-off">
                <i class="bx bx-x"></i>
              </span>
            </span>
            <span class="switch-label" style="color:white;">Were you able to donate?</span>                  
          </label>
        </div>

        <div class="mb-3">
          <label class="switch">                  
            <input type="checkbox" class="switch-input" name="convert" >
            <span class="switch-toggle-slider">
              <span class="switch-on">
                <i class="bx bx-check"></i>
              </span>
              <span class="switch-off">
                <i class="bx bx-x"></i>
              </span>
            </span>
            <span class="switch-label" style="color:white;">Did you convert a colleague?</span>                  
          </label>
        </div>

        <div class="mb-3">
          <label class="switch">                  
            <input type="checkbox" class="switch-input" name="support" >
            <span class="switch-toggle-slider">
              <span class="switch-on">
                <i class="bx bx-check"></i>
              </span>
              <span class="switch-off">
                <i class="bx bx-x"></i>
              </span>
            </span>
            <span class="switch-label" style="color:white;">Did you support a colleague?</span>                  
          </label>
        </div>

        <!-- end donation -->

        <!--         

        <div class="mb-3">
          <label class="form-label" for="add-user-name">First Name</label>
          <input type="text" class="form-control" id="add-user-name" placeholder="John" name="name" aria-label="John" />
        </div>

        <div class="mb-3">
          <label class="form-label" for="add-user-surname">Surname</label>
          <input type="text" class="form-control" id="add-user-surname" placeholder="Doe" name="surname" aria-label="Doe" />
        </div>
        
        <div class="mb-3">
          <label class="form-label" for="user-role">User Role</label>
          <select id="user-role" class="form-select" name="role">
            
          </select>
        </div>
        -->
        <!--
        <div class="mb-4">
          <label class="form-label" for="user-plan">Select Plan</label>
          <select id="user-plan" class="form-select">
            <option value="basic">Basic</option>
            <option value="enterprise">Enterprise</option>
            <option value="company">Company</option>
            <option value="team">Team</option>
          </select>
        </div>
        -->
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>
@endsection
