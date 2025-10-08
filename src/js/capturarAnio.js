 document.addEventListener("DOMContentLoaded", function() {
            const currentYear = new Date().getFullYear();
     
            document.querySelectorAll("#year").forEach(function(element) {
                element.textContent = currentYear;
            });
        });