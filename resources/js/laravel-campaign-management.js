/**
 * Page Campaign List
 */

'use strict';

// Datatable (jquery)
$(function () {
  // Variable declaration for table
  var dt_campaign_table = $('.datatables-campaigns'),
    select2 = $('.select2'),
    campaignView = baseUrl + 'app/campaign/view/account',
    offCanvasForm = $('#offcanvasAddCampaign');

  
  // ajax setup
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  

  // campaign datatable
  if (dt_campaign_htable.lengt) {
    var dt_campaign = dt_campaign_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: baseUrl + 'campaign-list',
        data: function(d){          
          d.extra_search = $('#campaigncompany').val();
        }
      },      
      columns: [
        // columns according to JSON
        { data: '' },
        { data: 'comp_id' },
        { data: 'company' },
        { data: 'campaign_name' },
        { data: 'campaign_start' },
        { data: 'campaign_end' },
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
             var $company = full['company'];
             return '<span class="text-body text-truncate">' + $company + '</span>';
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
          targets: 4,
          render: function (data, type, full, meta) {
            var $campaign_start = full['campaign_start'];
            return '<span class="text-body text-truncate">' + $campaign_start + '</span>';
          }
        },
        {          
          targets: 5,
          render: function (data, type, full, meta) {
            var $campaign_end = full['campaign_end'];
            return '<span class="text-body text-truncate">' + $campaign_end + '</span>';
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
              `<button class="btn btn-sm btn-icon edit-record" data-id="${full['comp_id']}" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddCampaign"><i class="bx bx-edit"></i></button>` +
              `<button class="btn btn-sm btn-icon delete-record" data-id="${full['comp_id']}"><i class="bx bx-trash"></i></button>` +
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
              title: 'Campaigns',
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
                      if (item.classList !== undefined && item.classList.contains('campaign-name')) {
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
              title: 'Campaigns',
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
                      if (item.classList.contains('campaign-name')) {
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
              title: 'Campaigns',
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
                      if (item.classList.contains('campaign-name')) {
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
              title: 'Campaigns',
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
                      if (item.classList.contains('campaign-name')) {
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
              title: 'Campaigns',
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
                      if (item.classList.contains('campaign-name')) {
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
          text: '<i class="bx bx-plus me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add New Campaign</span>',
          className: 'add-new btn btn-primary',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasAddCampaign'
          }
        }
      ],
      // For responsive popup
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['campaign_name'];
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
              '<select id="campaigncompany" class="form-select text-capitalize"><option value=""> Select Company </option></select>'
            )
              .appendTo('.campaign_company')
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                console.log("got here : " + val);                
                column.search(val ? '^' + val + '$' : '', true, false).draw();                
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
    var campaignid = $(this).data('id'),
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
          url: `${baseUrl}campaign-list/${campaignid}`,
          success: function () {
            dt_campaign.draw();
          },
          error: function (error) {
            console.log(error);
          }
        });

        // success sweetalert
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'The campaign has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The campaign is not deleted!',
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
    var campaign_id = $(this).data('id'),
      dtrModal = $('.dtr-bs-modal.show');
    
    console.log("campaign id: " + campaign_id);

    // hide responsive modal in small screen
    if (dtrModal.length) {
      dtrModal.modal('hide');
    }

    // changing the title of offcanvas
    $('#offcanvasAddCampaignLabel').html('Edit Campaign');

    // get data
    $.get(`${baseUrl}campaign-list\/${campaign_id}\/edit`, function (data) {
      console.log(data);
      $('#campaign-id').val(data.campaigns.id);
      $('#add-campaign-name').val(data.campaigns.campaign_name);    
      $('#add-campaign-start').val(data.campaigns.campaign_start);    
      $('#add-campaign-end').val(data.campaigns.campaign_end);    
      
      var model = $('#company-id');
      model.empty();
      $.each(data.companies, function(index, element) {
        var option = document.createElement("option");
        option.value = element.id;
        option.text = element.company_name;

        //console.log(element.id);
        //aconsole.log(data.campaigns.company_id);

        if (element.id === data.campaigns.company_id)
        {
          option.selected = true;
        }
        model.append(option);
      });    
    });
  });

  // changing the title and grabbing campaign details - this is for the admin
  $('.add-new').on('click', function () {
    $('#campaign-id').val(''); //resetting input field
    $('#offcanvasAddCampaignLabel').html('Add Campaign');

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

  // validating form and updating campaign's data
  const addNewCampaignForm = document.getElementById('addNewCampaignForm');

  // campaign form validation
  const fv = FormValidation.formValidation(addNewCampaignForm, {
    fields: {
      name: {
        validators: {
          notEmpty: {
            message: 'Please enter campaign name'
          }
        }
      },
      company: {
        validators: {
          notEmpty: {
            message: 'Please enter your company'
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
    // adding or updating campaign when form successfully validate
    $.ajax({
      data: $('#addNewCampaignForm').serialize(),
      url: `${baseUrl}campaign-list`,
      type: 'POST',
      success: function (status) {
        dt_campaign.draw();
        offCanvasForm.offcanvas('hide');

        // sweetalert
        Swal.fire({
          icon: 'success',
          title: `Successfully ${status}!`,
          text: `Campaign ${status} Successfully.`,
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

 /* $('#campaigncompany').on('change', function(){
    console.log(" *** " + this.value);
    // dt_campaign_table.search(this.value).draw();   
  }); */



  // clearing form data when offcanvas hidden
  offCanvasForm.on('hidden.bs.offcanvas', function () {
    fv.resetForm(true);
  });

  
});
