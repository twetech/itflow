<?php
require "/var/www/portal.twe.tech/includes/inc_all.php";


$link_token = getPlaidLinkToken();

?>


<script>

function sendPublicToken(public_token) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "https://portal.twe.tech/api/plaid.php?public_token", true);
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      console.log(xhr.responseText);
    }
  };
  var data = JSON.stringify({
    public_token: public_token
  });
  xhr.send(data);
}

const handler = Plaid.create({
  token: "<?= $link_token ?>",
  onSuccess: function(public_token, metadata) {
    // Send the public_token to api to exchange for access_token
    sendPublicToken(public_token);
  },
  onExit: function(err, metadata) {
    // The user exited the Link flow.
    if (err != null) {
      // The user encountered a Plaid API error prior to exiting.
      console.log(err);
    }
    // metadata contains information about the institution
    // that the user selected and the most recent API request IDs.
    // Storing this information can be helpful for support.
  },
});

handler.open();
</script>


<?php
require "/var/www/portal.twe.tech/includes/footer.php";
