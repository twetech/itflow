document.addEventListener("DOMContentLoaded", function() {
    // Select all elements with the class 'date-time-ago'
    var agoElements = document.querySelectorAll('.date-time-ago');
    agoElements.forEach(function(elem) {
        // Parse the date text as UTC
        var date = moment.utc(elem.textContent);

        // If in datatable, dont do anything
        if (elem.classList.contains('datatable')) {
            // Do nothing
        } else {
            // Replace the text with the formatted date
        }
    });

    // Select all elements with the class 'date-time-format'
    var formatElements = document.querySelectorAll('.date-time-format');
    formatElements.forEach(function(elem) {
        // Parse the date text as UTC
        var date = moment.utc(elem.textContent);

        // If in datatable, dont do anything
        if (elem.classList.contains('datatable')) {
            // Do nothing
        } else {
            // Replace the text with the formatted date
            elem.textContent = date.format('YYYY-MM-DD HH:mm:ss');
        }
    });

    var verboseElements = document.querySelectorAll('.date-time-worked');
    verboseElements.forEach(function(elem) {
        // Assuming the content is in HH:MM:SS format
        var parts = elem.textContent.split(':');
        var hours = parseInt(parts[0], 10);
        var minutes = parseInt(parts[1], 10);
        var seconds = parseInt(parts[2], 10);

        var verboseTime = '';
        if (hours > 0) verboseTime += hours + " Hour" + (hours > 1 ? "s" : "") + ", ";
        if (minutes > 0) verboseTime += minutes + " Minute" + (minutes > 1 ? "s" : "") + ", ";
        if (seconds > 0) verboseTime += seconds + " Second" + (seconds > 1 ? "s" : "");
        
        // Trim any trailing commas and spaces (in case of 00 seconds)
        verboseTime = verboseTime.replace(/,\s*$/, "");
        
        // Replace the element's text with the verbose format
        elem.textContent = verboseTime;
    });

    function updateDateTimeAgo() {
        $('.date-time-ago').each(function() {
            var date = moment.utc($(this).text());
            $(this).text(date.fromNow());
        });
    }
    
    // Attach the custom function to a custom event
    $(document).on('updateDateTime', updateDateTimeAgo);
});