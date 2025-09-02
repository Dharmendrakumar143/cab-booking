<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    function disableButtonOnClick(buttonElement) {
        buttonElement.disabled = true; // Disable the button
    }
</script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const links = document.querySelectorAll(".side-bar .nav-link");

        // Get the current URL
        const currentUrl = window.location.href;

        links.forEach(link => {
            // Check if the current URL contains the href of the link
            if (currentUrl.includes(link.href)) {
                // Remove active class from other links
                links.forEach(link => link.classList.remove("active"));
                
                // Add active class to the matching link
                link.classList.add("active");
            }
        });
    });


</script>
