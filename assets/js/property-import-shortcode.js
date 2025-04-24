jQuery(document).ready(function ($) {
    $(".dropdown-property").select2();
});
// add slick slider
jQuery(document).ready(function ($) {
    $('.slider').slick({
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
});

function resetFilters() {

    sessionStorage.removeItem('returnToSearchUrl');
    const urlParams = new URLSearchParams(window.location.search);

    // Get list of parameters to preserve
    const preserveParams = ['page_id', 'post_type'];

    // Create new URLSearchParams with only preserved parameters
    const newParams = new URLSearchParams();
    preserveParams.forEach(param => {
        if (urlParams.has(param)) {
            newParams.set(param, urlParams.get(param));
        }
    });

    // Construct new URL
    let newUrl = window.location.pathname;
    if (newParams.toString()) {
        newUrl += '?' + newParams.toString();
    }

    // Save scroll position before redirect
    sessionStorage.setItem('scrollPos', window.scrollY);

    // Navigate to new URL
    window.location.href = newUrl;
}

// Optional: Restore scroll position on page load
document.addEventListener('DOMContentLoaded', () => {
    const scrollY = sessionStorage.getItem('scrollPos');
    if (scrollY) {
        window.scrollTo(0, parseInt(scrollY));
        sessionStorage.removeItem('scrollPos');
    }
});