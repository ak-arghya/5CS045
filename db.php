<?php
$mysqli = new mysqli("localhost","2463745","Arghya@117278","db2463745");
if ($mysqli -> connect_errno) {
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();
}
?>