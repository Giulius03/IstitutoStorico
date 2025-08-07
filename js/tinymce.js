tinymce.init({
    selector: 'textarea#content',
    plugins: 'lists code image link',
    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code',
    menubar: false,
    content_style: 'body { font-family: monospace; font-size: 14px; }',

    file_picker_types: 'image',
    file_picker_callback: function(callback, value, meta) {
        if (meta.filetype === 'image') {
            var x = window.innerWidth / 2 - 200;
            var y = window.innerHeight / 2 - 200;
            window.open('../imageSelectorTinyMCE.php', 'File Manager', 'width=400,height=400,left=' + x + ',top=' + y);
            
            window.selectImage = function(url) {
                callback(url, { alt: '' });
            };
        }
    },

    setup: function(editor) {
        editor.on('init', function() {
            if (document.getElementById("tinymceDisabled").value === "true") {
                tinymce.get('content').mode.set('readonly');
            }
        });
    }
});

function select(url) {
    window.opener.selectImage(url);
    window.close();
}