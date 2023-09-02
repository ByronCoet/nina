/**
 * Page Donation List
 */

'use strict';

// this grabs campaigns depending on company selected
/*
$(document).ready(function() {
  $('#company-id').on('change', function(e) {
      console.log("on change company-id");
      var comp_id = e.target.value;
      $.ajax({          
          url: baseUrl + 'subcamp',
          type: "POST",
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          data: {
              comp_id: comp_id
          },
          success: function(data) {
              $('#campaign-id').empty();
              // console.log(data);
              $.each(data.camps, function(index,camp) {
                  $('#campaign-id').append('<option value="' + camp
                      .id + '">' + camp.campaign_name + '</option>');
              })
          }
      })
  });
});
*/

// Datatable (jquery)
$(function () {
  // Variable declaration for table
  var dt_donation_table = $('.datatables-donations'),
    select2 = $('.select2'),
    donationView = baseUrl + 'app/donation/view/account',
    offCanvasForm = $('#offcanvasAddDonation');
  
  // ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // donation datatable
  if (dt_donation_table.length) {
    var dt_donation = dt_donation_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + 'donation-list',
        data: function(d){          
          d.extra_search = $('#donationcompany').val();
        }
      },      
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'donation_id' },
        { data: 'company_name' },
        { data: 'campaign_name' },
        { data: 'user_name' },
        { data: 'user_surname' },
        { data: 'donated' },
        { data: 'converted' },
        { data: 'supported' },
        { data: 'edate' },
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
           // company
           targets: 2,           
           render: function (data, type, full, meta) {
             var $company_name = full['company_name'];
             return '<span class="text-body text-truncate">' + $company_name + '</span>';
           }
        },
        {          
          targets: 3,
          render: function (data, type, full, meta) {
            var $campaign_name = full['campaign_name'];
            return '<span class="text-body text-truncate">' + $campaign_name + '</span>';
          }
        },
        {          
          // user name
          targets: 4,           
          render: function (data, type, full, meta) {
            var $user_name = full['user_name'];
            return '<span class="text-body text-truncate">' + $user_name + '</span>';
          }
        },
        {          
          // user_surname
          targets: 5,           
          render: function (data, type, full, meta) {
            var $user_surname = full['user_surname'];
            return '<span class="text-body text-truncate">' + $user_surname + '</span>';
          }
        },
        {          
          targets: 6,
          render: function (data, type, full, meta) {
            var $donated = full['donated'];
            return '<span class="text-body text-truncate">' + $donated + '</span>';
          }
        },
        {          
          targets: 7,
          render: function (data, type, full, meta) {
            var $converted = full['converted'];
            return '<span class="text-body text-truncate">' + $converted + '</span>';
          }
        },
        {          
          targets: 8,
          render: function (data, type, full, meta) {
            var $supported = full['supported'];
            return '<span class="text-body text-truncate">' + $supported + '</span>';
          }
        },
        {          
          targets: 8,
          render: function (data, type, full, meta) {
            var $edate = full['edate'];
            return '<span class="text-body text-truncate">' + $edate + '</span>';
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
              `<button class="btn btn-sm btn-icon edit-record" data-id="${full['donation_id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddDonation"><i class="bx bx-edit"></i></button>` +
              `<button class="btn btn-sm btn-icon delete-record" data-id="${full['donation_id']}"><i class="bx bx-trash"></i></button>` +
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
        searchPlaceholder: 'Searchhh..'
      },
      // Buttons with Dropdown
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-outline-secondary dropdown-toggle mx-3',
          text: '<i class="bx bx-export me-2"></i>Export',
          buttons: [
            {
              extend: 'print',
              title: 'Donations',
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
                      if (item.classList !== undefined && item.classList.contains('donation-name')) {
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
              title: 'Donations',
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
                      if (item.classList.contains('donation-name')) {
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
              title: 'Donations',
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
                      if (item.classList.contains('donation-name')) {
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
              title: 'Donations',
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
                      if (item.classList.contains('donation-name')) {
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
              title: 'Donations',
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
                      if (item.classList.contains('donation-name')) {
                        result = result + item.lastChild.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
          ]
        }
        /*,
        {
          text: '<i class="bx bx-plus me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add New Donation</span>',
          className: 'add-new btn btn-primary',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasAddDonation'
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
              return 'Details of ' + data['donation_name'];
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
      },
      initComplete: function () {
        // Adding company filter once table initialized
        
        this.api()
          .columns(2)
          .every(function () {
            var column = this;
            var select = $(
              '<select id="donationcompany" class="form-select text-capitalize"><option value=""> Select Company </option></select>'
            )
              .appendTo('.donation_company')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                console.log("got here : " + val);
                
                column.search(val ? '^' + val + '$' : '', true, false).draw();
                //column.search(val ? val  : '').draw();
                // $('#datatables-donations').DataTable().search(val).draw();

                // this.search(val);
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + d + '">' + d + '</option>');
              });
          });
      }
    });
  }

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var donationid = $(this).data('id'),
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
          url: `${baseUrl}donation-list/${donationid}`,
          success: function () {
            dt_donation.draw();
          },
          error: function (error) {
            console.log(error);
          }
        });

        // success sweetalert
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'The donation has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The donation is not deleted!',
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
    var donation_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');
    
    console.log("donation id: " + donation_id);

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // changing the title of offcanvas
    $('#offcanvasAddDonationLabel').html('Edit Donation');

    // get data
    $.get(`${baseUrl}donation-list\/${donation_id}\/edit`, function (data) {
      console.log(data);
      $('#donation-id').val(data.donations.id);
      //$('#company-id').val(data.donations.company_id);    
      //$('#campaign-id').val(data.donations.campaign_id);    
      console.log("helooo33");

      // $("#checkbox").prop("checked", true);
      $('#donate').prop("checked", data.donations.donated == 1);    
      $('#convert').prop("checked", data.donations.converted == 1) ;    
      $('#support').prop("checked", data.donations.supported == 1);    
      const myFlatpickrInstance = $("#add-event-date").flatpickr({
        enableTime: false,
        altInput: true,
        allowInput: true,
      });
      myFlatpickrInstance.setDate(data.donations.event_date, true);


      // $('#add-event-date').flatpickr.defaultDate = data.donations.event_date;   // val(data.donations.event_date);    
      /*
      var model = $('#company-id');
      model.empty();
      $.each(data.companies, function(index, element) {
        var option = document.createElement("option");
        option.value = element.id;
        option.text = element.company_name;

        //console.log(element.id);
        //aconsole.log(data.donations.company_id);

        if (element.id === data.donations.company_id)
        {
          option.selected = true;
        }
        model.append(option);
      });
      */    
    });
  });

  // changing the title and grabbing donation details - this is for the admin
  $('.add-new').on('click', function () {
    $('#donation-id').val(''); //resetting input field
    $('#offcanvasAddDonationLabel').html('Add Donation');

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
      });
  });

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);

  // validating form and updating donation's data
  const addNewDonationForm = document.getElementById('addNewDonationForm');

  // donation form validation
  const fv = FormValidation.formValidation(addNewDonationForm, {
    fields: {
      company_id: {
        validators: {
          notEmpty: {
            message: 'Please select company'
          }
        }
      },
      campaign_id: {
        validators: {
          notEmpty: {
            message: 'Please select campaign'
          }
        }
      }
      ,
      company_start: {
        validators: {
          notEmpty: {
            message: 'Please enter start date'
          }
        }
      }
      ,
      company_end: {
        validators: {
          notEmpty: {
            message: 'Please enter end date'
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
    // adding or updating donation when form successfully validate
    $.ajax({
      data: $('#addNewDonationForm').serialize(),
      url: `${baseUrl}donation-list`,
      type: 'POST',
      success: function (status) {
        dt_donation.draw();
        offCanvasForm.offcanvas('hide');

        // sweetalert
        Swal.fire({
          icon: 'success',
          title: `Successfully ${status}!`,
          text: `Donation ${status} Successfully.`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      },
      error: function (err) {
        offCanvasForm.offcanvas('hide');
        Swal.fire({
          title: 'Duplicate Entry!',
          text: 'Your email should be unique.',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });

    
    
  });

 /* $('#donationcompany').on('change', function(){
    console.log(" *** " + this.value);
    // dt_donation_table.search(this.value).draw();   
  }); */



  // clearing form data when offcanvas hidden
  offCanvasForm.on('hidden.bs.offcanvas', function () {
    fv.resetForm(true);
  });

  
});
