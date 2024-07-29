<?php
session_start();
session_destroy();
session_unset();
setcookie("userid", "", time() - 3600);
setcookie("admin", "", time() - 3600);
setcookie("nama", "", time() - 3600);
echo '<META HTTP-EQUIV="Refresh" Content="0; URL=signin.php">';
exit;
