@extends('layouts/layoutMaster')

@section('title', 'Campaign Management')

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
<script src="{{asset('js/laravel-campaign-management.js')}}"></script>
<script src="{{asset('js/forms-pickers.js')}}"></script>
@endsection

@section('content')

<div class="row g-4 mb-4">
  <div class="col-sm-6 col-xl-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="content-left">
            <span>Campaigns</span>
            <div class="d-flex align-items-end mt-2">
              <h3 class="mb-0 me-2">{{$totalCampaign}}</h3>
              <small class="text-success">(100%)</small>
            </div>
            <small>Total Campaigns</small>
          </div>
          <span class="badge bg-label-primary rounded p-2">
            <i class="bx bx-user bx-sm"></i>
          </span>
        </div>
      </div>
    </div>
  </div>
  
</div>
<!-- camapaigns List Table -->
<div class="card">
  

  <div class="card-header border-bottom">
    <h5 class="card-title">Search Filter</h5>
    <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">      
      <div class="col-md-4 campaign_company"></div>
    </div>
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatables-campaigns table border-top">
      <thead>
        <tr>
          <th></th>
          <th>Id</th>
          <th>Company</th>
          <th>Campaign Name</th>
          <th>Start</th>
          <th>End</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- Offcanvas to add new campaign -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddCampaign" aria-labelledby="offcanvasAddCampaignLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasAddCampaignLabel" class="offcanvas-title">Add Campaign</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
      <form class="add-new-campaign pt-0" id="addNewCampaignForm">
        <input type="hidden" name="id" id="campaign-id">

        <div class="mb-3">
          <label class="form-label" for="add-campaign-name">Campaign Name</label>
          <input type="text" class="form-control" id="add-campaign-name" placeholder="Adcock" name="campaign_name" aria-label="Adcock" />
        </div>

        <div class="mb-3">
          <label class="form-label" for="add-campaign-company-id">Company</label>
          <select id="company-id" name="company_id" class="select2 form-select">                        
          </select>
        </div>
        
        <div class="mb-3">
          <label class="form-label" for="add-campaign-start">Start date</label>          
          <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="add-campaign-start" name="campaign_start" />
          </select>
        </div>
        
        <div class="mb-3">
          <label class="form-label" for="add-campaign-end">End date</label>          
          <input type="text" class="form-control" placeholder="YYYY-MM-DD"  id="add-campaign-end" name="campaign_end" />
          </select>
        </div>

        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>
@endsection
