@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Register Basic - Pages')

@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">
      <!-- Register Card -->
      <div class="card">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="{{url('/')}}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">
                @include('_partials.macroslogin')
              </span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-2">Register here 🚀</h4>
          <p class="mb-4">Enter the correct company!</p>

          <form id="formAuthentication" class="mb-3" action="{{url('/auth/register-store')}}" method="POST">
            @csrf
            
            <div class="mb-3">
              <label for="name" class="form-label">First name</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter your first name" autofocus>
            </div>

            <div class="mb-3">
              <label for="surname" class="form-label">Surname</label>
              <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter your surname" autofocus>
            </div>
            
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email">
            </div>

            <div class="mb-3">
              <label for="mobile" class="form-label">Mobile number</label>
              <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter mobile number">
            </div>

            <div class="mb-3">
              <label for="select2Basic" class="form-label">Company</label>
              <select id="select2Basic" name='company_id' class="select2 form-select" data-allow-clear="true">
                @foreach($companies as $c)
                  <option value="{{$c->id}}">{{$c->company_name}}</option>
                @endforeach                
              </select>
            </div>

            <div class="mb-3 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>

            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms">
                <label class="form-check-label" for="terms-conditions">
                  I agree to
                  <a href="javascript:void(0);">privacy policy & terms</a>
                </label>
              </div>
            </div>
            <button class="btn btn-primary d-grid w-100" type='submit'>
              Sign up
            </button>
          </form>

          <p class="text-center">
            <span>Already have an account?</span>
            <a href="{{url('auth/login-basic')}}">
              <span>Sign in instead</span>
            </a>
          </p>

          <div class="container-fluid d-flex flex-wrap justify-content-between pt-3 flex-md-row flex-column px-0" >
            <div class="smb-0 smb-md-0" >              
                @include('_partials.macrossanbs')
            </div>
            <div  style="padding-top: 45px;">
              @include('_partials.macrosadcock')
            </div>
          </div>

          
      </div>
      <!-- Register Card -->
    </div>
  </div>
</div>
@endsection
