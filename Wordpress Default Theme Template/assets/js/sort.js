document.addEventListener('DOMContentLoaded', function() {
    var orderby = document.getElementById('orderby');
    orderby.addEventListener('change', function() {
        this.form.submit();
    });
});