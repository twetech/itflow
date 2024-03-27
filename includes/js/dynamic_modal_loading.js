$(document).ready(function() {
    // Event delegation
    $(document).on('click', '.loadModalContentBtn', function(e) {
        e.preventDefault();

        var clickedElement = $(this);
        var modalFile = clickedElement.data('modal-file');

        if (modalFile) {
            var fullPath = '/includes/modals/' + modalFile;

            $.get(fullPath, function(data) {
                // Create a temporary div to hold the loaded content
                var tempDiv = $('<div>').html(data);


                // Extract and replace the modal title, body, and footer from the loaded content
                $('#dynamicModalLabel').html(tempDiv.find('.modal-title').html());
                $('#dynamicModal .modal-body').html(tempDiv.find('.modal-body').html());
                $('#dynamicModal .modal-footer').html(tempDiv.find('.modal-footer').html());


                // Reinitialize any plugins if needed, e.g., for select2 or date pickers in the modal

                // Show the modal
                $('#dynamicModal').modal('show');

                $('select').select2({
                    dropdownParent: $('#dynamicModal')
                });
                
            }).fail(function() {
                console.error('Failed to load the modal content from ' + fullPath);
            });
        }
    });
});


