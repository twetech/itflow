$(document).ready(function() {
    $(document).on('modalContentLoaded', function() {
        console.log('The modal content has been loaded');
        // Bind event handlers to the inputs after the modal content has been loaded


        // Get the description of the selected product
        $('#product_id').change(function() {
            var product_id = $(this).val();
            $.ajax({
                url: 'includes/ajax/ajax.php?',
                type: 'GET',
                success: function(response) {
                    $('#description').val(response);
                }
            });
        });
    });
});