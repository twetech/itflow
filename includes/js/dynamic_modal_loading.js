$(document).ready(function() {
    // Event delegation
    $(document).on('click', '.loadModalContentBtn', function(e) {
        e.preventDefault();

        var clickedElement = $(this);
        var modalFile = clickedElement.data('modal-file');

        // Change modal body to loading message
        $('#dynamicModal .modal-body').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
        

        if (modalFile) {
            var fullPath = '/includes/modals/' + modalFile;

            $.get(fullPath, function(data) {
                // Create a temporary div to hold the loaded content
                var tempDiv = $('<div>').html(data);

                // Output the loaded content to the console
                // Extract and replace the modal title, body, and footer from the loaded content
                $('#dynamicModalLabel').html(tempDiv.find('.modal-title').html());
                $('#dynamicModal .modal-body').html(tempDiv.find('.modal-body').html());
                $('#dynamicModal .modal-footer').html(tempDiv.find('.modal-footer').html());


                // Reinitialize any plugins if needed, e.g., for select2 or date pickers in the modal

                // Show the modal
                $('#dynamicModal').modal('show');

                // Trigger a custom event to indicate that the modal content has been loaded
                $(document).trigger('modalContentLoaded');

                $(".select2").select2(
                    {
                        dropdownParent: $('#dynamicModal .modal-content')
                    }
                );
                
                document.querySelectorAll('textarea').forEach(function(textarea) {
                    textarea.addEventListener('click', function initTinyMCE() {
                        // This check ensures that TinyMCE is initialized only once for each textarea
                        if (!tinymce.get(this.id)) {
                            tinymce.init({
                                selector: '#' + this.id,
                                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate mentions tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
                                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                            });
                        }
                    }, { once: true });
                });

                
            }).fail(function() {
                console.error('Failed to load the modal content from ' + fullPath);
            });
        }
    });

    // Event delegation for reload modal content button
    $(document).on('click', '.reloadModalContentBtn', function(e) {
        e.preventDefault();

        var clickedElement = $(this);
        var modalFile = clickedElement.data('modal-file');

        // Change modal body to loading message
        $('#dynamicModal .modal-body').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');

        if (modalFile) {
            var fullPath = '/includes/modals/' + modalFile;

            $.get(fullPath, function(data) {
                // Create a temporary div to hold the loaded content
                var tempDiv = $('<div>').html(data);

                // Output the loaded content to the console
                // Extract and replace the modal title, body, and footer from the loaded content
                $('#dynamicModalLabel').html(tempDiv.find('.modal-title').html());
                $('#dynamicModal .modal-body').html(tempDiv.find('.modal-body').html());
                $('#dynamicModal .modal-footer').html(tempDiv.find('.modal-footer').html());

            }).fail(function() {
                console.error('Failed to load the modal content from ' + fullPath);
            });
        }
    });
});


