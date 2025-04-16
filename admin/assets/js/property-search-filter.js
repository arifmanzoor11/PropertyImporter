jQuery(document).ready(function($) {
    // Function to load properties on page load
    function loadProperties() {
        $.ajax({
            url: ajax_obj.ajax_url, // Use the localized AJAX URL
            data: {
                action: 'property_search' // Set the action to call the PHP function
            },
            type: 'GET',
            success: function(data) {
                $('#property-results').html(data); // Insert the results
            },
            error: function(xhr, status, error) {
                $('#property-results').html('<p>An error occurred: ' + error + '</p>');
            }
        });
    }

    // Load properties on page load
    loadProperties();

    // Trigger search on form submission
    $('#property-filter').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        var form = $(this);

        $.ajax({
            url: ajax_obj.ajax_url,
            data: form.serialize() + '&action=property_search', // Serialize form data
            type: 'GET',
            success: function(data) {
                $('#property-results').html(data); // Insert the search results
            },
            error: function(xhr, status, error) {
                $('#property-results').html('<p>An error occurred: ' + error + '</p>');
            }
        });
    });
});
