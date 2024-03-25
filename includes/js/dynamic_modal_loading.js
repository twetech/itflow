$(document).ready(function() {
    // Event delegation
    $(document).on('click', '.loadModalContentBtn', function(e) {
        e.preventDefault();
        console.log('Clicked on a button with the class loadModalContentBtn');

        var clickedElement = $(this);
        console.log('Clicked element:', clickedElement);
        var modalFile = clickedElement.data('modal-file');
        console.log('Modal file:', modalFile);

        if (modalFile) {
            var fullPath = '/includes/modals/' + modalFile;
            console.log('Full path:', fullPath);

            $.get(fullPath, function(data) {
                // Create a temporary div to hold the loaded content
                var tempDiv = $('<div>').html(data);
                console.log('Loaded content:', tempDiv);


                console.log('Replacing Modal Content')
                // Extract and replace the modal title, body, and footer from the loaded content
                $('#dynamicModalLabel').html(tempDiv.find('.modal-title').html());
                console.log('Modal title:', tempDiv.find('.modal-title').html());
                $('#dynamicModal .modal-body').html(tempDiv.find('.modal-body').html());
                console.log('Modal body:', tempDiv.find('.modal-body').html());
                $('#dynamicModal .modal-footer').html(tempDiv.find('.modal-footer').html());
                console.log('Modal footer:', tempDiv.find('.modal-footer').html());
                console.log('Modal content replaced');

                // Reinitialize any plugins if needed, e.g., for select2 or date pickers in the modal

                // Show the modal
                console.log('Showing the modal');
                $('#dynamicModal').modal('show');
                console.log('Modal shown');
                
            }).fail(function() {
                console.error('Failed to load the modal content from ' + fullPath);
            });
        }
    });
});

$(document).ready(function() {
    $(document).on('click', function(e) {
        console.log('Document was clicked');
    });
});