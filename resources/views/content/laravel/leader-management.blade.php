@extends('layouts/layoutMaster')

@section('title', 'Conation Management')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/pickr/pickr-themes.css')}}" />
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
<script src="{{asset('js/laravel-leader-management.js')}}"></script>
<script src="{{asset('js/forms-pickers.js')}}"></script>
@endsection

@section('content')

<div class="row g-4 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Leader Board</span>
            <div class="d-flex align-items-end mt-2">
              <h3 class="mb-0 me-2">{{$totalDonations}}</h3>            
            </div>            
          </div>
          <span class="badge bg-label-primary rounded p-2">
            <i class="bx bx-user bx-sm"></i>
          </span>
        </div>
      </div>
    </div>
  </div>
  
</div>
<!-- donations List Table -->
<div class="card">
  @if (Auth::user()->role != "receptionist" && Auth::user()->role != "companyadmin")
    <div class="card-header border-bottom">
      <h5 class="card-title">Search Filter</h5>
      <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">      
        <div class="col-md-4 donation_company"></div>
      </div>
    </div>
  @endif
  <div class="card-datatable table-responsive">
    <table class="datatables-donations table border-top">
      <thead>
        <tr>
          <th></th>
          <th>Id</th>
          <th>Company</th>
          <th>Campaign</th>
          <th>Name</th>
          <th>Surname</th>
          <th>Donated</th>
          <th>Converted</th>
          <th>Supported</th>
          <th>Total</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- Offcanvas to add new donation -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddDonation" aria-labelledby="offcanvasAddDonationLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasAddDonationLabel" class="offcanvas-title">Add Donation</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
      <form class="add-new-donation pt-0" id="addNewDonationForm">
        <input type="hidden" name="id" id="donation-id">

        <div class="mb-3">
          <label class="form-label" for="add-donation-name">Donation Name</label>
          <input type="text" class="form-control" id="add-donation-name" placeholder="Adcock" name="donation_name" aria-label="Adcock" />
        </div>

        <div class="mb-3">
          <label class="form-label" for="add-donation-company-id">Company</label>
          <select id="company-id" name="company_id" class="select2 form-select">                        
          </select>
        </div>
        
        <div class="mb-3">
          <label class="form-label" for="add-donation-start">Start date</label>          
          <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="add-donation-start" name="donation_start" />
          </select>
        </div>
        
        <div class="mb-3">
          <label class="form-label" for="add-donation-end">End date</label>          
          <input type="text" class="form-control" placeholder="YYYY-MM-DD"  id="add-donation-end" name="donation_end" />
          </select>
        </div>

        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>
@endsection
