<?php
session_start();
session_unset();
session_destroy();

echo "Session destroyed";
header("Location: index.html");
exit();
?>
