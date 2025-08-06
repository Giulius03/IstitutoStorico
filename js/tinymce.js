tinymce.init({
    selector: 'textarea#content',
    plugins: 'lists code image link',
    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image | code',
    menubar: false,
    content_style: 'body { font-family: monospace; font-size: 14px; }',

    /* Configurazione per upload immagini */
    images_upload_url: 'utils/uploadImgTinyMCE.php', // Script per caricare immagini

    /* File picker personalizzato */
    file_picker_types: 'image',
    file_picker_callback: function(callback, value, meta) {
        if (meta.filetype === 'image') {
            // Apri popup per selezionare immagine
            var x = window.innerWidth / 2 - 200;
            var y = window.innerHeight / 2 - 200;
            window.open('imageSelectorTinyMCE.php', 'File Manager', 'width=400,height=400,left=' + x + ',top=' + y);
            
            // Funzione callback globale per selezione immagine
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