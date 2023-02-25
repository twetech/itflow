function populateShareModal(client_id, item_type, item_ref_id) {

    // Populate HTML fields
    document.getElementById("share_client_id").value = client_id;
    document.getElementById("share_item_type").value = item_type;
    document.getElementById("share_item_ref_id").value = item_ref_id;

    // (re)Hide the URL/div (incase we're re-generating it)
    document.getElementById("div_share_link_output").hidden = true;
    document.getElementById("share_link").value = '';

    // Show form and generate button
    document.getElementById("div_share_link_form").hidden = false;
    document.getElementById("div_share_link_generate").hidden = false;
}

function generateShareLink() {
    let client_id = document.getElementById("share_client_id").value;
    let item_type = document.getElementById("share_item_type").value;
    let item_ref_id = document.getElementById("share_item_ref_id").value;
    let item_note = document.getElementById("share_note").value;
    let item_views = document.getElementById("share_views").value;
    let item_expires = document.getElementById("share_expires").value;

    // Check values are provided
    if (item_views && item_expires && item_note) {
        // Send a GET request to ajax.php as ajax.php?share_generate_link=true....
        jQuery.get(
            "ajax.php",
            {share_generate_link: 'true', client_id: client_id, type: item_type, id: item_ref_id, note: item_note ,views: item_views, expires: item_expires},
            function(data) {

                // If we get a response from ajax.php, parse it as JSON
                const response = JSON.parse(data);

                // Hide the div/form & button used to generate the link
                document.getElementById("div_share_link_form").hidden = true;
                document.getElementById("div_share_link_generate").hidden = true;

                // Show the readonly input containing the shared link
                document.getElementById("div_share_link_output").hidden = false;
                document.getElementById("share_link").value = response;

                // Copy link to clipboard
                navigator.clipboard.writeText(response);
            }
        );
    }
}
