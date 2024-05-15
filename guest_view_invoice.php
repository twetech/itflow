<?php

//redirect to page with same get parameters
header("Location: /portal/guest_view_invoice.php?".$_SERVER['QUERY_STRING']);
exit;