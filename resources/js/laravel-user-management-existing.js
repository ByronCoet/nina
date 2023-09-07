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

    // changing the title of offcanvas
    $('#offcanvasAddUserLabel').html('Capture Donation');

    // get data
    $.get(`${baseUrl}user-list-existing\/${userid}\/edit`, function (data) {
      $('#userid').val(data.users.userid);

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
