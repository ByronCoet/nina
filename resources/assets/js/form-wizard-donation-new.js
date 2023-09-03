/**
 *  Form Wizard
 */

'use strict';

(function () {
  const select2 = $('.select2'),
    selectPicker = $('.selectpicker');

  // Wizard Validation
  // --------------------------------------------------------------------
  const wizardValidation = document.querySelector('#wizard-validation');
  if (typeof wizardValidation !== undefined && wizardValidation !== null) {
    // Wizard form
    const wizardValidationForm = wizardValidation.querySelector('#wizard-donation-new-form');
    // Wizard steps
    const wizardValidationFormStep1 = wizardValidationForm.querySelector('#account-details-validation');    
    const wizardValidationFormStep3 = wizardValidationForm.querySelector('#social-links-validation');
    // Wizard next prev button
    const wizardValidationNext = [].slice.call(wizardValidationForm.querySelectorAll('.btn-next'));
    const wizardValidationPrev = [].slice.call(wizardValidationForm.querySelectorAll('.btn-prev'));

    const validationStepper = new Stepper(wizardValidation, {
      linear: true
    });

    // Account details
    const FormValidation1 = FormValidation.formValidation(wizardValidationFormStep1, {
      fields: {
        formValidationFirstname: {
          validators: {
            notEmpty: {
              message: 'The first name is required'
            },
            stringLength: {
              min: 3,
              max: 30,
              message: 'The name must be more than 3 and less than 30 characters long'
            },
            regexp: {
              regexp: /^[a-zA-Z]+$/,
              message: 'The name can only consist of alphabetical'
            }
          }
        },formValidationSurname: {
          validators: {
            notEmpty: {
              message: 'The surname is required'
            },
            stringLength: {
              min: 3,
              max: 30,
              message: 'The name must be more than 3 and less than 30 characters long'
            },
            regexp: {
              regexp: /^[a-zA-Z]+$/,
              message: 'The surname can only consist of alphabetical'
            }
          }
        },
        formValidationMobile: {
          validators: {
            notEmpty: {
              message: 'The mobile is required'
            },
            stringLength: {
              min: 3,
              max: 13,
              message: 'The name must be more than 9 and less than 30 number long'
            },
            regexp: {
              regexp: /^[0-9]+$/,
              message: 'The mobile can only consist of numbers'
            }
            
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-sm-6'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      },
      init: instance => {

        const myFlatpickrInstance = $("#eventdate").flatpickr({
          enableTime: false,
          altInput: true,
          allowInput: true,
        });

        myFlatpickrInstance.setDate(new Date(), true);
        instance.on('plugins.message.placed', function (e) {
          //* Move the error message out of the `input-group` element
          if (e.element.parentElement.classList.contains('input-group')) {
            e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
          }
        });
      }
    }).on('core.form.valid', function () {
      // Jump to the next step when all fields in the current step are valid
      validationStepper.next();
    });

    
    // donation
    const FormValidation3 = FormValidation.formValidation(wizardValidationFormStep3, {
      fields: {
        eventdate: {
          validators: {
            notEmpty: {
              message: 'Event date is required'
            }
          }
        }
      },
      plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
          // Use this for enabling/changing valid/invalid class
          // eleInvalidClass: '',
          eleValidClass: '',
          rowSelector: '.col-sm-6'
        }),
        autoFocus: new FormValidation.plugins.AutoFocus(),
        submitButton: new FormValidation.plugins.SubmitButton()
      }
    }).on('core.form.valid', function () {
      console.log("submitting xxyy");      
      $.ajax({
        data: $('#wizard-donation-new-form').serialize(),
        url: `${baseUrl}newdonation`,
        type: 'POST',
        success: function (status) {
          // dt_user.draw();
          // offCanvasForm.offcanvas('hide');
          console.log("poof");
          // sweetalert
          Swal.fire({
            icon: 'success',
            title: `Saved`,
            text: `Donation data saved.`,
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
        },
        error: function (err) {
          // offCanvasForm.offcanvas('hide');
          const obj = JSON.parse(err.responseText);
          Swal.fire({
            title: 'Donation capture failed.',
            text: obj.message,
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          });
        }
      });


      // alert('Submitted..!!');
    });

    wizardValidationNext.forEach(item => {
      item.addEventListener('click', event => {
        // When click the Next button, we will validate the current step
        switch (validationStepper._currentIndex) {
          case 0:
            FormValidation1.validate();
            break;

          //case 1:
          //  FormValidation2.validate();
          //  break;

          case 1:
            FormValidation3.validate();
            break;

          default:
            break;
        }
      });
    });

    wizardValidationPrev.forEach(item => {
      item.addEventListener('click', event => {
        switch (validationStepper._currentIndex) {
          case 2:
            validationStepper.previous();
            break;

          case 1:
            validationStepper.previous();
            break;

          case 0:

          default:
            break;
        }
      });
    });
  }
})();
