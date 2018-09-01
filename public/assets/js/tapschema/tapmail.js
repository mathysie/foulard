tinymce.init({
    selector: '#tapmail',
    plugins: 'autoresize',
    menubar: false,
    toolbar: false,
    setup: function (ed) {
        ed.on('init', function () {
            tinymce.activeEditor.getBody().setAttribute('contenteditable', false);
        });
    }
});
