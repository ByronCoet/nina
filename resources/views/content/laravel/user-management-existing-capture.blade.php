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

<!--  src="{{--asset('js/laravel-user-management-existing.js')--}}" -->
<script >
/**
 * Page User List
 */

'use strict';

(function () {
  // Flat Picker
  // --------------------------------------------------------------------

  const eventdate = document.querySelector('#eventdate');

  // Date
  if (eventdate) {
    eventdate.flatpickr({
      monthSelectorType: 'static',      
    });
  }
})();

// Datatable (jquery)
$(function () {
  // Variable declaration for table
  var dt_user_table = $('.datatables-users'),
    select2 = $('.select2'),
    userView = baseUrl + 'app/user/view/account',
    offCanvasForm = $('#offcanvasAddUser');

  if (select2.length) {
    var $this = select2;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Select Country',
      dropdownParent: $this.parent()
    });
  }

  // ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Users datatable
  if (dt_user_table.length) {
    var dt_user = dt_user_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + 'user-list-existing'
      },
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'userid' },
        { data: 'name' },
        { data: 'surname' },
        { data: 'company' },
        { data: 'mobile' },
        { data: 'action' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
          className: 'bc_tc', 
          searchable: false,
          orderable: false,
          responsivePriority: 2,
          targets: 0,
          render: function (data, type, full, meta) {
            return '';
          }
        },
        {
          searchable: false,
          orderable: false,
          targets: 1,
          render: function (data, type, full, meta) {
            return `<span style="colsr: white !important;">${full.fake_id}</span>`;
          }
        },
        {          
          targets: 2,
          render: function (data, type, full, meta) {
            var $name = full['name'];
            return '<span class="text-body text-truncate bc_tc" style="colsor: white !important;">' + $name + '</span>';
          }
        },
        {          
          targets: 3,
          render: function (data, type, full, meta) {
            var $surname = full['surname'];
            return '<span class="text-body text-truncate" style="coslor: white !important;">' + $surname + '</span>';
          }
        },
        {
          // company
          targets: 4,
          render: function (data, type, full, meta) {
            var $company = full['company'];
            return '<span class="text-body text-truncate" style="colsor: white !important;">' + $company + '</span>';
          }
        },
        {
          // mobile
          targets: 5,
          render: function (data, type, full, meta) {
            var $mobile = full['mobile'];
            return '<span class="user-mobile" style="coslor: white !important;">' + $mobile + '</span>';
          }
        },        
        {
          // Actions
          targets: -1,
          title: 'Actions',
          searchable: false,
          orderable: false,
          render: function (data, type, full, meta) {
            //console.log(full);
            return (
              '<div class="d-inline-block text-nowrap">' +
              `<button class="btn btn-sm btn-icodn btn-danger edit-record" data-userid="${full['userid']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser"><i class="bx bx-donate-blood"></i>Donate</button>` +              
              '</div>'
            );
          }
        }
      ],
      order: [[2, 'desc']],
      dom:
        '<"row mx-2"' +
        '<"col-md-2"<"me-3"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search..'
      },
      // Buttons with Dropdown
      
      buttons: [


      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['name'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
  }


  // edit new donation record
  $(document).on('click', '.edit-record', function () {
    var userid = $(this).data('userid'),
      dtrModal = $('.dtr-bs-modal.show');
      console.log("userid");
      console.log(userid);  

      const myFlatpickrInstance = $("#eventdate").flatpickr({
        enableTime: false,
        altInput: true,
        allowInput: true,
      });
      myFlatpickrInstance.setDate(new Date(), true);

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    $('#userid').val(userid);

    // changing the title of offcanvas
    $('#offcanvasAddUserLabel').html('Capture Donation');

    // get data
    $.get(`${baseUrl}user-list-existing\/${userid}\/edit`, function (data) {

      console.log(data.users.id);
      // $('#userid').val(data.users.id);

      // $('#eventdate').val('');
      
      console.log("received ");
      console.log(data);

    });
  });

  
  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);

  // validating form and updating donation's data - saving new donation
  const addDonationForm = document.getElementById('addDonationForm');

  // user form validation
  const fv = FormValidation.formValidation(addDonationForm, {
    fields: {
      eventdate: {
        validators: {
          notEmpty: {
            message: 'Please enter event date'
          }
        }
      }      
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        eleValidClass: '',
        rowSelector: function (field, ele) {
          // field is the field name & ele is the field element
          return '.mb-3';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      // Submit the form when all fields are valid
      // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  }).on('core.form.valid', function () {
    // adding or updating user when form successfully validate
    $.ajax({
      data: $('#addDonationForm').serialize(),
      url: `${baseUrl}user-list-existing`,
      type: 'POST',
      success: function (status) {
        dt_user.draw();
        offCanvasForm.offcanvas('hide');
        fv.resetForm(true);

        // sweetalert
        Swal.fire({
          icon: 'success',
          title: `Successfully ${status}!`,
          text: `Donation captured.`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      },
      error: function (err) {        
        offCanvasForm.offcanvas('hide');
        const obj = JSON.parse(err.responseText);
        Swal.fire({
          title: 'Donation capture failed',
          text: obj.message,
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });

  // clearing form data when offcanvas hidden
  offCanvasForm.on('hidden.bs.offcanvas', function () {
    fv.resetForm(true);
    $('#eventdate').val('');

  });

  
});


</script>
@endsection

@section('content')

<h2 style="color: #00263E; font-style: italic;">We save lives. One bag at a time.</h2>
 <!-- <h4 style="color:#919d1a; font-style: italic; margin-top:-15px;">One bag at a time</h4> -->
<h6>Current Campaign: <span style="color:#00263E; margin-top:-25px; ">{{ $site_settings->campaign_name ?? 'not set' }}</span></h6>

<div class="row g-4 mb-4">
  

</div>
<!-- Users List Table -->
<div class="card"  style="bacdkground-color:#00B3DC;">
  <div class="card-header  bsc_tc">
    <h5 class="card-title mb-0 bsc_tc" >Search for existing user and click donate</h5>
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
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- Offcanvas to add new user -->
  <div class="offcanvas offcanvas-end" style="background-color:#bae2f3;"  tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Capture Donation</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
      <form class="add-new-user pt-0" id="addDonationForm">
        <input type="hidden" name="userid" id="userid">

        <!-- donation -->
        
        <div class="mb-3">
          <label class="form-label bxc_tc" for="eventdate">Event date</label>          
          <input type="text" class="form-control" placeholder="YYYY-MM-DD" id="eventdate" name="eventdate" />
          </select>
        </div>

        <div class="mb-3">
          <label class="switch  bxc_tc">                  
            <input type="checkbox" class="switch-input" name="donate" >
            <span class="switch-toggle-slider">
              <span class="switch-on">
                <i class="bx bx-check"></i>
              </span>
              <span class="switch-off">
                <i class="bx bx-x"></i>
              </span>
            </span>
            <span class="switch-label" style="coxlor:white;">Were you able to donate?</span>                  
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
            <span class="switch-label" style="coloxr:white;">Did you convert a colleague?</span>                  
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
            <span class="switch-label" style="colosr:white;">Did you support a colleague?</span>                  
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
