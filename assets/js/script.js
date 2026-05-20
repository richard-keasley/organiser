// Online Organiser Scripts

// Disable form submission if there are invalid fields
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Auto-hide alerts after 5 seconds
window.addEventListener('load', function() {
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.classList.add('fade');
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 150);
        }, 5000);
    });
});

// Confirm delete action
function confirmDelete() {
    return confirm('Are you sure you want to delete this item?');
}