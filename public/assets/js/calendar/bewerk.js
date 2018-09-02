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

tinymce.init({
    selector: '#description',
    menubar: false,
    toolbar: false,
    setup: function (ed) {
        ed.on('init', function () {
            tinymce.get('description').getBody().setAttribute('contenteditable', false);
        });
    }
});

tinymce.init({
    selector: '.tinymce',
    menubar: false,
    plugins: 'code lists',
    toolbar: 'undo redo | bold italic | bullist numlist | code removeformat',
    forced_root_block: false
});
