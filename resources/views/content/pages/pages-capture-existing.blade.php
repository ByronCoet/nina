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
@endsection

@section('page-script')
<script src="{{asset('assets/js/form-wizard-validation.js')}}"></script>
<script src="{{asset('js/forms-pickers-donation.js')}}"></script>
@endsection

@section('content')
<h2 style="color: #00263E; font-style: italic;">We save lives. One bag at a time.</h2>
 <!-- <h4 style="color:#919d1a; font-style: italic; margin-top:-15px;">One bag at a time</h4> -->
<h6>Current ss Campaign: <span style="color:#00263E; margin-top:-25px; ">{{ $site_settings->campaign_name ?? 'not set' }}</span></h6>

<!-- Default -->
<div class="row" >
  <!-- <div class="col-12">
    <h5>Default</h5>
  </div> -->

  <!-- Validation Wizard -->
  <div class="col-12 mb-4">    
    <div id="wizard-validation" class="bs-stepper mt-2">
    
      <div class="bs-stepper-header" style="background-color:#00B3DC;">
        <div class="step" data-target="#account-details-validation">
          <button type="button" class="step-trigger">
            <span class="bs-stepper-circle">1</span>
            <span class="bs-stepper-label">Search</span>
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

      <div class="bs-stepper-content"  style="background-color:#00B3DC;">
        <form id="wizard-validation-form" onSubmit="return false">
          <!-- Account Details -->
          <div id="account-details-validation" class="content">
            <!-- <div class="content-header mb-3">
              <h6 class="mb-0">Account Details</h6>
              <small>Enter Your Account Details.</small>
            </div> -->
            <div class="row g-3">              
            
              <!--
              <div class="mb-sm-6">                
                <div class="form-check mb-2">
                  <input type="radio" id="bs-validation-radio-new" name="bs-validation-radio" class="form-check-input" required />
                  <label class="form-check-label" for="bs-validation-radio-new">New user</label>
                </div>
                <div class="form-check">
                  <input type="radio" id="bs-validation-radio-exist" name="bs-validation-radio" class="form-check-input" required />
                  <label class="form-check-label" for="bs-validation-radio-exist">Existing user</label>
                </div>
              </div>
              -->
              <span class="bc_tc" >Select company and one or more of the other fields to search for your user.</span>
              <div class="col-sm-3">
                <label class="form-label bc_tc" for="formValidationFirstname">First name</label>
                <input type="text" name="formValidationFirstname" id="formValidationFirstname" class="form-control" placeholder="John" />
              </div>

              <div class="col-sm-3">
                <label class="form-label bc_tc" for="formValidationSurname">Surname</label>
                <input type="text" name="formValidationSurname" id="formValidationSurname" class="form-control" placeholder="Doe" />
              </div>

              <div class="col-sm-3">
                <label class="form-label bc_tc" for="formValidationMobile">Mobile number</label>
                <input type="text" name="formValidationMobile" id="formValidationMobile" class="form-control" placeholder="0711234567" />
              </div>

              <div class="col-sm-3">
                <label class="form-label bc_tc" for="formValidationCompany">Company</label>
                <select id="formValidationCompany" name="formValidationCompany" class="select2 form-select">
                  @foreach($companies as $c)
                    <option value="{{ $c->id }}">{{ $c->company_name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="col-sm-6">
                <button type="button" class="btn btn-danger">Search</button>
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
          
          <!-- Social Links -->
          <div id="social-links-validation" class="content">            
            <div class="row g-3">

              <div class="col-sm-6">
                <label class="form-label" for="add-event-date">Event date</label>          
                <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="add-event-date" name="add-event-date" />
                </select>
              </div>

              <div class="col-12">
                <label class="switch">                  
                  <input type="checkbox" class="switch-input" >
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
                  <input type="checkbox" class="switch-input" >
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
                  <input type="checkbox" class="switch-input" >
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
