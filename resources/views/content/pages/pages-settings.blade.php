@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'LeaderBoard')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/form-layouts.js')}}"></script>

@endsection

@section('content')
<h4>Set campaign in use</h4>
<h5>Campaign in use is set to: {{ $site_settings->campaign_name }}</h5>

<div class="row">
    <div class="col-xl">
    <div class="card mb-4">
    
    
       <!-- 
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Basic Layout</h5> <small class="text-muted float-end">Default label</small>
      </div>
      -->
        <div class="card-body">

            <form method="post" action="/storecampaign" enctype="multipart/form-data">
            {{ csrf_field() }}
                <div class="mb-3">    
                    <div class="col-sm-6">
                        <label class="form-label" for="formValidationCompany">Company</label>
                        <select id="formValidationCompany" name="formValidationCompany" class="select2 form-select">
                            <option value="0">Select company</option>
                            @foreach($companies as $c)
                                <option value="{{ $c->id }}">{{ $c->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">    
                    <div class="col-sm-6">
                        <label class="form-label" for="formValidationCampaign">Campaign</label>
                        <select id="formValidationCampaign" name="formValidationCampaign" class="select2 form-select">
                            <option value="0">Select campaign</option>                  
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="col-12 d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
  
</div>



@endsection


