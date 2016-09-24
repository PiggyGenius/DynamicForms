<?php 
$link = mysql_connect('','',''); 
if (!$link) { 
	die('Could not connect to MySQL: ' . mysql_error()); 
} 
echo 'Bien connecté à la base sous l\' utilisateur \'\'.'; mysql_close($link); 
?> 
