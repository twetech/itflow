<?php
require "/var/www/portal.twe.tech/includes/inc_all.php";
?>

<script>
(($) => {
  const linkToken = 'link-sandbox-3a9df5f7-408f-4726-a4a1-8c5968a11c3d'

  const handler = Plaid.create({
    token: linkToken,
    receivedRedirectUri: window.location.href,
    onSuccess: async (publicToken, metadata) => {
      await fetch("/api/exchange_public_token", {
        method: "POST",
        body: JSON.stringify({ public_token: publicToken }),
        headers: {
          "Content-Type": "application/json",
        },
      });
      const response = await fetch('/api/data', {
        method: 'GET',
      });
      const data = await response.json();
      window.location.href = "http://localhost:8080";          
    },
    onEvent: (eventName, metadata) => {
      console.log("Event:", eventName);
      console.log("Metadata:", metadata);
    },
    onExit: (error, metadata) => {
      console.log(error, metadata);
    },
  });
  handler.open();
})(jQuery);
</script>

<?php

require "/var/www/portal.twe.tech/includes/footer.php";
