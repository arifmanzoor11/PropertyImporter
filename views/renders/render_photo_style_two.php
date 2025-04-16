<?php
function render_photo_style_two($post_id) {
    // Fetch the 'Photos' meta field
    $photos_meta = get_post_meta($post_id, 'Photos', true);
    $serialized_photos = get_post_meta($post_id, 'photo_urls_serialized', true);
    // Check if the import option is enabled
    $enable_image_import = get_option('enable_image_import', false); // Get the option
    
    // If import is true and photos_meta is not empty, display images
    if ($enable_image_import == '1') {
        // Convert the comma-separated string into an array of photo IDs
        $photos = explode(',', $photos_meta);

        // Check if $photos is a valid array
        if (is_array($photos)) {
            ?>
            <style>
                .mySlides {
                    display: none;
                    width: 100%;
                    height: 600px;
                    background-size: cover;
                    background-position: center;
                }
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
                        <div class="mySlides" style="background-image: url('<?php echo esc_url($photo_url); ?>');"></div>
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
        $first_image_photos = get_post_meta($post_id, 'first_image', true);
        if ($serialized_photos && $first_image_photos) {
            // Unserialize the data to convert it back to an array
            $photo_urls = unserialize($serialized_photos);
                // Check if $photo_urls is a valid array
            if (is_array($photo_urls)) {
                ?>
                <style>
                    .mySlides {
                        display: none;
                        width: 100%;
                        height: 750px;
                        background-size: cover;
                        background-position: center;
                    }
                    .w3-button {font-size: 24px; cursor: pointer;}
                    .w3-display-left, .w3-display-right {position: absolute; top: 50%; width: auto;}
                    .w3-display-left {left: 0;}
                    .w3-display-right {right: 0;}
                </style>

            <div class="w3-content w3-display-container" style="position: relative">
                <?php
                // Display the first image as the first slide
                if ($first_image_photos) { ?>
                    <div class="mySlides" style="background-image: url('<?php echo esc_url($first_image_photos); ?>');"></div>
                <?php }

                // Iterate through each photo URL to display them in the slideshow
                foreach ($photo_urls as $photo_url) {
                    // Check if photo URL is valid
                    if ($photo_url) { ?>
                        <div class="mySlides" style="background-image: url('<?php echo esc_url($photo_url); ?>');"></div>
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
        }
        if($first_image_photos && empty($serialized_photos)){ ?>
            <div style="background: url('<?php echo esc_url($first_image_photos); ?>');
            height:600px; background-size:cover; background-position:center;"></div>
        <?php
        }
    }
}
?>
