jQuery(document).ready(function ($) {
    let mediaUploader;

    $('.term-image-upload').on('click', function (e) {
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
        mediaUploader.on('select', function () {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#term-image').val(attachment.id);
            $('#term-image-preview').html('<img src="' + attachment.url + '" alt="" style="max-width: 100%;">');
        });
        mediaUploader.open();
    });
});
