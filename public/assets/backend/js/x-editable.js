(function($) {
  'use strict';
  $(function()
  {
    if ($('#editable-form').length) {
      $.fn.editable.defaults.mode = 'inline';
      $.fn.editableform.buttons =
        '<button type="submit" class="btn btn-primary btn-sm editable-submit">' +
        '<i class="fa fa-fw fa-check"></i>' +
        '</button>' +
        '<button type="button" class="btn btn-default btn-sm editable-cancel">' +
        '<i class="fa fa-fw fa-times"></i>' +
        '</button>';

      $.ajaxSetup({
        headers: {
          'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $('#two_min_amount').editable({
        url: '/admin/2d-update-min',
        type: 'number',
        pk: 1,
        name: 'min_amount',
      });

      $('#two_max_amount').editable({
        url: '/admin/2d-update-max',
        type: 'number',
        pk: 1,
        name: 'max_amount',
      });

      $('#three_min_amount').editable({
        url: '/admin/3d-update-min',
        type: 'number',
        pk: 1,
        name: 'min_amount',
      });

      $('#three_max_amount').editable({
        url: '/admin/3d-update-max',
        type: 'number',
        pk: 1,
        name: 'max_amount',
      });

      $('#agent-phone').editable({
        url: '/admin/agent-phone',
        type: 'number',
        pk: 1,
        name: 'phone',
      });

      $('#agent-link').editable({
        url: '/admin/agent-link',
        type: 'text',
        pk: 1,
        name: 'link',
      });

      $('#phone').editable({
        url: '/admin/update-phone',
        type: 'number',
        pk: 1,
        name: 'phone',
      });

      $('#facebook').editable({
        url: '/admin/update-facebook',
        type: 'number',
        pk: 1,
        name: 'facebook',
      });

      $('#gmail').editable({
        url: '/admin/update-gmail',
        type: 'email',
        pk: 1,
        name: 'gmail',
      });

      $('#address').editable({
        url: '/admin/update-address',
        type: 'text',
        pk: 1,
        name: 'address',
      });

      $('#two_compensate').editable({
        url: '/admin/two_compensate',
        type: 'number',
        pk: 1,
        name: 'compensate',
      });

      $('#three_compensate').editable({
        url: '/admin/three_compensate',
        type: 'number',
        pk: 1,
        name: 'compensate',
      });

      $('#payment_status').editable({
        url: '/admin/paymentstatus',
        type: 'select',
        pk: 1,
        name: 'status',
        source: [
          { value: 'Pending', text: 'Pending' },
          { value: 'Approved', text: 'Approved' },
          { value: 'Rejected', text: 'Rejected' }
        ]
      });
    }
  });
})(jQuery);
