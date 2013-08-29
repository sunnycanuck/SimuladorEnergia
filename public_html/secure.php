<?php 
session_start();

if(!empty($_SESSION['log']))
	header("Location:index.php");

?>