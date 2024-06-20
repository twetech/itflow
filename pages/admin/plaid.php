<?php
require "/var/www/portal.twe.tech/includes/inc_all.php";


if (!isset($_GET['oauth_state_id'])) {
    // Get link token from Plaid API
    $link_token = getPlaidLinkToken();
    // Save link token to session
    $_SESSION['link_token'] = $link_token;
    $redirect_uri = "";
} else {
  $oauth_state_id = $_GET['oauth_state_id'];
  $link_token = $_SESSION['link_token'];
  $redirect_uri = "receivedRedirectUri: window.location.href,";
}

// check if access token is set in database
$access_token_sql = "SELECT * FROM plaid_access_tokens WHERE client_id = 1";
$num_rows = mysqli_num_rows(mysqli_query($mysqli, $access_token_sql));

if ($link_token == null) {
  echo "Error getting link token";
} elseif ($num_rows == 0) { ?>
  <button id="link-button" class="btn btn-primary mb-2">
    <i class="fas fa-link"></i>
      Link Account
  </button>
<?php } 

if ($num_rows > 0) {
  echo "<a href='/post.php?sync_transactions' class='btn btn-primary'>
    <i class='fas fa-sync'></i>
    Sync Transactions
  </a>";
}


?>

<script>

function sendPublicToken(public_token) {
  console.log(public_token);
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "https://portal.twe.tech/api/plaid.php?public_token", true);
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      console.log(xhr.responseText);
    }
  };
  console.log("public_token: " + public_token);
  var data = JSON.stringify({
    public_token: public_token
  });
  xhr.send(data);
}

const handler = Plaid.create({
  token: "<?= $link_token ?>",
  <?= $redirect_uri ?>
  onSuccess: function(public_token, metadata) {
    // Send the public_token to api to exchange for access_token
    sendPublicToken(public_token);
    // Reload the page
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

document.getElementById('link-button').addEventListener('click', function() {
  handler.open();
});
</script>


<?php
require "/var/www/portal.twe.tech/includes/footer.php";
