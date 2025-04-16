<?php
function render_photo_data($post_id) {
    // Fetch the 'Photos' meta field
    $photos_meta = get_post_meta($post_id, 'Photos', true);

    // Check if the import option is enabled
    $enable_image_import = get_option('enable_image_import', false); // Get the option

    // If import is true and photos_meta is not empty, display images
    if ($enable_image_import && !empty($photos_meta)) {
        // Convert the comma-separated string into an array of photo IDs
        $photos = explode(',', $photos_meta);

        // Check if $photos is a valid array
        if (is_array($photos)) {
            ?>
            <style>
                .mySlides {display:none;}
                .w3-button {font-size: 24px; cursor: pointer;}
                .w3-display-left, .w3-display-right {position: absolute; top: 50%; width: auto;}
                .w3-display-left {left: 0;}
                .w3-display-right {right: 0;}
            </style>

            <div class="w3-content w3-display-container" style="position: relative">
                <?php
                // Iterate through each photo ID to display them in the slideshow
                foreach ($photos as $photo_id) {
                    // Get the URL of the photo
                    $photo_url = wp_get_attachment_url(trim($photo_id));
                    
                    // Check if photo URL is valid
                    if ($photo_url) {
                        ?>
                        <img class="mySlides" src="<?php echo esc_url($photo_url); ?>" style="width:100%">
                        <?php
                    }
                }
                ?>
                <button class="w3-button w3-black w3-display-left" onclick="plusDivs(-1)">&#10094;</button>
                <button class="w3-button w3-black w3-display-right" onclick="plusDivs(1)">&#10095;</button>
            </div>

            <script>
                var slideIndex = 1;
                showDivs(slideIndex);

                function plusDivs(n) {
                  showDivs(slideIndex += n);
                }

                function showDivs(n) {
                  var i;
                  var x = document.getElementsByClassName("mySlides");
                  if (n > x.length) {slideIndex = 1}
                  if (n < 1) {slideIndex = x.length}
                  for (i = 0; i < x.length; i++) {
                    x[i].style.display = "none";  
                  }
                  x[slideIndex-1].style.display = "block";  
                }
            </script>
            <?php
        }
    } else {
        // If import is false, retrieve and display serialized data from 'photo_urls_serialized'
        $serialized_photos = get_post_meta($post_id, 'photo_urls_serialized', true);

        if ($serialized_photos) {
            // Unserialize the data to convert it back to an array
            $photo_urls = unserialize($serialized_photos);

            // Check if $photo_urls is a valid array
            if (is_array($photo_urls)) {
                foreach ($photo_urls as $photo_url) {
                    ?>
                    <img src="<?php echo esc_url($photo_url); ?>" style="width:100%">
                    <?php
                }
            }
        } else {
            echo 'No photos found.';
        }
    }
}
?>
