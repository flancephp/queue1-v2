<script type="text/javascript" src="Assets/js/jquery-3.6.1.min.js"></script>
<script type="text/javascript" src="Assets/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="Assets/js/custom.js"></script>

<script>
    if (document.getElementById('dropdownMenu')) {
        document.addEventListener("DOMContentLoaded", function() {
            var dropdownMenu = document.getElementById('dropdownMenu');

            // Prevent the dropdown from closing when clicking inside it
            dropdownMenu.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevents the click event from bubbling up to the document
            });

            // Close the dropdown if the user clicks outside of it
            window.addEventListener('click', function(event) {
                var dropdownToggle = document.getElementById('dropBtn');
                if (!dropdownToggle.contains(event.target)) {
                    var dropdownMenu = document.querySelector('.dropdown__menu');
                    if (dropdownMenu.classList.contains('show')) {
                        dropdownMenu.classList.remove('show');
                    }
                }
            });
        });
    }
</script>