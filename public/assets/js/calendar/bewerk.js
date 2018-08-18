$(function () {
    $('input[name^="kwn-bij-"]').change(function() {
        var id = $(this).attr('id').match(/(\d+)/)[1];
        var porties = $('#kwn-port-'+id);

        if ($(this).attr('value') == 1) {
            porties.prop('readonly', false);
        }

        if ($(this).attr('value') == 0) {
            porties.prop('readonly', true);
            porties.val('');
        }
    });
});
