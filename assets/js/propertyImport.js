
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.tab-list li a');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            // Remove active class from all tabs and panes
            tabLinks.forEach(function(item) {
                item.parentElement.classList.remove('active');
            });
            tabPanes.forEach(function(pane) {
                pane.classList.remove('active');
            });

            // Add active class to the clicked tab and corresponding pane
            link.parentElement.classList.add('active');
            const targetPane = document.querySelector(link.getAttribute('href'));
            targetPane.classList.add('active');
        });
    });

    // Set the first tab and pane as active by default
    tabLinks[0].click();
});