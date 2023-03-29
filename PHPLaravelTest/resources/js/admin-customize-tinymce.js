// Customize TinyMCE WYSIWYG
window.tinymce_init_callback = function(editor, options = {}) {
    if (editor) {
        if (typeof editor === 'string' || typeof editor === 'number') {
            editor = window.tinyMCE.get(editor)
        }

        editor && editor.destroy()
    }

    window.tinyMCE.init({
        content_css: '/css/tinyMCE.css',
        body_class: 'wysiwyg-content',
        menubar: false,
        selector: 'textarea.richTextBox',
        skin_url: `${window.$('meta[name="assets-path"]').attr('content')}?path=js/skins/voyager`,
        min_height: 100,
        resize: 'vertical',
        plugins: 'link, image, lists, code',
        extended_valid_elements:
            'input[id|name|value|type|class|style|required|placeholder|autocomplete|onclick]',
        file_browser_callback: function(field_name, url, type) {
            if (type == 'image') {
                window.$('#upload_file').trigger('click')
            }
        },
        setup: function(editor) {
            // Force changes in wysiwyg to update the hidden textarea, to avoid HTML5 validation preventing form submission.
            // If you want to display an error when a wysiwyg is invalid upon attempted submission, add an `oninvalid` attr to the <textarea>
            editor.on('change', function(event) {
                editor.save()

                options.onChange && options.onChange(event.target.targetElm.value)
            })
        },
        toolbar:
            'formatselect alignleft aligncenter alignright alignjustify | bold italic | bullist numlist outdent indent | link | code',
        block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3',
        formats: {
            underline: { inline: 'u' },
            p: { block: 'p' },
            h1: { block: 'h1' },
            h2: { block: 'h2' },
            h3: { block: 'h3' },
        },
        convert_urls: false,
        image_caption: true,
        image_title: true,
    })
}
