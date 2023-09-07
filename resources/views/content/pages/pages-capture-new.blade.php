@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'LeaderBoard')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bs-stepper/bs-stepper.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/bs-stepper/bs-stepper.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/pickr/pickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/form-wizard-donation-new.js')}}"></script>
<script src="{{asset('js/forms-pickers-donation.js')}}"></script>
@endsection



@section('content')
<h2 style="color: #00263E; font-style: italic;">We save lives. One bag at a time.</h2>
 <!-- <h4 style="color:#919d1a; font-style: italic; margin-top:-15px;">One bag at a time</h4> -->
<h6>Current Campaign: <span style="color:#00263E; margin-top:-25px; ">{{ $site_settings->campaign_name ?? 'not set' }}</span></h6>

<!-- Default -->
<div class="row" >
  <!-- <div class="col-12">
    <h5>Default</h5>
  </div> -->

  <!-- Validation Wizard -->
  <div class="col-12 mb-4">    
    <div id="wizard-validation" class="bs-stepper mt-2">
    
      <div class="bs-stepper-header" >
        <div class="step" data-target="#account-details-validation">
          <button type="button" class="step-trigger">
            <span class="bs-stepper-circle">1</span>
            <span class="bs-stepper-label">Account</span>
          </button>
        </div>        
        <div class="line"></div>
        <div class="step" data-target="#social-links-validation">
          <button type="button" class="step-trigger">
            <span class="bs-stepper-circle">2</span>
            <span class="bs-stepper-label">Donation</span>
          </button>
        </div>
      </div>

      <div class="bs-stepper-content" >      
        <form id="wizard-donation-new-form" onSubmit="return false">
         @csrf 
          <!-- Account Details -->
          <div id="account-details-validation" class="content">
            <!-- <div class="content-header mb-3">
              <h6 class="mb-0">Account Details</h6>
              <small>Enter Your Account Details.</small>
            </div> -->
            <div class="row g-3">            
            <span>Accurately fill in the new donor details.</span>  

              <div class="col-sm-6">
                <label class="form-label" for="formValidationFirstname">First name</label>
                <input type="text" name="formValidationFirstname" id="formValidationFirstname" class="form-control" placeholder="John" />
              </div>

              <div class="col-sm-6">
                <label class="form-label" for="formValidationSurname">Surname</label>
                <input type="text" name="formValidationSurname" id="formValidationSurname" class="form-control" placeholder="Doe" />
              </div>

              <div class="col-sm-6">
                <label class="form-label" for="formValidationMobile">Mobile number</label>
                <input type="text" name="formValidationMobile" id="formValidationMobile" class="form-control" placeholder="0711234567" />
              </div>

              @if (Auth::user()->role != "receptionist" && Auth::user()->role != "companyadmin")
                <div class="col-sm-6">
                  <label class="form-label" for="formValidationCompany">Company</label>
                  <select id="formValidationCompany" name="formValidationCompany" class="select2 form-select">
                    @foreach($companies as $c)
                      <option value="{{ $c->id }}">{{ $c->company_name }}</option>
                    @endforeach
                  </select>
                </div>
              @endif

              <div class="col-12">
                <label class="switch">
                  
                  <input type="checkbox" class="switch-input" name="consent">
                  <span class="switch-toggle-slider">
                    <span class="switch-on">
                      <i class="bx bx-check"></i>
                    </span>
                    <span class="switch-off">
                      <i class="bx bx-x"></i>
                    </span>
                  </span>
                  <span class="switch-label" >Do you give consent for your information to be used for the duration of the campaign to receive
                  leaderboard information?</span>
                  
                </label>
              </div>

              <!--<div class="col-sm-6">
                <label class="form-label" for="formValidationEmail">Email</label>
                <input type="email" name="formValidationEmail" id="formValidationEmail" class="form-control" placeholder="john.doe@email.com" aria-label="john.doe" />
              </div>-->
              
              <div class="col-12 d-flex justify-content-between">
                <button class="btn btn-label-secondary btn-prev" disabled> <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                  <span class="d-sm-inline-block d-none">Previous</span>
                </button>
                <button class="btn btn-primary btn-next"> <span class="d-sm-inline-block d-none me-sm-1">Next</span> <i class="bx bx-chevron-right bx-sm me-sm-n2"></i></button>
              </div>
            </div>
          </div>
          
          <!-- Donation data -->
          <div id="social-links-validation" class="content">            
            <div class="row g-3">

              <div class="col-sm-6">
                <label class="form-label" for="eventdate">Event date</label>          
                <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="eventdate" name="eventdate" />
                </select>
              </div>

              <div class="col-12">
                <label class="switch">                  
                  <input type="checkbox" class="switch-input" name="donate" >
                  <span class="switch-toggle-slider">
                    <span class="switch-on">
                      <i class="bx bx-check"></i>
                    </span>
                    <span class="switch-off">
                      <i class="bx bx-x"></i>
                    </span>
                  </span>
                  <span class="switch-label">Were you able to donate?</span>                  
                </label>
              </div>

              <div class="col-12">
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
                  <span class="switch-label">Did you convert a colleague?</span>                  
                </label>
              </div>

              <div class="col-12">
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
                  <span class="switch-label">Did you support a colleague?</span>                  
                </label>
              </div>

              <div class="col-12 d-flex justify-content-between">
                <button class="btn btn-primary btn-prev"> <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                  <span class="d-sm-inline-block d-none">Previous</span>
                </button>
                <button class="btn btn-success btn-next btn-submit">Submit</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /Validation Wizard -->


</div>

@endsection
