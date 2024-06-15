<?php

//redirect to /pages/ticket.php with all the same parameters
header("Location: /pages/ticket.php?".http_build_query($_GET));
