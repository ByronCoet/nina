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
        { data: 'id' },
        { data: 'name' },
        { data: 'surname' },
        { data: 'company' },
        { data: 'mobile' },        
        { data: 'email' },                
        { data: 'action' }
      ],
      columnDefs: [
        {
          // For Responsive
          className: 'control',
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
            return `<span>${full.fake_id}</span>`;
          }
        },
        {          
          targets: 2,
          render: function (data, type, full, meta) {
            var $name = full['name'];
            return '<span class="text-body text-truncate"  style="color:white;">' + $name + '</span>';
          }
        },
        {          
          targets: 3,
          render: function (data, type, full, meta) {
            var $surname = full['surname'];
            return '<span class="text-body text-truncate" style="color:white;" >' + $surname + '</span>';
          }
        },
        {
          // company
          targets: 4,
          render: function (data, type, full, meta) {
            var $company = full['company'];
            return '<span class="text-body text-truncate">' + $company + '</span>';
          }
        },
        {
          // mobile
          targets: 5,
          render: function (data, type, full, meta) {
            var $mobile = full['mobile'];
            return '<span class="user-mobile">' + $mobile + '</span>';
          }
        },
        {
          // emial
          targets: 6,
          render: function (data, type, full, meta) {
            var $email = full['email'];
            return '<span class="user-email">' + $email + '</span>';
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
              `<button class="btn btn-sm btn-icodn btn-danger edit-record" data-id="${full['id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser"><i class="bx bx-donate-blood"></i>Donate</button>` +
              // `<button class="btn btn-sm btn-icon delete-record" data-id="${full['id']}"><i class="bx bx-trash"></i></button>` +
              // '<button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"> + 
              // '<i class="bx bx-dots-vertical-rounded"></i></button>' +
              // '<div class="dropdown-menu dropdown-menu-end m-0">' +
              //'<a href="' +
              //userView +
              //'" class="dropdown-item">View</a>' +
              //'<a href="javascript:;" class="dropdown-item">Suspend</a>' +
              //'</div>' +
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

        /*  {
          extend: 'collection',
          className: 'btn btn-outline-secondary dropdown-toggle mx-3',
          text: '<i class="bx bx-export me-2"></i>Export',
          buttons: [
  
              extend: 'print',
              
              title: 'Users',
              text: '<i class="bx bx-printer me-2" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [2, 3],
                // prevent avatar to be print
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('user-name')) {
                        result = result + item.lastChild.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function (win) {
                //customize print view for dark
                $(win.document.body)
                  .css('color', config.colors.headingColor)
                  .css('border-color', config.colors.borderColor)
                  .css('background-color', config.colors.body);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              title: 'Users',
              text: '<i class="bx bx-file me-2" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [2, 3],
                // prevent avatar to be print
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList.contains('user-name')) {
                        result = result + item.lastChild.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'excel',
              title: 'Users',
              text: '<i class="bx bxs-file-export me-2"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [2, 3],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList.contains('user-name')) {
                        result = result + item.lastChild.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'pdf',
              title: 'Users',
              text: '<i class="bx bxs-file-pdf me-2"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [2, 3],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList.contains('user-name')) {
                        result = result + item.lastChild.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'copy',
              title: 'Users',
              text: '<i class="bx bx-copy me-2" ></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [2, 3],
                // prevent avatar to be copy
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList.contains('user-name')) {
                        result = result + item.lastChild.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
            
          ]
        },        
        {
          text: '<i class="bx bx-plus me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add New User</span>',
          className: 'add-new btn btn-primary',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasAddUser'
          }
        }
        */
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

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var user_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // sweetalert for confirmation of delete
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        // delete the data
        $.ajax({
          type: 'DELETE',
          url: `${baseUrl}user-list-existing/${user_id}`,
          success: function () {
            dt_user.draw();
          },
          error: function (error) {
            console.log(error);
          }
        });

        // success sweetalert
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'The user has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The User is not deleted!',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });

  // edit record
  $(document).on('click', '.edit-record', function () {
    var user_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');
    
    $('#eventdate').val('');
    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // changing the title of offcanvas
    $('#offcanvasAddUserLabel').html('Capture Donation');

    // get data
    $.get(`${baseUrl}user-list-existing\/${user_id}\/edit`, function (data) {
      $('#user_id').val(data.users.id);
      // $('#eventdate').val('');
      
      console.log("set event date nullxxx");
      //$('#add-user-name').val(data.users.name);
      //$('#add-user-surname').val(data.users.surname);
      //$('#add-user-contact').val(data.users.mobile);
      //$('#add-user-email').val(data.users.email);
      //$('#add-user-role').val(data.users.role);

      /*
      var model = $('#company-id');
      model.empty();  
      $.each(data.companies, function(index, element) {
        var option = document.createElement("option");
        option.value = element.id;
        option.text = element.company_name;

        //console.log(element.id);
        //aconsole.log(data.users.company_id);

        if (element.id === data.users.company_id)
        {
          option.selected = true;
        }
        model.append(option);
      });      
      */
      /*
      var model = $('#user-role');
      model.empty();
      console.log("roles");
      $.each(data.roles, function(index, element) {
        var option = document.createElement("option");
        option.value = element.role_name;
        option.text = element.role_name;

        console.log(element.role_name);

        if (element.role_name === data.users.role)
        {
          option.selected = true;
        }
        
        model.append(option);
      });
      */
    });
  });

  // changing the title and grabbing company details - this is for the admin
  $('.add-new').on('click', function () {
    $('#user_id').val(''); //resetting input field
    $('#offcanvasAddUserLabel').html('Add User');

      console.log("get companies");
      // get data
      $.get(`${baseUrl}all-companies`, function (data) {      

        var model = $('#company-id');
        model.empty();
        console.log("get companies 1");
        $.each(data.companies, function(index, element) {
          var option = document.createElement("option");
          option.value = element.id;
          option.text = element.company_name;

          console.log(element.company_name);
          
          model.append(option);
        });

        var model = $('#user-role');
        model.empty();
        console.log("roles");
        $.each(data.roles, function(index, element) {
          var option = document.createElement("option");
          option.value = element.role_name;
          option.text = element.role_name;

          console.log(element.role_name);
          
          model.append(option);
        });
        
      });
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);

  // validating form and updating user's data
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
        Swal.fire({
          title: 'Error!',
          text: 'Error occured.',
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
