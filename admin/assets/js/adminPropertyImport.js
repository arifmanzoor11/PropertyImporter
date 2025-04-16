jQuery(document).ready(function($) {
    var frame;

    $('.custom-upload-button').on('click', function(e) {
        e.preventDefault();

        var button = $(this);
        var field = button.data('field');

        if (frame) {
            frame.open();
            return;
        }

        frame = wp.media({
            title: 'Select or Upload Images',
            button: {
                text: 'Use these images'
            },
            multiple: true
        });

        frame.on('select', function() {
            var attachment_ids = [];
            var attachments = frame.state().get('selection').toJSON();

            attachments.forEach(function(attachment) {
                attachment_ids.push(attachment.url);
            });

            var urls = attachment_ids.join(', ');
            $('#' + field).val(urls);
            
            var gallery = button.siblings('.custom-photo-gallery');
            gallery.empty();
            attachment_ids.forEach(function(url) {
                gallery.append('<div class="custom-photo-item"><img src="' + url + '" style="max-width:100%;height:auto;"></div>');
            });
        });

        frame.open();
    });
});



jQuery(document).ready(function($) {
    // Initialize the tabs
    $("#property-details-tabs").tabs();

    // Add Owner button functionality
    $(".add-owner-button").click(function() {
        var ownerIndex = $(".owner-entry").length;
        var newOwnerHtml = `
            <div class="owner-entry">
                <h4>Owner ${ownerIndex + 1}</h4>
                <p>
                    <label for="Owners[${ownerIndex}][RegisteredName]">RegisteredName</label>
                    <input type="text" id="Owners[${ownerIndex}][RegisteredName]" name="Owners[${ownerIndex}][RegisteredName]" value="" class="widefat" />
                </p>
                <p>
                    <label for="Owners[${ownerIndex}][Logo]">Logo</label>
                    <input type="text" id="Owners[${ownerIndex}][Logo]" name="Owners[${ownerIndex}][Logo]" value="" class="widefat" />
                </p>
                <p>
                    <label for="Owners[${ownerIndex}][ContactID]">ContactID</label>
                    <input type="text" id="Owners[${ownerIndex}][ContactID]" name="Owners[${ownerIndex}][ContactID]" value="" class="widefat" />
                </p>
            </div>
        `;
        $(newOwnerHtml).insertBefore(this);
    });
});



jQuery(document).ready(function($) {
    $("#property-details-tabs").tabs();

    // Add Document button functionality
    $(".add-document-button").click(function() {
        var documentIndex = $(".document-entry").length;
        var newDocumentHtml = '<div class="document-entry"><h4>Document ' + (documentIndex + 1) + '</h4>' +
            '<p><label for="DocumentMedia[' + documentIndex + '][Description]">Description</label>' +
            '<input type="text" id="DocumentMedia[' + documentIndex + '][Description]" name="DocumentMedia[' + documentIndex + '][Description]" value="" class="widefat" /></p>' +
            '<p><label for="DocumentMedia[' + documentIndex + '][URLs][]">URLs</label>' +
            '<textarea id="DocumentMedia[' + documentIndex + '][URLs][]" name="DocumentMedia[' + documentIndex + '][URLs][]" class="widefat"></textarea><span class="description">Enter each URL on a new line.</span></p>' +
            '<p><label for="DocumentMedia[' + documentIndex + '][Titles][]">Titles</label>' +
            '<textarea id="DocumentMedia[' + documentIndex + '][Titles][]" name="DocumentMedia[' + documentIndex + '][Titles][]" class="widefat"></textarea><span class="description">Enter each title on a new line.</span></p>' +
            '</div>';
        $(newDocumentHtml).insertBefore(this);
    });
});


