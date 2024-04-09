document.addEventListener("DOMContentLoaded", function() {
    // Select all elements with the class 'date-time-ago'
    var agoElements = document.querySelectorAll('.date-time-ago');
    agoElements.forEach(function(elem) {
        // Parse the date text as UTC
        var date = moment.utc(elem.textContent);

        // Replace the text with 'time ago' format followed by a new line and <small> element
        elem.textContent = date.fromNow();
    });

    // Select all elements with the class 'date-time-format'
    var formatElements = document.querySelectorAll('.date-time-format');
    formatElements.forEach(function(elem) {
        // Parse the date text as UTC
        var date = moment.utc(elem.textContent);

        // Replace the text with 'formatted' format
        elem.textContent = date.format('MMMM Do YYYY, h:mm:ss a');
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
});